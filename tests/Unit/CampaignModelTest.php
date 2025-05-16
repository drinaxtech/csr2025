<?php

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can determine if a campaign has reached its goal with exact amount', function () {
    $campaign = Campaign::factory()->create(['goal_amount' => 100]);
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 100]);
    expect($campaign->reached_goal)->toBeTrue();
});

it('can determine if a campaign has reached its goal with more than the goal amount', function () {
    $campaign = Campaign::factory()->create(['goal_amount' => 100]);
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 120]);
    expect($campaign->reached_goal)->toBeTrue();
});

it('can determine if a campaign has not reached its goal', function () {
    $campaign = Campaign::factory()->create(['goal_amount' => 100]);
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 50]);
    expect($campaign->reached_goal)->toBeFalse();
});

it('can calculate the total donations for a campaign with multiple donations', function () {
    $campaign = Campaign::factory()->create();
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 30]);
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 70]);
    Donation::factory()->create(['campaign_id' => $campaign->id, 'amount' => 25]);
    expect($campaign->donation_total)->toBe(125.0);
});

it('can calculate the total donations as zero if there are no donations', function () {
    $campaign = Campaign::factory()->create();
    expect($campaign->donation_total)->toBe(0.0);
});

it('has a donations relationship', function () {
    $campaign = Campaign::factory()->create();
    Donation::factory()->count(3)->create(['campaign_id' => $campaign->id]);
    expect($campaign->donations)->toHaveCount(3);
    expect($campaign->donations->first())->toBeInstanceOf(Donation::class);
});