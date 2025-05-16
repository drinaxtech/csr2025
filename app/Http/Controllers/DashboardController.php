<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function __construct()
    {
        if (Auth::check() && Auth::user()->role == 'admin') {
            abort(404, 'Not found');
        }
    }
    /**
     * Display the admin dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $user = Auth::user();

        $activeCampaignsCount = Campaign::where('created_by', $user->id)->where('status', 'active')->count();

        $reachedGoalCampaignsCount = Campaign::where('created_by', $user->id)
            ->withSum([
                'donations' => function ($query) {
                    $query->whereHas('transactions', function ($query) {
                        $query->where('status', 'success');
                    });
                }
            ], 'amount')
            ->having('donations_sum_amount', '>=', \Illuminate\Support\Facades\DB::raw('goal_amount'))
            ->count();

       


        return Inertia::render('Dashboard', [
            'activeCampaignsCount' => $activeCampaignsCount,
            'reachedGoalCampaignsCount' => $reachedGoalCampaignsCount,
            // You can also pass other relevant dashboard data here
        ]);
    }
}