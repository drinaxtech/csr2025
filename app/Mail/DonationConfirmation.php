<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\User;

class DonationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $donation;
    public $campaign;
    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(Donation $donation, Campaign $campaign, User $user)
    {
        $this->donation = $donation;
        $this->campaign = $campaign;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Donation to ' . $this->campaign->title . ' - Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.donation-confirmation',
            with: [
                'donation' => $this->donation,
                'campaign' => $this->campaign,
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}