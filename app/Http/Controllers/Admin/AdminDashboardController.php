<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $totalCampaigns = Campaign::count();
        // Calculate total donations with successful transactions using the relationship
    $totalDonations = Donation::whereHas('transactions', function ($query) {
        $query->where('status', 'success');
    })->sum('amount');

    $totalUsers = User::where('role', 'employee')->count();

    // Get recent donations with successful transactions using the relationship
    $recentDonations = Donation::whereHas('transactions', function ($query) {
        $query->where('status', 'success');
    })
        ->with(['user', 'campaign', 'transactions']) // Eager load transactions as well
        ->latest()
        ->take(5)
        ->get();

        return Inertia::render('Admin/Dashboard', [
            'totalCampaigns' => $totalCampaigns,
            'totalDonations' => $totalDonations,
            'totalUsers' => $totalUsers,
            'recentDonations' => $recentDonations,
        ]);
    }
}