<?php

namespace App\Http\Middleware;

use App\Models\OneOnOne;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOneOnOne
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route()->parameter('oneOnOne'))) {
            $id = (int) $request->route()->parameter('oneOnOne');
        } else {
            $id = $request->route()->parameter('oneOnOne')->id;
        }

        try {
            $oneOnOne = OneOnOne::where('user_id', auth()->user()->id)
                ->orWhere('other_user_id', auth()->user()->id)
                ->findOrFail($id);

            $request->attributes->add(['oneOnOne' => $oneOnOne]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
