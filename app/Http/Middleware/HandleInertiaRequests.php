<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Inertia\Middleware;
use Laravel\Fortify\Features;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return [
            ...parent::share($request),
            'name' => Config::string('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'passkeyRoutes' => $this->passkeyRoutes(),
        ];
    }

    /**
     * Passkey URLs come from the server because Fortify only registers these routes when passkeys are enabled.
     *
     * @return array{login: array{options: string, submit: string}, confirm: array{options: string, submit: string}}|null
     */
    private function passkeyRoutes(): ?array
    {
        if (! Features::enabled(Features::passkeys())) {
            return null;
        }

        return [
            'login' => [
                'options' => route('passkey.login-options'),
                'submit' => route('passkey.login'),
            ],
            'confirm' => [
                'options' => route('passkey.confirm-options'),
                'submit' => route('passkey.confirm'),
            ],
        ];
    }
}
