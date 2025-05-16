<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

it('only administrators can access the mail settings page', function () {
    $user = User::factory()->create_one(['role' => 'employee']);
    actingAs($user)
        ->get(route('admin.mail.settings.index'))
        ->assertForbidden();

    $admin = User::factory()->create_one(['role' => 'admin']);
    actingAs($admin)
        ->get(route('admin.mail.settings.index'))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page->component('Admin/MailSettings'));
});

it('administrators can update mail settings', function () {
    $admin = User::factory()->create_one(['role' => 'admin']);
    actingAs($admin)
        ->post(route('admin.mail.settings.update'), [
            'driver' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'test@example.com',
            'password' => 'secret',
            'encryption' => 'tls',
            'from_address' => 'admin@example.com',
            'from_name' => 'Admin',
        ])
        ->assertRedirect()
        ->assertSessionHas('success');

    // You might want to assert that the database (if you switched to it) or .env file was updated
    // depending on your implementation.
});

it('administrators can test mail connection', function () {
    $admin = User::factory()->create_one(['role' => 'admin']);
    actingAs($admin)
        ->post(route('admin.mail.test'), [
            'driver' => 'smtp',
            'host' => 'smtp.example.com',
            'port' => 587,
            'username' => 'test@example.com',
            'password' => 'secret',
            'encryption' => 'tls',
            'from_address' => 'admin@example.com',
        ])
        ->assertOk()
        ->assertJson(['success' => 'Mail connection successful!']); // Adjust based on your actual response
});

it('validation works for updating mail settings', function () {
    $admin = User::factory()->create_one(['role' => 'admin']);
    actingAs($admin)
        ->post(route('admin.mail.settings.update'), [
            'driver' => '',
            'host' => '',
            'port' => 'invalid',
            'from_address' => 'invalid-email',
        ])
        ->assertSessionHasErrors(['driver', 'host', 'port', 'from_address']);
});