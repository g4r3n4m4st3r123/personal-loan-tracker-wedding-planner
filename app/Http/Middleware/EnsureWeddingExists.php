<?php

namespace App\Http\Middleware;

use App\Models\Wedding;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWeddingExists
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | Make sure the user is authenticated
        |--------------------------------------------------------------------------
        |
        | This middleware is normally used after the auth middleware.
        |
        */

        if (!auth()->check()) {
            return redirect()->route('login');
        }


        /*
        |--------------------------------------------------------------------------
        | Check for the current user's wedding
        |--------------------------------------------------------------------------
        |
        | Each user can only access their own wedding.
        |
        */

        $hasWedding = Wedding::where(
            'user_id',
            auth()->id()
        )->exists();


        /*
        |--------------------------------------------------------------------------
        | No Wedding Yet
        |--------------------------------------------------------------------------
        |
        | Send the user to the Wedding Overview where they can create
        | their own wedding.
        |
        */

        if (!$hasWedding) {

            return redirect()
                ->route('wedding.index')
                ->with(
                    'error',
                    'Please create your wedding details first before opening this section.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Wedding Exists
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}