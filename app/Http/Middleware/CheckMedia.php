<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CheckMedia
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_string($request->route()->parameter('media'))) {
            $id = (int) $request->route()->parameter('media');
        } else {
            $id = $request->route()->parameter('media')->id;
        }

        try {
            $media = Media::findOrFail($id);

            switch ($media->model_type) {
                case Message::class:
                    $message = Message::findOrFail($media->model_id);

                    if ($message->project->organization_id !== auth()->user()->organization_id) {
                        abort(401);
                    }
                    break;

                default:
                    // code...
                    break;
            }

            return $next($request);
        } catch (ModelNotFoundException) {
            abort(401);
        }
    }
}
