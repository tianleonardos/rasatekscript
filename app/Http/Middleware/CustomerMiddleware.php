<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Set different session cookie for customer to avoid conflicts with admin sessions
        config(['session.cookie' => 'customer-session']);

        if (!auth()->check() || !auth()->user()->isCustomer()) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}