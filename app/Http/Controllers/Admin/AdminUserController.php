<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('mobile', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $users = $query->withCount('downloads')->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['downloads.book', 'pointTransactions', 'withdrawalRequests']);
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $user->update(['status' => $user->status === 'active' ? 'banned' : 'active']);
        return back()->with('success', 'ইউজার স্ট্যাটাস পরিবর্তন হয়েছে।');
    }

    public function adjustPoints(Request $request, User $user)
    {
        $request->validate(['points' => 'required|integer', 'reason' => 'required|string']);
        $points = (int) $request->points;
        if ($points > 0) {
            \App\Services\RewardService::addPoints($user, $points, 'admin_adjustment', 'অ্যাডমিন: ' . $request->reason);
        } elseif ($points < 0 && $user->points >= abs($points)) {
            \App\Services\RewardService::deductPoints($user, abs($points), 'admin_adjustment', 'অ্যাডমিন: ' . $request->reason);
        }
        return back()->with('success', 'পয়েন্ট আপডেট হয়েছে।');
    }

    public function sendNotification(Request $request, User $user)
    {
        $request->validate(['title' => 'required|string', 'message' => 'required|string']);
        Notification::create([
            'user_id' => $user->id,
            'title'   => $request->title,
            'message' => $request->message,
            'type'    => 'info',
        ]);
        return back()->with('success', 'নোটিফিকেশন পাঠানো হয়েছে।');
    }
}
