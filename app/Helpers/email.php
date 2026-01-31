<?php

use App\Models\ClientEmailTemplate;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;

if (! function_exists('getEmailTemplate')) {
    function getEmailTemplate(string $key)
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
}
