<?php

declare(strict_types=1);

use App\Models\User;

it('lets an unverified user resend the verification email from the profile page', function (): void {
    $this->actingAs(User::factory()->unverified()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));

    $page->assertSee('Your email address is unverified.')
        ->click('Click here to resend the verification email.')
        ->assertSee('A new verification link has been sent to your email address.')
        ->assertNoJavaScriptErrors();
});

it('does not show the verification notice to a verified user', function (): void {
    $this->actingAs(User::factory()->withoutTwoFactor()->create());

    $page = visit(route('user-profile.edit'));

    $page->assertSee('Profile information')
        ->assertDontSee('Your email address is unverified.')
        ->assertNoJavaScriptErrors();
});
