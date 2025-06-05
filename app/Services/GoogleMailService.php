<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleMailService
{
    protected string $endpoint = 'https://script.google.com/macros/s/AKfycbxcUokMkKM6vgwedxT4q0wd-i9ki_aWqc_S2ftGbsFdwrudwHAAWlxnodaR7eu-M4axDg/exec';

    public function send(string $to, string $subject, string $body, string $html = ''): bool
    {
        $response = Http::post($this->endpoint, [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'html' => $html,
        ]);

        return $response->successful();
    }
}
