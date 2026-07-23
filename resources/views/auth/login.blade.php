@extends('layouts.app')
@section('title', 'লগইন - বইঘর')
@section('content')
<div class="auth-page" style="padding-top:0;background:linear-gradient(135deg,#1e1b4b,#312e81)">
    <div class="auth-card">
        <div class="auth-logo">
            <a href="{{ route('home') }}" class="logo" style="justify-content:center">
                <span class="logo-icon"><i class="fas fa-book-open"></i></span>
                <span class="logo-text">বই<span class="accent">ঘর</span></span>
            </a>
        </div>
        <h1 class="auth-title">আবার স্বাগতম!</h1>
        <p class="auth-subtitle">আপনার অ্যাকাউন্টে লগইন করুন</p>

        @if($errors->any())
        <div style="background:rgba(239,68,68,.1);border:1px solid var(--danger);border-radius:var(--radius-sm);padding:12px;margin-bottom:16px;color:var(--danger);font-size:.9rem">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">মোবাইল নম্বর বা ইমেইল</label>
                <input type="text" name="login" class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}"
                       placeholder="01XXXXXXXXX বা email@example.com"
                       value="{{ old('login') }}" required autofocus>
                @error('login')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">পাসওয়ার্ড</label>
                <div style="position:relative">
                    <input type="password" name="password" id="passwordField" class="form-control" placeholder="পাসওয়ার্ড লিখুন" required>
                    <button type="button" onclick="togglePass()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);color:var(--text-3)">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <label class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <span style="font-size:.88rem">মনে রাখুন</span>
                </label>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-sign-in-alt"></i> লগইন করুন
            </button>
        </form>
        <div class="auth-footer">
            অ্যাকাউন্ট নেই? <a href="{{ route('register') }}">রেজিস্টার করুন</a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function togglePass(){
    const f = document.getElementById('passwordField');
    const i = document.getElementById('eyeIcon');
    f.type = f.type==='password'?'text':'password';
    i.className = f.type==='password'?'fas fa-eye':'fas fa-eye-slash';
}
</script>
@endpush
