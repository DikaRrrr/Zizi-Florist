<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        return view('frontend.v_profile.index', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);


        $user = User::find(Auth::id());

        if ($request->hasFile('avatar')) {

            // Hapus foto lama jika ada (dan bukan foto bawaan)
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Simpan foto baru
            $path = $request->file('avatar')->store('avatars', 'public');

            // Update database
            $user->avatar = $path;
            $user->save(); // Sekarang ini pasti berhasil!
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
