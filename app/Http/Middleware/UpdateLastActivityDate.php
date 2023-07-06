<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastActivityDate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        if (! $request->user()->last_active_at || $request->user()->last_active_at->isPast()) {
            // this is a workaround to make sure we don't trigger the search
            // indexing feature on the User model when updating the last active date
            User::withoutSyncingToSearch(function () use ($request): void {
                $request->user()->update([
                    'last_active_at' => now(),
                ]);
            });
        }

        return $next($request);
    }
}
