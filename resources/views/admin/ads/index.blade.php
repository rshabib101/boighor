@extends('layouts.admin')
@section('title', 'বিজ্ঞাপন ম্যানেজমেন্ট')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-ad mr-2"></i>বিজ্ঞাপন ম্যানেজমেন্ট</h3>
        <a href="{{ route('admin.settings.ads.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> নতুন বিজ্ঞাপন</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover">
            <thead><tr><th>নাম</th><th>পজিশন</th><th>ধরন</th><th>ইম্প্রেশন</th><th>ক্লিক</th><th>স্ট্যাটাস</th><th>মেয়াদ</th><th>একশন</th></tr></thead>
            <tbody>
                @forelse($ads as $ad)
                <tr>
                    <td><strong>{{ $ad->name }}</strong></td>
                    <td><span class="badge badge-info">{{ $ad->position }}</span></td>
                    <td>{{ $ad->type }}</td>
                    <td>{{ number_format($ad->impressions) }}</td>
                    <td>{{ number_format($ad->clicks) }}</td>
                    <td><span class="badge badge-{{ $ad->is_active ? 'success' : 'secondary' }}">{{ $ad->is_active ? 'সক্রিয়' : 'বন্ধ' }}</span></td>
                    <td>{{ $ad->ends_at ? $ad->ends_at->format('d M Y') : 'অসীমিত' }}</td>
                    <td>
                        <a href="{{ route('admin.settings.ads.edit', $ad) }}" class="btn btn-xs btn-info"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.settings.ads.destroy', $ad) }}" method="POST" style="display:inline" onsubmit="return confirm('মুছবেন?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">কোনো বিজ্ঞাপন নেই</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
