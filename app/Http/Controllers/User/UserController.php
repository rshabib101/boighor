<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookDownload;
use App\Models\WithdrawalRequest;
use App\Models\Notification;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function profile()
    {
        $user = auth()->user();
        $downloadCount = $user->downloads()->count();
        $favoriteCount = $user->favorites()->count();
        $totalPoints = $user->points;
        return view('user.profile', compact('user', 'downloadCount', 'favoriteCount', 'totalPoints'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'   => 'required|string|min:2|max:100',
            'email'  => 'nullable|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|min:11|max:15|unique:users,mobile,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        $data = ['name' => $request->name, 'email' => $request->email, 'mobile' => $request->mobile];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) Storage::delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);
        return back()->with('success', 'প্রোফাইল আপডেট হয়েছে।');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'বর্তমান পাসওয়ার্ড সঠিক নয়।']);
        }

        $user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'পাসওয়ার্ড পরিবর্তন হয়েছে।');
    }

    public function downloadHistory()
    {
        $downloads = auth()->user()->downloads()
            ->with('book.author')
            ->latest()
            ->paginate(20);
        return view('user.history', compact('downloads'));
    }

    public function favorites()
    {
        $favorites = auth()->user()->favorites()
            ->with('book.author', 'book.category')
            ->latest()
            ->paginate(20);
        return view('user.favorites', compact('favorites'));
    }

    public function bookmarks()
    {
        $bookmarks = auth()->user()->bookmarks()
            ->with('book.author')
            ->latest()
            ->paginate(20);
        return view('user.bookmarks', compact('bookmarks'));
    }

    public function wallet()
    {
        $user = auth()->user();
        $transactions = $user->pointTransactions()->latest()->paginate(20);
        $withdrawals = $user->withdrawalRequests()->latest()->paginate(10);
        $pointValues = RewardService::getPointValues();
        $minPoints = RewardService::getMinWithdrawalPoints();
        return view('user.wallet', compact('user', 'transactions', 'withdrawals', 'pointValues', 'minPoints'));
    }

    public function submitWithdrawal(Request $request)
    {
        $user = auth()->user();
        $minPoints = RewardService::getMinWithdrawalPoints();

        $request->validate([
            'points'         => 'required|integer|min:' . $minPoints,
            'payment_method' => 'required|in:bkash,nagad,rocket,mobile_recharge',
            'account_number' => 'required|string|min:11|max:15',
        ], [
            'points.min' => 'ন্যূনতম ' . $minPoints . ' পয়েন্ট উইড্র করা যাবে।',
        ]);

        if ($user->points < $request->points) {
            return back()->withErrors(['points' => 'আপনার পর্যাপ্ত পয়েন্ট নেই।']);
        }

        // Check pending withdrawal
        $pending = $user->withdrawalRequests()->where('status', 'pending')->exists();
        if ($pending) {
            return back()->with('error', 'আপনার একটি উইড্র রিকোয়েস্ট প্রক্রিয়াধীন আছে।');
        }

        $amount = RewardService::getPointsForWithdrawal($request->points);
        RewardService::deductPoints($user, $request->points, 'withdrawal', 'উইড্র রিকোয়েস্ট');

        WithdrawalRequest::create([
            'user_id'        => $user->id,
            'points'         => $request->points,
            'amount'         => $amount,
            'payment_method' => $request->payment_method,
            'account_number' => $request->account_number,
        ]);

        return back()->with('success', 'উইড্র রিকোয়েস্ট পাঠানো হয়েছে। অ্যাডমিন অনুমোদনের পর পেমেন্ট পাবেন।');
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->paginate(20);
        // Mark all as read
        $user->notifications()->where('is_read', false)->update(['is_read' => true]);
        return view('user.notifications', compact('notifications'));
    }

    public function adWatch(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'লগইন করুন'], 401);
        }
        RewardService::giveAdWatchBonus(auth()->user());
        return response()->json(['success' => true, 'message' => RewardService::getPointValues()['ad_watch'] . ' পয়েন্ট পেয়েছেন!', 'points' => auth()->user()->fresh()->points]);
    }
}
