<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika url mengandung 'konsumen', redirect ke login konsumen
        if ($request->is('konsumen/*')) {
            return route('konsumen.login');
        }
        // Default redirect ke loginpegawai
        return route('loginpegawai');
    }
}
