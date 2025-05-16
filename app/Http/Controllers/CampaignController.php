<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;
use \Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Display a listing of the campaigns.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $page = $request->input('page', 1); // Get the current page number
        

        $campaigns = Campaign::query()
            ->withSum([
                'donations' => function ($query) {
                    $query->whereHas('transactions', function ($query) {
                        $query->where('status', 'success'); // Correct Enum Usage
                    });
                }
            ], 'amount')
            ->where('status', 'active') 
            ->where(function ($query) {
                $query->where('starts_at', '<=', now())
                      ->orWhere('ends_at', '>=', now());
            })
            ->whereNot('created_by', Auth::id())
            ->when($search, function ($query, $search) {
                if(empty($search)) {
                    return;
                }
                $query->where('title', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->paginate(10, ['*'], 'page', $page); // Paginate with the current page

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $campaigns->items(),
                'next_page_url' => $campaigns->nextPageUrl(),
            ]);
        }

        return Inertia::render('Campaigns/Index', [
            'campaigns' => $campaigns,
            'search' => $search
        ]);
    }

    /**
     * Display a listing of the logged-in user's campaigns.
     *
     * @return \Inertia\Response
     */
    public function myCampaigns()
    {
        $userCampaigns = Campaign::where('created_by', Auth::id())->where('status', 'active')->latest()->get();
        return Inertia::render('Campaigns/MyCampaigns', [
            'campaigns' => $userCampaigns,
        ]);
    }

    /**
     * Show the form for creating a new campaign.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Campaigns/Create');
    }

    /**
     * Store a newly created campaign in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        Campaign::create([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'created_by' => Auth::user()->id, // Assuming authenticated user creates the campaign
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified campaign.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Inertia\Response
     */
    public function show(Campaign $campaign)
    {
        //$campaign->loadSum('donations', 'amount');
        $campaign->loadSum(['donations' => function ($query) {
            $query->whereHas('transactions', function ($query) {
                $query->where('status', 'success'); // Use the enum value
            });
        }], 'amount');
        return Inertia::render('Campaigns/Show', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Display a list of donations for a specific campaign.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Inertia\Response
     */
    public function showDonations(Campaign $campaign)
    {
        if ($campaign->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $donations = $campaign->donations()->latest()->with('user')->get();

        return Inertia::render('Campaigns/DonationsList', [
            'campaign' => $campaign,
            'donations' => $donations,
        ]);
    }

    /**
     * Show the form for editing the specified campaign.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Inertia\Response
     */
    public function edit(Campaign $campaign)
    {
        if ($campaign->created_by !== Auth::id()) {
            abort(404, 'Not found');
        }

        return Inertia::render('Campaigns/Edit', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Update the specified campaign in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Campaign $campaign)
    {
        if ($campaign->created_by !== Auth::id()) {
            abort(404, 'Not found');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'goal_amount' => 'required|numeric|min:0',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $campaign->update([
            'title' => $request->title,
            'description' => $request->description,
            'goal_amount' => $request->goal_amount,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
        ]);

        return redirect()->route('campaigns.index')->with('success', 'Campaign updated successfully.');
    }

    /**
     * Remove the specified campaign from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function close(Campaign $campaign)
    {
        if ($campaign->created_by !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    
        $campaign->status = 'closed'; // Or use an enum if you have one
        
        if($campaign->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Deleted successfully',
            ]);
        }

    }

}