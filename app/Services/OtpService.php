<?php

namespace App\Services;

use App\Models\TemporaryOtp;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function create(User $user, string $type, string $sessionId): int
    {
        $otp = random_int(100000, 999999);

        TemporaryOtp::create([
            'user_id' => $user->id,
            'otp' => Hash::make($otp),
            'session_id' => $sessionId,
        ]);

        if (! empty($user->email)) {
            $user->notify(new OtpNotification(
                $otp,
                $type,
                $user->name,
                $user->email
            ));
        }

        return $otp;
    }

    public function resend(int $userId, string $type): void
    {
        $otpRecord = TemporaryOtp::where('user_id', $userId)->latest()->first();

        if (! $otpRecord) {
            throw new \Exception('OTP expired. Please login again.');
        }

        // ⏱ Rate limit (30 seconds)
        if ($otpRecord->updated_at->diffInSeconds(now()) < 30) {
            throw new \Exception('Please wait before requesting a new OTP.');
        }

        if ($otpRecord->session_id !== session()->getId()) {
            throw new \Exception('Invalid OTP session.');
        }

        $user = User::findOrFail($userId);

        DB::transaction(function () use ($otpRecord, $user, $type) {

            $otp = random_int(100000, 999999);

            $otpRecord->update([
                'otp' => Hash::make($otp),
            ]);

            $user->notify(
                new OtpNotification(
                    $otp,
                    $type,
                    $user->name,
                    $user->email
                )
            );
        });

        activityLog([
            'table_id' => $userId,
            'table_name' => 'User',
            'title' => 'Resend OTP',
            'message' => 'OTP resent successfully.',
            'status' => 1,
        ]);
    }
}
