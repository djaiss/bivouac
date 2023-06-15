<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckAdministratorRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->permissions === User::ROLE_ADMINISTRATOR ||
            $request->user()->permissions === User::ROLE_ACCOUNT_MANAGER) {
            return $next($request);
        }

        abort(401);
    }
}
