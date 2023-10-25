<?php

namespace App\Http\Middleware;

use App\Models\OneOnOneEntry;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOneOnOneEntry
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route()->parameter('entry'))) {
            $id = (int) $request->route()->parameter('entry');
        } else {
            $id = $request->route()->parameter('entry')->id;
        }

        try {
            $oneOnOneEntry = OneOnOneEntry::where('one_on_one_id', $request->oneOnOne->id)
                ->findOrFail($id);

            $request->attributes->add(['oneOnOneEntry' => $oneOnOneEntry]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
