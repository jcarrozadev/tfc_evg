<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationCustom extends Mailable implements ShouldQueue {
    use Queueable, SerializesModels;

    public $datos;

    public function __construct($datos) {
        $this->datos = $datos;
    }

    public function build() {
        return $this->view('emails.notificacion')
                    ->subject('Tu asunto personalizado')
                    ->with([
                        'nombre' => $this->datos['nombre'],
                        'mensaje' => $this->datos['mensaje'],
                    ]);
    }
}

