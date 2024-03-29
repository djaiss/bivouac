<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => [
                    'id' => $request->user()?->id,
                    'name' => $request->user()?->name,
                    'avatar' => $request->user()?->avatar,
                    'permissions' => $request->user()?->permissions,
                ],
            ],
            'url' => [
                'search' => route('search.index'),
                'home' => route('home.index'),
                'projects' => route('projects.index'),
                'one_on_ones' => route('oneonones.index'),
                'profile' => route('profile.edit'),
                'settings' => [
                    'index' => route('settings.index'),
                ],
                'locale' => [
                    'update_fr' => route('locale.update', ['locale' => 'fr']),
                    'update_en' => route('locale.update', ['locale' => 'en']),
                ],
            ],
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
        ]);
    }
}
