<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Register extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $setPasswordUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $setPasswordUrl)
    {
        $this->user = $user;
        $this->setPasswordUrl = $setPasswordUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Register',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'newregister',
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

    public function build()
    {
        return $this->from('support@easypeasly.com')
                    ->subject('Welcome to EasyPeasly - Set Your Password')
                    ->view('newregister')
                    ->with([
                        'user' => $this->user,
                        'setPasswordUrl' => $this->setPasswordUrl,
                    ]);
    }
}
