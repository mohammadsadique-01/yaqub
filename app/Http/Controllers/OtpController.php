<?php

namespace App\Http\Controllers;

use App\Http\Requests\OtpRequest;
use App\Http\Requests\ResendOtpRequest;
use App\Models\TemporaryOtp;
use App\Models\User;
use App\Services\OtpService;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OtpController extends Controller
{
    public function otp(): View
    {
        return view('backend.otp');
    }

    public function resendOtp(ResendOtpRequest $request, OtpService $otpService)
    {
        try {
            $otpService->resend($request->user_id, $request->title);

            return back()->with('success', 'OTP has been resent.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function submitOtp(OtpRequest $request)
    {
        try {
            $userId = Crypt::decryptString($request->userId);
            $title = session('title');

            $otpRecord = $this->getLatestOtp($userId);

            if (! $this->isValidOtp($otpRecord, $request->otp)) {
                $this->logFailedOtp();

                return back()->withErrors([
                    'otp' => 'Invalid or expired OTP.',
                ]);
            }

            $this->clearOtp($otpRecord);

            if ($title === 'login') {
                return $this->loginUser($userId);
            }

            if ($title === 'forgotpassword') {
                return $this->resetPasswordFlow($userId);
            }

            return back()->withErrors(['error' => 'Invalid request.']);
        } catch (\Exception $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function getLatestOtp(int $userId)
    {
        return TemporaryOtp::where('user_id', $userId)
            ->latest()
            ->first();
    }

    private function isValidOtp($otpRecord, string $otp): bool
    {
        if (! $otpRecord) {
            return false;
        }

        // ❌ Too many attempts
        if ($otpRecord->attempts >= 5) {
            $otpRecord->delete();
            throw new Exception('Too many attempts. Please login again.');
        }

        // Expiry: 10 minutes
        if (Carbon::parse($otpRecord->created_at)->addMinutes(10)->isPast()) {
            $otpRecord->delete();

            return false;
        }

        // ❌ Wrong OTP → increment attempts
        if (! Hash::check($otp, $otpRecord->otp)) {
            $otpRecord->increment('attempts');

            return false;
        }

        return true;
    }

    private function logFailedOtp(): void
    {
        activityLog([
            'title' => 'OTP Failed',
            'message' => 'Invalid or expired OTP entered.',
            'status' => 3,
        ]);
    }

    private function clearOtp($otpRecord): void
    {
        $otpRecord->delete();
        session()->forget('title');
    }

    private function loginUser(int $userId)
    {
        Auth::loginUsingId($userId);

        activityLog([
            'user_id' => $userId,
            'table_id' => $userId,
            'table_name' => 'User',
            'title' => 'OTP Login Success',
            'message' => 'User logged in successfully via OTP.',
            'status' => 1,
        ]);

        return redirect()->route('dashboard');
    }

    private function resetPasswordFlow(int $userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 1;
        $user->save();

        activityLog([
            'user_id' => $userId,
            'table_id' => $userId,
            'table_name' => 'User',
            'title' => 'OTP Password Reset Success',
            'message' => 'OTP verified for password reset.',
            'status' => 1,
        ]);

        return redirect()->route('login')
            ->with('success', 'OTP verified. You can now reset your password.');
    }
}
