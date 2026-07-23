@extends('layouts.app')
@section('title', 'ওয়ালেট - বইঘর')
@section('content')
<div class="container">
<div class="user-page-layout">
    @include('partials.user-sidebar')
    <div>
        {{-- Balance Card --}}
        <div class="wallet-card">
            <div class="wallet-label">আপনার মোট পয়েন্ট</div>
            <div class="wallet-balance js-points-display">{{ number_format($user->points) }}</div>
            <div style="margin-top:8px;opacity:.8;font-size:.85rem">
                সমতুল্য মূল্য: ৳{{ number_format(\App\Services\RewardService::getPointsForWithdrawal($user->points), 2) }}
            </div>
        </div>

        {{-- Reward Ways --}}
        <div class="form-card mb-3">
            <h3 style="font-size:1rem;margin-bottom:16px"><i class="fas fa-coins" style="color:var(--accent)"></i> পয়েন্ট অর্জনের উপায়</h3>
            <div class="reward-grid">
                <div class="reward-card">
                    <div class="reward-icon">🎁</div>
                    <div class="reward-pts">+{{ $pointValues['signup'] }}</div>
                    <div class="reward-name">সাইনআপ বোনাস</div>
                </div>
                <div class="reward-card">
                    <div class="reward-icon">📅</div>
                    <div class="reward-pts">+{{ $pointValues['daily_login'] }}</div>
                    <div class="reward-name">দৈনিক লগইন</div>
                    @if($user->canClaimDailyBonus())
                    <span class="badge-pill badge-success mt-1">দাবি করুন!</span>
                    @else
                    <span class="badge-pill badge-primary mt-1">পেয়েছেন</span>
                    @endif
                </div>
                <div class="reward-card">
                    <div class="reward-icon">📺</div>
                    <div class="reward-pts">+{{ $pointValues['ad_watch'] }}</div>
                    <div class="reward-name">বিজ্ঞাপন দেখুন</div>
                    <button class="btn btn-sm btn-primary mt-2 js-watch-ad" style="font-size:.78rem">
                        <i class="fas fa-play-circle"></i> দেখুন
                    </button>
                </div>
                <div class="reward-card">
                    <div class="reward-icon">📥</div>
                    <div class="reward-pts">+{{ $pointValues['book_download'] }}</div>
                    <div class="reward-name">বই ডাউনলোড</div>
                </div>
                <div class="reward-card">
                    <div class="reward-icon">📚</div>
                    <div class="reward-pts">+{{ $pointValues['article_read'] }}</div>
                    <div class="reward-name">প্রবন্ধ পড়ুন</div>
                </div>
                <div class="reward-card">
                    <div class="reward-icon">👥</div>
                    <div class="reward-pts">+{{ $pointValues['referral'] }}</div>
                    <div class="reward-name">বন্ধু রেফার</div>
                </div>
                <div class="reward-card">
                    <div class="reward-icon">🧠</div>
                    <div class="reward-pts">+{{ $pointValues['quiz'] }}</div>
                    <div class="reward-name">কুইজ সম্পন্ন</div>
                </div>
            </div>
        </div>

        {{-- Withdrawal Form --}}
        <div class="form-card mb-3">
            <h3 style="font-size:1rem;margin-bottom:16px"><i class="fas fa-money-bill-wave" style="color:var(--success)"></i> পয়েন্ট উইড্র করুন</h3>
            <div style="background:rgba(16,185,129,.1);border:1px solid var(--success);border-radius:var(--radius-sm);padding:10px 14px;margin-bottom:16px;font-size:.85rem;color:var(--success)">
                <i class="fas fa-info-circle"></i> ন্যূনতম {{ $minPoints }} পয়েন্ট = ৳{{ \App\Services\RewardService::getPointsForWithdrawal($minPoints) }} টাকা
            </div>
            @if($user->points < $minPoints)
            <div style="background:rgba(239,68,68,.1);border:1px solid var(--danger);border-radius:var(--radius-sm);padding:10px 14px;color:var(--danger);font-size:.85rem">
                <i class="fas fa-exclamation-circle"></i> আপনার পয়েন্ট পর্যাপ্ত নয়। আরো {{ $minPoints - $user->points }} পয়েন্ট অর্জন করুন।
            </div>
            @else
            <form method="POST" action="{{ route('user.withdraw') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">পয়েন্ট পরিমাণ</label>
                    <input type="number" name="points" class="form-control" min="{{ $minPoints }}" max="{{ $user->points }}" placeholder="{{ $minPoints }}" required>
                    @error('points')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">পেমেন্ট পদ্ধতি</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">নির্বাচন করুন</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="rocket">Rocket</option>
                        <option value="mobile_recharge">মোবাইল রিচার্জ</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">অ্যাকাউন্ট নম্বর</label>
                    <input type="text" name="account_number" class="form-control" placeholder="01XXXXXXXXX" required>
                </div>
                <button type="submit" class="btn btn-success">উইড্র রিকোয়েস্ট পাঠান</button>
            </form>
            @endif
        </div>

        {{-- Transactions --}}
        <div class="form-card mb-3">
            <h3 style="font-size:1rem;margin-bottom:16px">পয়েন্ট হিস্ট্রি</h3>
            @forelse($transactions as $t)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-size:.9rem;font-weight:600">{{ $t->description }}</div>
                    <div style="font-size:.78rem;color:var(--text-3)">{{ $t->created_at->format('d M Y, h:i A') }}</div>
                </div>
                <div style="font-weight:700;color:{{ $t->points > 0 ? 'var(--success)' : 'var(--danger)' }}">
                    {{ $t->points > 0 ? '+' : '' }}{{ $t->points }}
                </div>
            </div>
            @empty
            <div class="empty-state" style="padding:20px"><i class="fas fa-history"></i><p>কোনো লেনদেন নেই</p></div>
            @endforelse
            <div class="pagination-wrap">{{ $transactions->links('partials.pagination') }}</div>
        </div>

        {{-- Withdrawal History --}}
        <div class="form-card">
            <h3 style="font-size:1rem;margin-bottom:16px">উইড্র হিস্ট্রি</h3>
            @forelse($withdrawals as $w)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 0;border-bottom:1px solid var(--border)">
                <div>
                    <div style="font-size:.9rem;font-weight:600">{{ $w->points }} পয়েন্ট → ৳{{ $w->amount }}</div>
                    <div style="font-size:.78rem;color:var(--text-3)">{{ ucfirst($w->payment_method) }} - {{ $w->account_number }}</div>
                    <div style="font-size:.78rem;color:var(--text-3)">{{ $w->created_at->format('d M Y') }}</div>
                </div>
                <span class="badge-pill {{ $w->status === 'approved' ? 'badge-success' : ($w->status === 'rejected' ? 'badge-danger' : 'badge-warning') }}">
                    {{ $w->status === 'approved' ? 'অনুমোদিত' : ($w->status === 'rejected' ? 'বাতিল' : 'প্রক্রিয়াধীন') }}
                </span>
            </div>
            @empty
            <div class="empty-state" style="padding:20px"><i class="fas fa-money-bill"></i><p>কোনো উইড্র নেই</p></div>
            @endforelse
        </div>
    </div>
</div>
</div>
@endsection
