@extends('layouts.app')
@section('title', 'প্রোফাইল - বইঘর')
@section('content')
<div class="container">
<div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        <div class="form-card mb-3">
            <h2 style="font-size:1.1rem;margin-bottom:20px">প্রোফাইল তথ্য</h2>
            <form method="POST" action="{{ route('user.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid var(--primary)">
                    <div>
                        <label class="btn btn-outline-primary btn-sm" style="cursor:pointer">
                            <i class="fas fa-camera"></i> ছবি পরিবর্তন করুন
                            <input type="file" name="avatar" accept="image/*" style="display:none">
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">নাম</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">ইমেইল</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">মোবাইল নম্বর</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}">
                </div>
                <button type="submit" class="btn btn-primary">প্রোফাইল সেভ করুন</button>
            </form>
        </div>

        <div class="form-card">
            <h2 style="font-size:1.1rem;margin-bottom:20px">পাসওয়ার্ড পরিবর্তন</h2>
            <form method="POST" action="{{ route('user.password.update') }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">বর্তমান পাসওয়ার্ড</label>
                    <input type="password" name="current_password" class="form-control" required>
                    @error('current_password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">নতুন পাসওয়ার্ড</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">নতুন পাসওয়ার্ড নিশ্চিত</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">পাসওয়ার্ড পরিবর্তন করুন</button>
            </form>
        </div>

        {{-- Referral --}}
        <div class="form-card mt-3">
            <h2 style="font-size:1.1rem;margin-bottom:16px"><i class="fas fa-share-alt" style="color:var(--primary)"></i> বন্ধু রেফার করুন</h2>
            <p style="color:var(--text-2);font-size:.9rem;margin-bottom:12px">প্রতিটি রেফারে আপনি পাবেন <strong style="color:var(--primary)">২০ পয়েন্ট</strong>!</p>
            <div style="display:flex;gap:8px">
                <input type="text" class="form-control" value="{{ url('/register?ref=' . $user->referral_code) }}" readonly id="refLink">
                <button class="btn btn-primary js-copy-ref" data-copy="{{ url('/register?ref=' . $user->referral_code) }}">
                    <i class="fas fa-copy"></i> কপি করুন
                </button>
            </div>
            <p style="margin-top:8px;font-size:.85rem;color:var(--text-3)">আপনার কোড: <strong>{{ $user->referral_code }}</strong></p>
        </div>
    </div>
</div>
</div>
@endsection
