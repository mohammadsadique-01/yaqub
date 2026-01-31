<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

if (! function_exists('activityLog')) {
    function activityLog(array $data)
    {
        $userId = null;

        if (Auth::check()) {
            $userId = Auth::id();
        } elseif (! empty($data['user_id'])) {
            $userId = $data['user_id'];
        }

        $log = new Log;
        $log->user_id = $userId;
        $log->table_id = $data['table_id'] ?? null;
        $log->table_name = $data['table_name'] ?? null;
        $log->title = $data['title'] ?? null;
        $log->message = $data['message'] ?? null;
        $log->status = $data['status'] ?? 0;
        $log->save();
    }
}
