<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationCustom extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function build(): NotificationCustom {
        return $this->view('emails.notificationGuard')
                    ->subject('Gestion de guardia')
                    ->with([
                        'name' => $this->data['name'],
                        'body' => $this->data['body'],
                    ]);
    }
}

