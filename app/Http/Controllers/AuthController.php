<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Models\FinancialYear;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('backend.login');
    }

    public function submitLogin(LoginRequest $request, OtpService $otpService)
    {
        $user = User::where(fn ($q) => $q->where('email', $request->email_or_mobile)
            ->orWhere('mobile', $request->email_or_mobile)
        )->first();

        if (! $this->isValidUser($user, $request->password)) {
            $this->logFailedLogin();

            return back()->withErrors([
                'error' => 'Invalid credentials or inactive user.',
            ])->withInput();
        }

        $otpService->create(
            $user,
            'login',
            $request->session()->getId()
        );

        activityLog([
            'user_id' => $user->id,
            'table_id' => $user->id,
            'table_name' => 'User',
            'title' => 'Login: Success',
            'message' => 'OTP sent successfully.',
            'status' => 1,
        ]);

        session(['title' => 'login']);

        return redirect()->route('otp', [
            'userId' => Crypt::encryptString($user->id),
        ]);
    }

    private function isValidUser(?User $user, string $password): bool
    {
        return $user
            && $user->status === 1
            && Hash::check($password, $user->password);
    }

    private function logFailedLogin(): void
    {
        activityLog([
            'title' => 'Login Failed',
            'message' => 'Invalid credentials or inactive user.',
            'status' => 3,
        ]);
    }

    public function forgotPassword(): View
    {
        return view('backend.forgot-password');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            activityLog([
                'table_id' => Auth::id(),
                'table_name' => 'User',
                'title' => 'Logout: Success',
                'message' => 'User logged out successfully.',
                'status' => 1,
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function submitForgotPassword(
        ForgotPasswordRequest $request,
        OtpService $otpService
    ) {
        try {

            $user = User::where('email', $request->email_or_mobile)
                ->orWhere('mobile', $request->email_or_mobile)
                ->first();

            if (! $user) {
                return back()->withErrors([
                    'email_or_mobile' => 'User not found.',
                ]);
            }

            // 🔐 Temporarily update password (locked until OTP)
            $user->update([
                'password' => Hash::make($request->password),
                'status' => 0,
            ]);

            // 🔑 Send OTP
            $otpService->create(
                $user,
                'forgotpassword',
                $request->session()->getId()
            );

            session(['title' => 'forgotpassword']);

            activityLog([
                'user_id' => $user->id,
                'table_id' => $user->id,
                'table_name' => 'User',
                'title' => 'Forgot Password: OTP Sent',
                'message' => 'OTP sent for password reset.',
                'status' => 1,
            ]);

            return redirect()->route('otp', [
                'userId' => Crypt::encryptString($user->id),
            ]);

        } catch (\Exception $e) {

            activityLog([
                'title' => 'Forgot Password Error',
                'message' => $e->getMessage(),
                'status' => 2,
            ]);

            return back()->withErrors([
                'error' => 'Something went wrong. Please try again.',
            ]);
        }
    }

    public function financialyearswitch(int $id): RedirectResponse
    {
        $fy = FinancialYear::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        session([
            'financial_year_id' => $fy->id,
            'financial_year_name' => $fy->name,
        ]);

        return redirect()->back();
    }
}
