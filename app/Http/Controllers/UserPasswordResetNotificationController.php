<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateUserPasswordResetNotification;
use App\Http\Requests\CreateUserPasswordResetNotificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final readonly class UserPasswordResetNotificationController
{
    public function create(Request $request): Response
    {
        return Inertia::render('user-password-reset-notification/create', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(
        CreateUserPasswordResetNotificationRequest $request,
        CreateUserPasswordResetNotification $action
    ): RedirectResponse {
        $action->handle(['email' => $request->string('email')->value()]);

        return back()->with('status', __('A reset link will be sent if the account exists.'));
    }
}
