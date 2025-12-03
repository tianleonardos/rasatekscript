<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Set different session cookie for admin to avoid conflicts with customer sessions
        config(['session.cookie' => 'admin-session']);

        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return redirect('/')->with('error', 'Akses ditolak! Hanya admin yang diizinkan.');
        }

        return $next($request);
    }
}