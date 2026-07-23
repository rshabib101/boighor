@extends('layouts.admin')
@section('title', 'ড্যাশবোর্ড')
@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary"><div class="inner"><h3>{{ number_format($totalBooks) }}</h3><p>মোট বই</p></div><div class="icon"><i class="fas fa-book"></i></div><a href="{{ route('admin.books.index') }}" class="small-box-footer">দেখুন <i class="fas fa-arrow-circle-right"></i></a></div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success"><div class="inner"><h3>{{ number_format($totalUsers) }}</h3><p>মোট ইউজার</p></div><div class="icon"><i class="fas fa-users"></i></div><a href="{{ route('admin.users.index') }}" class="small-box-footer">দেখুন <i class="fas fa-arrow-circle-right"></i></a></div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning"><div class="inner"><h3>{{ number_format($totalDownloads) }}</h3><p>মোট ডাউনলোড</p></div><div class="icon"><i class="fas fa-download"></i></div><a href="#" class="small-box-footer">বিস্তারিত <i class="fas fa-arrow-circle-right"></i></a></div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger"><div class="inner"><h3>{{ $pendingWithdrawals }}</h3><p>উইড্র পেন্ডিং</p></div><div class="icon"><i class="fas fa-money-bill-wave"></i></div><a href="{{ route('admin.withdrawals.index') }}" class="small-box-footer">অনুমোদন করুন <i class="fas fa-arrow-circle-right"></i></a></div>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-6"><div class="info-box"><span class="info-box-icon bg-info"><i class="fas fa-th-large"></i></span><div class="info-box-content"><span class="info-box-text">ক্যাটাগরি</span><span class="info-box-number">{{ $totalCategories }}</span></div></div></div>
    <div class="col-lg-3 col-6"><div class="info-box"><span class="info-box-icon bg-success"><i class="fas fa-user-plus"></i></span><div class="info-box-content"><span class="info-box-text">আজকের নতুন ইউজার</span><span class="info-box-number">{{ $newUsersToday }}</span></div></div></div>
    <div class="col-lg-3 col-6"><div class="info-box"><span class="info-box-icon bg-warning"><i class="fas fa-download"></i></span><div class="info-box-content"><span class="info-box-text">আজকের ডাউনলোড</span><span class="info-box-number">{{ $downloadsToday }}</span></div></div></div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-book mr-2 text-primary"></i>সর্বশেষ বই</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead><tr><th>বইয়ের নাম</th><th>লেখক</th><th>ক্যাটাগরি</th><th>ডাউনলোড</th></tr></thead>
                    <tbody>
                        @foreach($recentBooks as $book)
                        <tr>
                            <td><a href="{{ route('admin.books.edit', $book) }}">{{ Str::limit($book->title, 30) }}</a></td>
                            <td>{{ $book->author?->name ?? '-' }}</td>
                            <td>{{ $book->category?->name }}</td>
                            <td>{{ number_format($book->download_count) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title"><i class="fas fa-money-bill-wave mr-2 text-warning"></i>উইড্র রিকোয়েস্ট</h3></div>
            <div class="card-body p-0">
                <table class="table table-hover">
                    <thead><tr><th>ইউজার</th><th>পয়েন্ট</th><th>মাধ্যম</th><th>একশন</th></tr></thead>
                    <tbody>
                        @forelse($pendingWithdrawalsList as $w)
                        <tr>
                            <td>{{ $w->user->name }}</td>
                            <td>{{ $w->points }}</td>
                            <td>{{ ucfirst($w->payment_method) }}</td>
                            <td>
                                <form action="{{ route('admin.withdrawals.approve', $w) }}" method="POST" style="display:inline">
                                    @csrf
                                    <button class="btn btn-xs btn-success"><i class="fas fa-check"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">কোনো পেন্ডিং উইড্র নেই</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
