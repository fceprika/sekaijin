<?php

namespace App\Mail;

use App\Models\User;
use App\Services\CommunityStatsService;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue dans la communauté Sekaijin ! 🌍',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Get cached community statistics
        return new Content(
            view: 'emails.welcome',
            with: [
                'user' => $this->user,
                'totalMembers' => CommunityStatsService::getTotalMembers(),
                'countriesCovered' => CommunityStatsService::getCountriesCovered(),
                'totalContent' => CommunityStatsService::getTotalContent(),
            ]
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
