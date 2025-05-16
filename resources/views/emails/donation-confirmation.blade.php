@component('mail::message')
# Thank You for Your Donation!

Dear {{ $user->name }},

Thank you for your generous donation of **${{ number_format($donation->amount, 2) }}** to the **{{ $campaign->title }}** campaign.

Your contribution will help us in our efforts to {{ $campaign->description }}.

Here are the details of your donation:

* **Campaign:** {{ $campaign->title }}
* **Amount:** ${{ number_format($donation->amount, 2) }}
* **Date:** {{ $donation->donated_at->format('Y-m-d H:i:s') }}

We truly appreciate your support!

Sincerely,
{{ config('app.name') }}