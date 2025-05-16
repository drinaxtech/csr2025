<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user(); // Assuming you want statistics related to the logged-in user's campaigns

        $activeCampaignsCount = Campaign::where('created_by', $user->id) // <--- This line caused the error

            ->count();

        $reachedGoalCampaignsCount = Campaign::where('created_by', $user->id) // <--- This line caused the error
   
            ->where('goal_amount', '<=', function ($query) {
                $query->selectRaw('SUM(amount)')
                    ->from('donations')
                    ->whereColumn('campaign_id', 'campaigns.id');
            })
            ->count();

        // Another way to get reached goal campaigns using the model attribute:
        $reachedGoalCampaignsCountEloquent = Campaign::where('created_by', $user->id) // <--- This line caused the error
      
            ->get()
            ->filter(function ($campaign) {
                return $campaign->reached_goal;
            })
            ->count();


        return Inertia::render('Dashboard', [
            'activeCampaignsCount' => $activeCampaignsCount,
            'reachedGoalCampaignsCount' => $reachedGoalCampaignsCountEloquent,
            // You can also pass other relevant dashboard data here
        ]);
    }
}