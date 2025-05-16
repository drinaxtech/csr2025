<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationConfirmation;

class DonationController extends Controller
{
    /**
     * Show the form for making a donation to a specific campaign.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Inertia\Response
     */
    public function create(Campaign $campaign)
    {
        $campaign->loadSum('donations', 'amount');

        if ($campaign->donations_sum_amount >= $campaign->goal_amount) {
            return redirect()->route('campaigns.show', $campaign->id)->with('error', 'This campaign has already reached its goal. Thank you for your interest!');
        }

        return Inertia::render('Donations/Create', [
            'campaign' => $campaign,
        ]);
    }

    /**
     * Store a newly created donation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Campaign $campaign)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            // Add validation for payment method if implemented
        ]);

        $donation = Donation::create([
            'user_id' => Auth::user()->id,
            'campaign_id' => $campaign->id,
            'amount' => $request->amount,
            'donated_at' => now(),
            // Add payment status and other relevant fields later
        ]);
        //$donation->save();

        // Load the user and campaign to pass to the email
        $user = Auth::user();
        $campaign->load('user'); 


        // Optionally, you might want to trigger an event here for donation confirmation
        // event(new DonationCreated($donation));

         // Send the confirmation email
        Mail::to($user->email)->send(new DonationConfirmation($donation, $campaign, $user));

        return redirect()->route('campaigns.show', $campaign->id)->with('success', 'Thank you for your donation! A confirmation email has been sent to your address.');
    }

    /**
     * Display the donation confirmation.
     *
     * @param  \App\Models\Donation  $donation
     * @return \Inertia\Response
     */
    public function showConfirmation(Donation $donation)
    {
        return Inertia::render('Donations/Confirmation', [
            'donation' => $donation,
        ]);
    }

    public function index(Request $request)
    {
        $donations = Donation::with(['user', 'campaign'])
            ->where('user_id', Auth::user()->id) // Only show donations for the logged-in user
            ->latest()
            ->get();

        return Inertia::render('Donations/DonationHistory', [
            'donations' => $donations,
        ]);
    }
}