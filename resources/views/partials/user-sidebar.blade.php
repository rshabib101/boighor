<div class="user-sidebar">
    <div class="user-sidebar-header">
        <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" class="user-avatar-lg">
        <div style="font-weight:700;font-size:1rem">{{ auth()->user()->name }}</div>
        <div style="font-size:.82rem;opacity:.8">{{ auth()->user()->email ?? auth()->user()->mobile }}</div>
        <div class="user-points-display">
            <i class="fas fa-coins" style="color:var(--accent)"></i>
            {{ number_format(auth()->user()->points) }} পয়েন্ট
        </div>
    </div>
    <nav>
        <a href="{{ route('user.profile') }}" class="user-nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}">
            <i class="fas fa-user"></i> প্রোফাইল
        </a>
        <a href="{{ route('user.history') }}" class="user-nav-link {{ request()->routeIs('user.history') ? 'active' : '' }}">
            <i class="fas fa-download"></i> ডাউনলোড হিস্ট্রি
        </a>
        <a href="{{ route('user.favorites') }}" class="user-nav-link {{ request()->routeIs('user.favorites') ? 'active' : '' }}">
            <i class="fas fa-heart"></i> ফেভারিট
        </a>
        <a href="{{ route('user.bookmarks') }}" class="user-nav-link {{ request()->routeIs('user.bookmarks') ? 'active' : '' }}">
            <i class="fas fa-bookmark"></i> বুকমার্ক
        </a>
        <a href="{{ route('user.wallet') }}" class="user-nav-link {{ request()->routeIs('user.wallet') ? 'active' : '' }}">
            <i class="fas fa-wallet"></i> ওয়ালেট
        </a>
        <a href="{{ route('user.notifications') }}" class="user-nav-link {{ request()->routeIs('user.notifications') ? 'active' : '' }}">
            <i class="fas fa-bell"></i> নোটিফিকেশন
            @if(auth()->user()->unreadNotificationsCount() > 0)
            <span class="badge" style="position:static;margin-left:auto">{{ auth()->user()->unreadNotificationsCount() }}</span>
            @endif
        </a>
    </nav>
</div>
