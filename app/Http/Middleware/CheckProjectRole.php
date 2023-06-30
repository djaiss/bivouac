<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckProjectRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $requestedProjectId = $request->route()->parameter('project');

        try {
            $project = Project::where('organization_id', $request->user()->organization_id)
                ->findOrFail($requestedProjectId);

            $request->attributes->add(['project' => $project]);

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
