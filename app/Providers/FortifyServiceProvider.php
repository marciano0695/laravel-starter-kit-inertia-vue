<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
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
    }
}
