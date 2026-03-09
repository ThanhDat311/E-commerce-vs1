<?php

it('switches available locale and updates session', function () {
    $response = $this->get(route('locale.switch', ['lang' => 'vi']));

    $response->assertStatus(302); // Redirect back
    expect(session('locale'))->toBe('vi');
});

it('ignores unsupported locales', function () {
    $this->withSession(['locale' => 'en']);

    $response = $this->get(route('locale.switch', ['lang' => 'fr']));

    $response->assertStatus(302);
    expect(session('locale'))->toBe('en'); // remains default/previous
});
