<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class checkEmployerProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check employer role
        if ($user->hasRole('employer')) {

            // Check if employer profile completed
            if (!$user->employerProfile) {
                // Redirect to complete profile
                return redirect()->route('employer.profiles')
                                 ->with('error', 'Please complete your profile before creating a job.');
            }
        }
        return $next($request);
    }
}
