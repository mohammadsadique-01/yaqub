<?php

use App\Models\ClientEmailTemplate;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

function getEmailTemplate(string $key): EmailTemplate|ClientEmailTemplate|null
{
    $emailTemplate = EmailTemplate::where('title', $key)->first();

    if (! $emailTemplate) {
        return null;
    }

    $userId = Auth::id();

    if (! $userId) {
        return $emailTemplate;
    }

    return ClientEmailTemplate::where('client_user_id', $userId)
        ->where('email_templates_id', $emailTemplate->id)
        ->first()
        ?? $emailTemplate;
}

function MailSender($parameter)
{
    Mail::send($parameter['template_name'], $parameter, function ($message) use ($parameter) {
        $message
            ->to($parameter['email'], $parameter['name'])
            ->subject($parameter['subject']);
    });
}
