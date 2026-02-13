<?php

/** @var \Tests\TestCase $this */

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->admin = User::factory()->create(['role_id' => 1, 'is_active' => true]);
});

it('shows settings page for admin', function () {
    $this->actingAs($this->admin)
        ->get(route('admin.settings.index'))
        ->assertStatus(200)
        ->assertSee('System Settings')
        ->assertSee('General Information');
});

it('can update general settings', function () {
    $this->actingAs($this->admin)
        ->post(route('admin.settings.update'), [
            'group' => 'general',
            'site_name' => 'New Site Name',
            'currency_symbol' => '€',
            // maintenance_mode unchecked = 0
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    expect(Setting::get('site_name'))->toBe('New Site Name');
    expect(Setting::get('currency_symbol'))->toBe('€');
    expect(Setting::get('maintenance_mode'))->toBe('0');
});

it('can update boolean settings', function () {
    // Enable security options
    $this->actingAs($this->admin)
        ->post(route('admin.settings.update'), [
            'group' => 'security',
            'allow_vendor_registration' => '1',
            'require_email_verification' => '1',
        ])
        ->assertRedirect();

    expect(Setting::get('allow_vendor_registration'))->toBe('1');

    // Disable one
    $this->actingAs($this->admin)
        ->post(route('admin.settings.update'), [
            'group' => 'security',
            'allow_vendor_registration' => '1',
            // require_email_verification missing = 0
        ])
        ->assertRedirect();

    expect(Setting::get('require_email_verification'))->toBe('0');
});

it('masks sensitive data in view', function () {
    Setting::set('stripe_secret', 'sk_live_12345');

    $this->actingAs($this->admin)
        ->get(route('admin.settings.index'))
        ->assertStatus(200)
        ->assertSee('******');

    // Ensure we don't accidentally overwrite with asterisks if sent back
    $this->actingAs($this->admin)
        ->post(route('admin.settings.update'), [
            'group' => 'payment',
            'stripe_secret' => '******',
            'stripe_key' => 'pk_live_new',
        ]);

    expect(Setting::get('stripe_secret'))->toBe('sk_live_12345');
    expect(Setting::get('stripe_key'))->toBe('pk_live_new');
});
