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
        $totalDonations = Donation::sum('amount');
        $totalUsers = User::count();
        $recentDonations = Donation::latest()->take(5)->get();

        return Inertia::render('Admin/Dashboard', [
            'totalCampaigns' => $totalCampaigns,
            'totalDonations' => $totalDonations,
            'totalUsers' => $totalUsers,
            'recentDonations' => $recentDonations,
        ]);
    }
}