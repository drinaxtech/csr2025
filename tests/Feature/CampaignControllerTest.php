<?php

use App\Http\Controllers\CampaignController;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

// PestPHP Tests

// File: tests/Feature/CampaignControllerTest.php
test('CampaignController: index method returns correct data', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);
    $campaigns = Campaign::factory()->count(3)->create(['user_id' => $user->id]);

    // Act
    $response = $this->get(route('campaigns.index'));

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(function (AssertableInertia $page) use ($campaigns) {
        $page->component('Campaigns/Index');
        $page->has('campaigns.data', 3);
        $page->where('campaigns.data.0.title', $campaigns[0]->title);
    });
});

// File: tests/Feature/CampaignControllerTest.php
test('CampaignController: index method filters by search term', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);
    Campaign::factory()->create(['user_id' => $user->id, 'title' => 'Campaign A']);
    Campaign::factory()->create(['user_id' => $user->id, 'title' => 'Campaign B']);
    Campaign::factory()->create(['user_id' => $user->id, 'title' => 'Other']);

    // Act
    $response = $this->get(route('campaigns.index', ['search' => 'Campaign']));

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(function (AssertableInertia $page) {
        $page->has('campaigns.data', 2);
    });
});

// File: tests/Feature/CampaignControllerTest.php
test('CampaignController: index method sorts campaigns', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);
    Campaign::factory()->create(['user_id' => $user->id, 'title' => 'Campaign A', 'created_at' => now()->subDay()]);
    Campaign::factory()->create(['user_id' => $user->id, 'title' => 'Campaign B', 'created_at' => now()]);

    // Act
    $response = $this->get(route('campaigns.index', ['sort' => 'title', 'direction' => 'asc']));

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(function (AssertableInertia $page) {
        $page->has('campaigns.data', 2);
        $page->where('campaigns.data.0.title', 'Campaign A');
    });
});

// File: tests/Feature/CampaignControllerTest.php
test('CampaignController: exportCSV method returns a streamed response', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);
    Campaign::factory()->count(2)->create(['user_id' => $user->id]);

    // Act
    $response = $this->get(route('campaigns.export'));

    // Assert
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv');
    $response->assertHeader('Content-Disposition', 'attachment; filename="campaigns.csv"');
    $this->assertStringContainsString('Title,Description,Goal Amount,Created At', $response->streamedContent());
});

// File: tests/Feature/CampaignControllerTest.php
test('CampaignController: myCampaigns method returns campaigns for logged in user', function () {
    // Arrange
    $user = User::factory()->create();
    Auth::login($user);
    Campaign::factory()->create(['created_by' => $user->id, 'title' => 'My Campaign']);
    Campaign::factory()->create(['created_by' => User::factory()->create()->id, 'title' => 'Other Campaign']);

    // Act
    $response = $this->get(route('campaigns.myCampaigns'));

    // Assert
    $response->assertStatus(200);
    $response->assertInertia(function (AssertableInertia $page) {
        $page->component('Campaigns/MyCampaigns');
        $page->has('campaigns.data', 1);
        $page->where('campaigns.data.0.title', 'My Campaign');
    });
});

// File: tests/Feature/DonationControllerTest.php (Example - Adjust as needed)
test('DonationController:  successful donation redirects with message', function () {
    // Arrange
    //  Mock any necessary dependencies, e.g., payment gateway
    $this->post(route('donations.process'), [/* your donation data */])->assertRedirect(route('donations.success'));
    $this->followRedirects()->assertSee('Thank you for your donation!');

});

// File: tests/Feature/UserControllerTest.php
test('UserController: edit method returns correct inertia view', function () {
    $user = User::factory()->create();
    $response = $this->actingAs($user)->get(route('profile.edit'));

    $response->assertStatus(200);
    $response->assertInertia('Profile/Edit');
});