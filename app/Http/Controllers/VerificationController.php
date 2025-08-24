<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show(Request $request)
    {
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('home'))
                    : view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('verified', true);
        }

        if ($user->verification_token !== $request->route('hash')) {
            return redirect()->route('verification.notice')->with('error', 'Invalid verification link.');
        }

        if ($user->verification_token_expires_at && Carbon::parse($user->verification_token_expires_at)->isPast()) {
            return redirect()->route('verification.notice')->with('error', 'Verification link has expired.');
        }

        $user->markEmailAsVerified();

        return redirect()->route('home')->with('verified', true);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('home'));
        }

        // Check if user can resend (rate limiting)
        if ($user->verification_token_expires_at && 
            Carbon::parse($user->verification_token_expires_at)->diffInHours(Carbon::now()) < 1) {
            return back()->with('error', 'Please wait before requesting another verification email.');
        }

        // Generate new verification token
        $user->verification_token = Str::random(64);
        $user->verification_token_expires_at = Carbon::now()->addHours(24);
        $user->save();

        // Send verification email
        Mail::to($user->email)->send(new EmailVerificationMail($user));

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Show phone verification form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showPhoneVerification(Request $request)
    {
        if ($request->user()->phone_verified_at) {
            return redirect()->route('home');
        }

        return view('auth.verify-phone');
    }

    /**
     * Verify phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if ($user->phone_verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        if ($user->phone_verification_expires_at && Carbon::parse($user->phone_verification_expires_at)->isPast()) {
            return back()->withErrors(['code' => 'Verification code has expired.']);
        }

        $user->phone_verified_at = now();
        $user->phone_verification_code = null;
        $user->phone_verification_expires_at = null;
        $user->save();

        return redirect()->route('home')->with('success', 'Phone number verified successfully.');
    }

    /**
     * Resend phone verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendPhoneVerification(Request $request)
    {
        $user = $request->user();

        if ($user->phone_verified_at) {
            return redirect()->route('home');
        }

        // Check rate limiting
        if ($user->phone_verification_expires_at && 
            Carbon::parse($user->phone_verification_expires_at)->diffInMinutes(Carbon::now()) < 2) {
            return back()->with('error', 'Please wait before requesting another verification code.');
        }

        // Generate new verification code
        $user->phone_verification_code = rand(100000, 999999);
        $user->phone_verification_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // In production, you would integrate with an SMS service here
        // For now, we'll just show the code in the response
        return back()->with('success', 'Verification code resent: ' . $user->phone_verification_code);
    }
}