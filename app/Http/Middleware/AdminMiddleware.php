<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }
            return redirect()->route('admin.login')->with('error', 'অ্যাডমিন প্যানেলে প্রবেশের অনুমতি নেই।');
        }

        if (auth()->user()->isBanned()) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।');
        }

        return $next($request);
    }
}
