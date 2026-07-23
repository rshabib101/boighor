<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminWithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = WithdrawalRequest::with('user');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $withdrawals = $query->latest()->paginate(20)->withQueryString();
        return view('admin.withdrawals.index', compact('withdrawals'));
    }

    public function approve(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate(['admin_note' => 'nullable|string']);
        $withdrawal->update([
            'status'       => 'approved',
            'admin_note'   => $request->admin_note ?? 'অনুমোদিত হয়েছে।',
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $withdrawal->user_id,
            'title'   => 'উইড্র অনুমোদিত',
            'message' => 'আপনার ' . $withdrawal->points . ' পয়েন্টের উইড্র রিকোয়েস্ট অনুমোদিত হয়েছে। ৳' . $withdrawal->amount . ' ' . ucfirst($withdrawal->payment_method) . '-এ পাঠানো হয়েছে।',
            'type'    => 'success',
        ]);

        return back()->with('success', 'উইড্র অনুমোদন করা হয়েছে।');
    }

    public function reject(Request $request, WithdrawalRequest $withdrawal)
    {
        $request->validate(['admin_note' => 'required|string']);

        // Refund points
        \App\Services\RewardService::addPoints(
            $withdrawal->user,
            $withdrawal->points,
            'withdrawal_refund',
            'উইড্র রিজেক্ট - পয়েন্ট ফেরত: ' . $request->admin_note
        );

        $withdrawal->update([
            'status'       => 'rejected',
            'admin_note'   => $request->admin_note,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);

        Notification::create([
            'user_id' => $withdrawal->user_id,
            'title'   => 'উইড্র বাতিল',
            'message' => 'আপনার উইড্র রিকোয়েস্ট বাতিল হয়েছে। কারণ: ' . $request->admin_note . '। পয়েন্ট আপনার ওয়ালেটে ফেরত দেওয়া হয়েছে।',
            'type'    => 'warning',
        ]);

        return back()->with('success', 'উইড্র বাতিল করা হয়েছে এবং পয়েন্ট ফেরত দেওয়া হয়েছে।');
    }
}
