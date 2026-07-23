<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\BookDownload;
use App\Models\WithdrawalRequest;
use App\Models\Advertisement;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBooks        = Book::count();
        $totalUsers        = User::where('role', 'user')->count();
        $totalDownloads    = BookDownload::count();
        $pendingWithdrawals = WithdrawalRequest::where('status', 'pending')->count();
        $totalCategories   = Category::count();
        $newUsersToday     = User::whereDate('created_at', today())->count();
        $downloadsToday    = BookDownload::whereDate('created_at', today())->count();

        $recentBooks = Book::with('author', 'category')->latest()->limit(5)->get();
        $recentUsers = User::where('role', 'user')->latest()->limit(5)->get();
        $pendingWithdrawalsList = WithdrawalRequest::with('user')->where('status', 'pending')->latest()->limit(5)->get();

        $monthlyDownloads = BookDownload::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');

        return view('admin.dashboard', compact(
            'totalBooks', 'totalUsers', 'totalDownloads', 'pendingWithdrawals',
            'totalCategories', 'newUsersToday', 'downloadsToday',
            'recentBooks', 'recentUsers', 'pendingWithdrawalsList', 'monthlyDownloads'
        ));
    }
}
