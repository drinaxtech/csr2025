<?php

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class)->group('dashboard');

it('shows the dashboard page with campaign statistics', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create some campaigns associated with the user
    Campaign::factory()->count(3)->create(['created_by' => $user->id, 'ends_at' => now()->addDays(7)]);
    $reachedGoalCampaign = Campaign::factory()->create(['created_by' => $user->id, 'goal_amount' => 100, 'ends_at' => now()->addDays(7)]);
    Donation::factory()->count(5)->create(['campaign_id' => $reachedGoalCampaign->id, 'amount' => 25]); // Total $125

    $response = $this->get('/dashboard');

    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Dashboard')
                ->has('activeCampaignsCount', 4) // 3 + 1
                ->has('reachedGoalCampaignsCount', 1)
        );
});

it('shows zero statistics if the user has no active campaigns', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response->assertStatus(200)
        ->assertInertia(fn (AssertableInertia $page) =>
            $page->component('Dashboard')
                ->has('activeCampaignsCount', 0)
                ->has('reachedGoalCampaignsCount', 0)
        );
});