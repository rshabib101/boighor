@extends('layouts.admin')
@section('title', 'বিজ্ঞাপন ম্যানেজমেন্ট')
@section('content')
<div class="row">
<div class="col-md-8">
    <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-ad mr-2"></i>বিজ্ঞাপন তালিকা</h3></div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead><tr><th>শিরোনাম</th><th>পজিশন</th><th>কোড / লিংক</th><th>ইম্প্রেশন</th><th>ক্লিক</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
                <tbody>
                    @forelse($ads as $ad)
                    <tr>
                        <td><strong>{{ $ad->title }}</strong></td>
                        <td><span class="badge badge-info">{{ $ad->position }}</span></td>
                        <td><code>{{ Str::limit($ad->ad_code ?: $ad->link, 30) }}</code></td>
                        <td>{{ number_format($ad->impression_count) }}</td>
                        <td>{{ number_format($ad->click_count) }}</td>
                        <td><span class="badge badge-{{ $ad->is_active ? 'success' : 'secondary' }}">{{ $ad->is_active ? 'সক্রিয়' : 'বন্ধ' }}</span></td>
                        <td>
                            <form action="{{ route('admin.settings.ads.destroy', $ad) }}" method="POST" style="display:inline" onsubmit="return confirm('বিজ্ঞাপনটি মুছবেন?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">কোনো বিজ্ঞাপন যোগ করা হয়নি</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-3">{{ $ads->links() }}</div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card">
        <div class="card-header"><h3 class="card-title"><i class="fas fa-plus mr-2"></i>নতুন বিজ্ঞাপন যোগ করুন</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.ads.store') }}">
                @csrf
                <div class="form-group"><label>বিজ্ঞাপনের নাম/শিরোনাম *</label><input type="text" name="title" class="form-control" required placeholder="যেমন: Header Banner Ad"></div>
                <div class="form-group">
                    <label>পজিশন *</label>
                    <select name="position" class="form-control" required>
                        <option value="header">Header (হেডার)</option>
                        <option value="sidebar">Sidebar (সাইডবার)</option>
                        <option value="in_content">In Content (কন্টেন্টের ভেতরে)</option>
                        <option value="footer">Footer (ফুটার)</option>
                        <option value="popup">Popup (পপআপ)</option>
                    </select>
                </div>
                <div class="form-group"><label>বিজ্ঞাপন কোড (AdSense / HTML) *</label><textarea name="ad_code" class="form-control" rows="4" required placeholder="HTML বা Google AdSense Script Code দিন..."></textarea></div>
                <div class="form-group"><label>কাস্টম ডিরেক্ট লিংক (ঐচ্ছিক)</label><input type="url" name="link" class="form-control" placeholder="https://..."></div>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked>
                        <label class="custom-control-label" for="is_active">বিজ্ঞাপনটি চালু রাখুন</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-save"></i> বিজ্ঞাপন সংরক্ষণ করুন</button>
            </form>
        </div>
    </div>
</div>
</div>
@endsection
