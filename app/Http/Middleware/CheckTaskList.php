<?php

namespace App\Http\Middleware;

use App\Models\TaskList;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTaskList
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route()->parameter('taskList'))) {
            $id = (int) $request->route()->parameter('taskList');
        } else {
            $id = $request->route()->parameter('taskList')->id;
        }

        try {
            $taskList = TaskList::where('organization_id', $request->user()->organization_id)
                ->findOrFail($id);

            $request->attributes->add(['taskList' => $taskList]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
