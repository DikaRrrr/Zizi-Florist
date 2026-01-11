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
        // Ambil semua user, urutkan admin dulu baru user biasa
        $users = User::orderBy('role', 'asc')->latest()->get();
        return view('backend.v_user.index', compact('users'));
    }

    public function create()
    {
        return view('backend.v_user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,customer',
            'hp'       => 'nullable|numeric',
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // Enkripsi Password
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
            'role'     => 'required|in:admin,customer',
        ]);

        // Setup data yang mau diupdate
        $data = [
            'nama'   => $request->nama,
            'email'  => $request->email,
            'role'   => $request->role,
            'hp'     => $request->hp,
            'alamat' => $request->alamat,
        ];

        // Cek apakah password diisi? Jika ya, update. Jika kosong, biarkan password lama.
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Data User diperbarui');
    }

    public function destroy($id)
    {
        // Cegah menghapus diri sendiri
        if (Auth::id() == $id) {
        return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
    }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
