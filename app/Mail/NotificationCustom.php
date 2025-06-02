<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * NotificationCustom
 * Mailable class for sending custom notifications.
 */
class NotificationCustom extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $data;

    /**
     * NotificationCustom constructor.
     * Initializes the mailable with the provided data.
     *
     * @param array $data
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return NotificationCustom
     */
    public function build(): NotificationCustom {
        return $this->view('emails.notificationGuard')
                    ->subject('Gestion de guardia')
                    ->with([
                        'name' => $this->data['name'],
                        'body' => $this->data['body'],
                    ]);
    }
}

