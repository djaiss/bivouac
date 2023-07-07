<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProject
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route()->parameter('project'))) {
            $id = (int) $request->route()->parameter('project');
        } else {
            $id = $request->route()->parameter('project')->id;
        }

        try {
            $project = Project::where('organization_id', $request->user()->organization_id)
                ->findOrFail($id);

            if ($project->users()->where('user_id', $request->user()->id)->doesntExist()) {
                throw new ModelNotFoundException;
            }

            $request->attributes->add(['project' => $project]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
