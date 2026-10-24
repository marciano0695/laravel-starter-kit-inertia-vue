<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;
use Laravel\Passkeys\Passkey;

final readonly class UserPasskeyController implements HasMiddleware
{
    public static function middleware(): array
    {
        return Features::optionEnabled(Features::passkeys(), 'confirmPassword')
            ? [new Middleware('password.confirm', only: ['index'])]
            : [];
    }

    public function index(#[CurrentUser] User $user): Response
    {
        // Passkeys are opt-in, so the page only exists once the feature is enabled.
        abort_unless(Features::canManagePasskeys(), 404);

        return Inertia::render('user-passkey/index', [
            'passkeys' => $this->passkeys($user),
        ]);
    }

    /**
     * Wayfinder types the page props from this shape, and PHPStan keeps it honest.
     *
     * @return array<int, array{id: int, name: string, destroy_url: string, authenticator: string|null, created_at_diff: string|null, last_used_at_diff: string|null}>
     */
    private function passkeys(User $user): array
    {
        return $user->passkeys()
            ->latest()
            ->get()
            ->map(fn (Passkey $passkey): array => [
                'id' => $passkey->id,
                'name' => $passkey->name,
                'destroy_url' => route('passkey.destroy', $passkey),
                'authenticator' => $passkey->authenticator,
                'created_at_diff' => $passkey->created_at?->diffForHumans(),
                'last_used_at_diff' => $passkey->last_used_at?->diffForHumans(),
            ])
            ->values()
            ->all();
    }
}
