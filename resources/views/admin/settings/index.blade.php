@extends('layouts.admin')
@section('title', 'সাইট সেটিং')
@section('content')
<div class="row">
<div class="col-md-9">
<div class="card">
    <div class="card-header"><h3 class="card-title"><i class="fas fa-cog mr-2"></i>সাইট সেটিং</h3></div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3" id="settingsTab" role="tablist">
            <li class="nav-item"><a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab">সাধারণ সেটিং</a></li>
            <li class="nav-item"><a class="nav-link" id="points-tab" data-toggle="tab" href="#points" role="tab">পয়েন্ট সেটিং</a></li>
            <li class="nav-item"><a class="nav-link" id="social-tab" data-toggle="tab" href="#social" role="tab">সোশ্যাল লিংক</a></li>
            <li class="nav-item"><a class="nav-link" id="notify-tab" data-toggle="tab" href="#notify" role="tab">ইউজার নোটিফিকেশন</a></li>
        </ul>

        <div class="tab-content" id="settingsTabContent">
            {{-- Tab 1: General --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf @method('PUT')
                    <div class="form-group"><label>সাইটের নাম</label><input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? 'বইঘর' }}"></div>
                    <div class="form-group"><label>সাইটের ট্যাগলাইন</label><input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}"></div>
                    <div class="form-group"><label>যোগাযোগের ইমেইল</label><input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}"></div>
                    <div class="form-group"><label>যোগাযোগের ফোন</label><input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}"></div>
                    <div class="form-group"><label>Google AdSense কোড</label><textarea name="google_adsense_code" class="form-control" rows="3">{{ $settings['google_adsense_code'] ?? '' }}</textarea></div>
                    <div class="form-group"><label>Footer লেখা</label><input type="text" name="footer_text" class="form-control" value="{{ $settings['footer_text'] ?? '' }}"></div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> সেভ করুন</button>
                </form>
            </div>

            {{-- Tab 2: Points --}}
            <div class="tab-pane fade" id="points" role="tabpanel">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf @method('PUT')
                    <div class="row">
                        <div class="col-md-6"><div class="form-group"><label>সাইনআপ পয়েন্ট</label><input type="number" name="points_signup" class="form-control" value="{{ $settings['points_signup'] ?? 50 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>দৈনিক লগইন পয়েন্ট</label><input type="number" name="points_daily_login" class="form-control" value="{{ $settings['points_daily_login'] ?? 10 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>বিজ্ঞাপন দেখা পয়েন্ট</label><input type="number" name="points_ad_watch" class="form-control" value="{{ $settings['points_ad_watch'] ?? 5 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>ডাউনলোড পয়েন্ট</label><input type="number" name="points_book_download" class="form-control" value="{{ $settings['points_book_download'] ?? 2 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>রেফারেল পয়েন্ট</label><input type="number" name="points_referral" class="form-control" value="{{ $settings['points_referral'] ?? 20 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>কুইজ পয়েন্ট</label><input type="number" name="points_quiz" class="form-control" value="{{ $settings['points_quiz'] ?? 15 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>Points to BDT Rate (১ পয়েন্ট = ? টাকা)</label><input type="number" step="0.01" name="points_to_bdt_rate" class="form-control" value="{{ $settings['points_to_bdt_rate'] ?? 0.1 }}"></div></div>
                        <div class="col-md-6"><div class="form-group"><label>ন্যূনতম উইড্র পয়েন্ট</label><input type="number" name="min_withdrawal_points" class="form-control" value="{{ $settings['min_withdrawal_points'] ?? 500 }}"></div></div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> পয়েন্ট সেটিং সেভ করুন</button>
                </form>
            </div>

            {{-- Tab 3: Social --}}
            <div class="tab-pane fade" id="social" role="tabpanel">
                <form method="POST" action="{{ route('admin.settings.update') }}">
                    @csrf @method('PUT')
                    <div class="form-group"><label>Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ $settings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/..."></div>
                    <div class="form-group"><label>Telegram URL</label><input type="url" name="telegram_url" class="form-control" value="{{ $settings['telegram_url'] ?? '' }}" placeholder="https://t.me/..."></div>
                    <div class="form-group"><label>YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ $settings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/..."></div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> সোশ্যাল লিংক সেভ করুন</button>
                </form>
            </div>

            {{-- Tab 4: Notifications --}}
            <div class="tab-pane fade" id="notify" role="tabpanel">
                <h5 class="mb-3"><i class="fas fa-bullhorn text-primary mr-2"></i>সব ইউজারকে নোটিফিকেশন পাঠান</h5>
                <form method="POST" action="{{ route('admin.settings.notify-all') }}">
                    @csrf
                    <div class="form-group"><label>শিরোনাম *</label><input type="text" name="title" class="form-control" required placeholder="নোটিফিকেশনের শিরোনাম"></div>
                    <div class="form-group"><label>বার্তা *</label><textarea name="message" class="form-control" rows="3" required placeholder="নোটিফিকেশন বার্তা..."></textarea></div>
                    <div class="form-group"><label>লিংক (ঐচ্ছিক)</label><input type="url" name="link" class="form-control" placeholder="https://..."></div>
                    <button type="submit" class="btn btn-warning" onclick="return confirm('সব ইউজারকে নোটিফিকেশন পাঠাবেন?')"><i class="fas fa-paper-plane"></i> নোটিফিকেশন পাঠান</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function(){
    $('#settingsTab a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });
});
</script>
@endpush
