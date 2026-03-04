<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('contact page can be rendered', function () {
    $response = $this->get(route('contact.index'));
    $response->assertStatus(200);
    $response->assertViewIs('contact.index');
});

test('contact form can be submitted', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Support Request',
        'message' => 'I have a question about my order.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

test('help page can be rendered', function () {
    $response = $this->get(route('help.index'));
    $response->assertStatus(200);
    $response->assertViewIs('help');
});
