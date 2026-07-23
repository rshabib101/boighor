@extends('layouts.app')
@section('title', 'নোটিফিকেশন - বইঘর')
@section('content')
<div class="container"><div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        <div class="form-card">
            <h2 style="font-size:1.1rem;margin-bottom:20px">নোটিফিকেশন</h2>
            @forelse($notifications as $n)
            <div style="display:flex;gap:12px;padding:14px 0;border-bottom:1px solid var(--border);{{ !$n->is_read ? 'background:rgba(99,102,241,.04);' : '' }}">
                <div style="width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                     background:{{ $n->type === 'success' ? 'rgba(16,185,129,.1)' : ($n->type === 'warning' ? 'rgba(245,158,11,.1)' : 'rgba(99,102,241,.1)') }};
                     color:{{ $n->type === 'success' ? 'var(--success)' : ($n->type === 'warning' ? 'var(--accent)' : 'var(--primary)') }}">
                    <i class="fas {{ $n->type === 'success' ? 'fa-check-circle' : ($n->type === 'warning' ? 'fa-exclamation-circle' : 'fa-bell') }}"></i>
                </div>
                <div>
                    <div style="font-weight:600;font-size:.92rem">{{ $n->title }}</div>
                    <div style="color:var(--text-2);font-size:.88rem;margin-top:2px">{{ $n->message }}</div>
                    <div style="color:var(--text-4);font-size:.75rem;margin-top:4px">{{ $n->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            <div class="empty-state"><i class="fas fa-bell-slash"></i><h3>কোনো নোটিফিকেশন নেই</h3></div>
            @endforelse
            <div class="pagination-wrap">{{ $notifications->links('partials.pagination') }}</div>
        </div>
    </div>
</div></div>
@endsection
