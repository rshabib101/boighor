<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Referral;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) return redirect()->route('home');
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';
        $credentials = [$field => $request->login, 'password' => $request->password];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = auth()->user();
            if ($user->isBanned()) {
                Auth::logout();
                return back()->with('error', 'আপনার অ্যাকাউন্ট নিষ্ক্রিয় করা হয়েছে।');
            }

            $user->update(['last_login_at' => now()]);
            RewardService::giveDailyLoginBonus($user);

            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['login' => 'লগইন তথ্য সঠিক নয়।'])->withInput();
    }

    public function showRegister()
    {
        if (auth()->check()) return redirect()->route('home');
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|min:2|max:100',
            'email'    => 'nullable|email|unique:users,email',
            'mobile'   => 'nullable|string|min:11|max:15|unique:users,mobile',
            'password' => 'required|string|min:6|confirmed',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ], [
            'name.required'    => 'নাম দিতে হবে।',
            'email.unique'     => 'এই ইমেইল ইতোমধ্যে ব্যবহৃত।',
            'mobile.unique'    => 'এই মোবাইল নম্বর ইতোমধ্যে ব্যবহৃত।',
            'password.min'     => 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষর হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড মিলছে না।',
        ]);

        if (!$request->email && !$request->mobile) {
            return back()->withErrors(['mobile' => 'ইমেইল অথবা মোবাইল নম্বর দিতে হবে।'])->withInput();
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'mobile'        => $request->mobile,
            'password'      => Hash::make($request->password),
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by'   => $request->referral_code,
            'last_login_at' => now(),
        ]);

        // Signup bonus
        RewardService::giveSignupBonus($user);

        // Referral bonus
        if ($request->filled('referral_code')) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
            if ($referrer) {
                RewardService::giveReferralBonus($referrer, $user);
                Referral::create(['referrer_id' => $referrer->id, 'referred_id' => $user->id, 'points_earned' => RewardService::getPointValues()['referral']]);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home')->with('success', 'স্বাগতম! আপনার অ্যাকাউন্ট তৈরি হয়েছে এবং ' . RewardService::getPointValues()['signup'] . ' পয়েন্ট বোনাস পেয়েছেন।');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
