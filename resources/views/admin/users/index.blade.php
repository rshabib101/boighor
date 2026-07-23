@extends('layouts.admin')
@section('title', 'ইউজার ম্যানেজমেন্ট')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title"><i class="fas fa-users mr-2"></i>সব ইউজার</h3>
    </div>
    <div class="card-body">
        <form method="GET" class="mb-3" style="display:flex;gap:10px;flex-wrap:wrap">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" style="width:250px" placeholder="নাম, ইমেইল বা মোবাইল...">
            <select name="status" class="form-control" style="width:150px">
                <option value="">সব স্ট্যাটাস</option>
                <option value="active" {{ request('status')==='active'?'selected':'' }}>সক্রিয়</option>
                <option value="banned" {{ request('status')==='banned'?'selected':'' }}>নিষিদ্ধ</option>
            </select>
            <button class="btn btn-secondary"><i class="fas fa-search"></i></button>
        </form>
        <table class="table table-hover">
            <thead><tr><th>নাম</th><th>মোবাইল/ইমেইল</th><th>পয়েন্ট</th><th>ডাউনলোড</th><th>যোগদান</th><th>স্ট্যাটাস</th><th>একশন</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->mobile ?? $user->email }}</td>
                    <td><i class="fas fa-coins text-warning"></i> {{ number_format($user->points) }}</td>
                    <td>{{ $user->downloads_count }}</td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $user->status === 'active' ? 'success' : 'danger' }}">{{ $user->status === 'active' ? 'সক্রিয়' : 'নিষিদ্ধ' }}</span></td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-xs btn-info"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display:inline">
                            @csrf
                            <button class="btn btn-xs btn-{{ $user->status === 'active' ? 'warning' : 'success' }}" title="{{ $user->status === 'active' ? 'নিষিদ্ধ করুন' : 'সক্রিয় করুন' }}">
                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted">কোনো ইউজার নেই</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $users->appends(request()->query())->links() }}
    </div>
</div>
@endsection
