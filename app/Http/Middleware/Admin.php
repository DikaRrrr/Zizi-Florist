<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek: Sudah Login? DAN Role-nya Admin?
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Silakan lewat
        }

        // Kalau bukan, tendang ke Home
        return redirect('/')->with('error', 'Anda bukan Admin!');
    }
}
