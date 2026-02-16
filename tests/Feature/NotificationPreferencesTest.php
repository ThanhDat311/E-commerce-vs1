<?php

use App\Models\User;

test('notification settings page is displayed', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/my-notifications');

    $response->assertOk();
    $response->assertViewIs('profile.notifications.index');
    $response->assertViewHas('preferences');
});

test('guest cannot access notification settings', function () {
    $response = $this->get('/my-notifications');

    $response->assertRedirect('/login');
});

test('default preferences are loaded when user has none', function () {
    $user = User::factory()->create(['notification_preferences' => null]);

    $response = $this->actingAs($user)->get('/my-notifications');

    $response->assertOk();

    $preferences = $response->viewData('preferences');
    expect($preferences['order_updates'])->toBeTrue();
    expect($preferences['promotions'])->toBeFalse();
    expect($preferences['security_alerts'])->toBeTrue();
});

test('user can save notification preferences', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/my-notifications', [
        'order_updates' => '1',
        'promotions' => '1',
        'newsletter' => '1',
        'security_alerts' => '1',
        'email_notifications' => '1',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $user->refresh();
    $prefs = $user->notification_preferences;

    expect($prefs['order_updates'])->toBeTrue();
    expect($prefs['promotions'])->toBeTrue();
    expect($prefs['newsletter'])->toBeTrue();
    expect($prefs['security_alerts'])->toBeTrue();
    expect($prefs['email_notifications'])->toBeTrue();
    expect($prefs['sms_notifications'])->toBeFalse();
    expect($prefs['push_notifications'])->toBeFalse();
});

test('unchecked preferences are saved as false', function () {
    $user = User::factory()->create(['notification_preferences' => [
        'order_updates' => true,
        'promotions' => true,
    ]]);

    $response = $this->actingAs($user)->post('/my-notifications', [
        // no checkboxes checked – all should be false
    ]);

    $response->assertRedirect();

    $user->refresh();
    $prefs = $user->notification_preferences;

    expect($prefs['order_updates'])->toBeFalse();
    expect($prefs['promotions'])->toBeFalse();
});
