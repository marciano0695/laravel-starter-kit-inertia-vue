<?php

declare(strict_types=1);

use App\Models\User;

it('shows a toast after updating the profile', function (): void {
    $user = User::factory()->withoutTwoFactor()->create();

    $this->actingAs($user);

    $page = visit(route('user-profile.edit'));

    $page->fill('name', 'Taylor Otwell')
        ->click('@update-profile-button')
        ->assertSee(__('Profile updated.'))
        ->assertNoJavaScriptErrors();
});
