<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Stringable;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

final class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->bootFortifyDefaults();
        $this->bootRateLimitingDefaults();
    }

    private function bootFortifyDefaults(): void
    {
        Fortify::loginView(
            view: fn (Request $request) => Inertia::render(
                component: 'session/create',
                props: [
                    'canResetPassword' => Features::enabled(Features::resetPasswords()),
                    'status' => $request->session()->get('status'),
                ]
            )
        );

        Fortify::twoFactorChallengeView(
            view: fn () => Inertia::render(
                component: 'user-two-factor-authentication-challenge/show'
            )
        );

        Fortify::confirmPasswordView(
            view: fn () => Inertia::render(
                component: 'user-password-confirmation/create'
            )
        );
    }

    private function bootRateLimitingDefaults(): void
    {
        RateLimiter::for(
            name: 'login',
            callback: fn (Request $request) => Limit::perMinute(maxAttempts: 5)->by(
                key: $request->string('email')->value().$request->ip()
            )
        );
        RateLimiter::for(
            name: 'two-factor',
            callback: fn (Request $request) => Limit::perMinute(maxAttempts: 5)->by(
                key: $request->session()->get('login.id')
            )
        );
        RateLimiter::for(
            name: 'passkeys',
            // Option requests carry no credential yet, so fall back to the session to keep throttling per visitor.
            callback: fn (Request $request) => Limit::perMinute(maxAttempts: 10)->by(
                key: $request->string('credential.id')
                    ->whenEmpty(fn (Stringable $key): Stringable => $key->append($request->session()->getId()))
                    ->append('|', (string) $request->ip())
                    ->value()
            )
        );
    }
}
