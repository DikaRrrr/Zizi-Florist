<?php

namespace App\Http\Controllers\BackEnd;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role', 'asc')->latest()->get();
        return view('backend.v_user.index', compact('users'));
    }

    public function create()
    {
        return view('backend.v_user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:admin,customer',
            'hp'       => 'nullable|numeric|digits_between:10,15',
            'alamat'   => 'nullable|string',
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'hp'       => $request->hp,
            'alamat'   => $request->alamat,
        ]);

        return redirect()->route('admin.user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.v_user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => 'required|in:admin,customer',
            'password' => 'nullable|string|min:8',
            'hp'     => 'nullable|numeric|digits_between:10,15',
            'alamat' => 'nullable|string',
        ]);

        $data = [
            'nama'   => $request->nama,
            'email'  => $request->email,
            'role'   => $request->role,
            'hp'     => $request->hp,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Data User diperbarui');
    }

    public function destroy($id)
    {
        if (Auth::id() == $id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }

    public function formCetak()
    {
        $judul = "Form Cetak Laporan User";
        return view('backend.v_user.form', compact('judul'));
    }

    public function cetak(Request $request)
    {
        $request->validate([
            'tanggal_awal'  => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $tanggalAwal  = $request->tanggal_awal;
        $tanggalAkhir = $request->tanggal_akhir;


        $cetak = User::whereDate('created_at', '>=', $tanggalAwal)
            ->whereDate('created_at', '<=', $tanggalAkhir)
            ->orderBy('role', 'asc')
            ->orderBy('nama', 'asc')
            ->get();

        $judul = "Laporan Data Pengguna (User)";

        return view('backend.v_user.cetak', compact('cetak', 'judul', 'tanggalAwal', 'tanggalAkhir'));
    }
}
