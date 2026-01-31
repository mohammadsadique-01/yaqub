<?php

use Illuminate\Support\Facades\Mail;

if (! function_exists('mailSender')) {
    function mailSender(array $data): void
    {
        Mail::send($data['template_name'], $data, function ($message) use ($data) {
            $message
                ->to($data['email'], $data['name'] ?? null)
                ->subject($data['subject']);
        });
    }
}
