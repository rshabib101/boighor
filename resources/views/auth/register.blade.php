@extends('layouts.app')
@section('title', 'রেজিস্টার - বইঘর')
@section('content')
<div class="auth-page" style="padding-top:0;background:linear-gradient(135deg,#1e1b4b,#312e81)">
    <div class="auth-card" style="max-width:500px">
        <div class="auth-logo">
            <a href="{{ route('home') }}" class="logo" style="justify-content:center">
                <span class="logo-icon"><i class="fas fa-book-open"></i></span>
                <span class="logo-text">বই<span class="accent">ঘর</span></span>
            </a>
        </div>
        <h1 class="auth-title">নতুন অ্যাকাউন্ট তৈরি করুন</h1>
        <p class="auth-subtitle">রেজিস্টার করুন এবং <strong style="color:var(--primary)">৫০ পয়েন্ট</strong> বোনাস পান!</p>

        @if($errors->any())
        <div style="background:rgba(239,68,68,.1);border:1px solid var(--danger);border-radius:var(--radius-sm);padding:12px;margin-bottom:16px;color:var(--danger);font-size:.9rem">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">পূর্ণ নাম <span style="color:var(--danger)">*</span></label>
                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       placeholder="আপনার পূর্ণ নাম লিখুন" value="{{ old('name') }}" required>
                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">মোবাইল নম্বর</label>
                <input type="text" name="mobile" class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                       placeholder="01XXXXXXXXX" value="{{ old('mobile') }}">
                @error('mobile')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">ইমেইল</label>
                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       placeholder="email@example.com" value="{{ old('email') }}">
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="কমপক্ষে ৬ অক্ষর" required>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড নিশ্চিত করুন <span style="color:var(--danger)">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="পাসওয়ার্ড আবার লিখুন" required>
            </div>
            <div class="form-group">
                <label class="form-label">রেফারেল কোড (ঐচ্ছিক)</label>
                <input type="text" name="referral_code" class="form-control" placeholder="বন্ধুর রেফারেল কোড দিন" value="{{ old('referral_code', request('ref')) }}">
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" required>
                <span style="font-size:.85rem;color:var(--text-2)">আমি <a href="#" style="color:var(--primary)">শর্তাবলী</a> মেনে নিচ্ছি</span>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-user-plus"></i> রেজিস্টার করুন
            </button>

            <div style="background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);padding:10px 14px;margin-top:14px;display:flex;gap:8px;align-items:center">
                <i class="fas fa-gift" style="color:var(--success)"></i>
                <span style="font-size:.85rem;color:var(--success)">রেজিস্ট্রেশনে পাবেন <strong>৫০ পয়েন্ট</strong> বোনাস!</span>
            </div>
        </form>
        <div class="auth-footer">
            ইতোমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('login') }}">লগইন করুন</a>
        </div>
    </div>
</div>
@endsection
