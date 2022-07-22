<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewSubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber;
    public $notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subscriber, $notifiable)
    {
        $this->subscriber = $subscriber;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail.creatorPaidSubscriber'))
            ->markdown('emails.creatorPaidSubscriber');
    }
}
