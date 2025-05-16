<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use \Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\DonationConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{

    public function __construct()
    {
        // You can also use $this->middleware() here.
        if (Auth::check() && Auth::user()->role == 'admin') {
            abort(404, 'Not found');
        }
    }
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

        $amount = $request->amount;
        $currency = 'USD'; // Could be made dynamic
        $paymentGateway = $request->payment_gateway;
        $paymentData = $request->payment_data;

        // Generate unique transaction ID
        $transactionId = Str::uuid()->toString();

        DB::beginTransaction();

            $user = Auth::user();
            // Create Donation Record
            $donation = Donation::create([
                'user_id' => $user->id,
                'campaign_id' => $campaign->id,
                'amount' => $amount,
                'donated_at' => now(),
            ]);

            // Determine Payment Gateway and Process Payment
            // Make sure PaymentServiceFactory exists in App\Services namespace
            $paymentService = app()->make("App\\Services\\Payment\\PaymentServiceFactory")::make($paymentGateway);

            $paymentResponse = $paymentService->processPayment([
                'amount' => $amount * 100,  // Stripe/PayPal expects amount in cents
                'currency' => $currency,
                'payment_data' => $paymentData,
                'description' => "Donation to {$campaign->title}",
            ]);

            // Determine transaction status
            $transactionStatus = isset($paymentResponse['status']) ? $paymentResponse['status'] : 'failed';

            // Save Transaction Record
            $transaction = Transaction::create([
                'donation_id' => $donation->id,
                'payment_gateway' => 'cash',
                'transaction_id' => $transactionId,
                'status' => 'success',
                'amount' => $amount,
                'currency' => $currency,
                'response_data' => $paymentResponse,
                'refund_id' => null,
                'refund_status' => null,
            ]);

            DB::commit();

            // Send Email Confirmation
            Mail::to($user->email)->send(new DonationConfirmation($donation, $campaign, $user));

            return redirect()->route('donations.success', [$campaign->id, $donation->id, $transaction->id]);


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

    public function index()
    {
        $donations = Donation::with(['user', 'campaign'])
            ->where('user_id', Auth::user()->id) // Only show donations for the logged-in user
            ->latest()
            ->get();

        return Inertia::render('Donations/DonationHistory', [
            'donations' => $donations,
        ]);
    }

    public function donationSuccess(Campaign $campaign, Donation $donation, Transaction $transaction)
    {
    
        return Inertia::render('Donations/DonationSuccess', [
            'campaign' => $campaign,
            'donation' => $donation,
            'transaction' => $transaction
        ]);
    }

}