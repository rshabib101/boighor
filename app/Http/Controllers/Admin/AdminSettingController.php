<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Models\Advertisement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'site_name', 'site_tagline', 'site_logo', 'site_favicon',
            'contact_email', 'contact_phone', 'facebook_url', 'telegram_url', 'youtube_url',
            'points_signup', 'points_daily_login', 'points_ad_watch', 'points_book_download',
            'points_article_read', 'points_referral', 'points_quiz',
            'points_to_bdt_rate', 'min_withdrawal_points',
            'google_adsense_code', 'google_analytics_id',
            'footer_text', 'about_text',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                SiteSetting::set($field, $request->get($field));
            }
        }

        Cache::flush();
        return back()->with('success', 'সেটিংস আপডেট হয়েছে।');
    }

    // Advertisements
    public function ads()
    {
        $ads = Advertisement::latest()->paginate(15);
        return view('admin.settings.ads', compact('ads'));
    }

    public function storeAd(Request $request)
    {
        $request->validate(['title' => 'required|string', 'position' => 'required|string', 'ad_code' => 'required|string']);
        Advertisement::create($request->all() + ['is_active' => $request->boolean('is_active', true)]);
        return back()->with('success', 'বিজ্ঞাপন যোগ করা হয়েছে।');
    }

    public function updateAd(Request $request, Advertisement $advertisement)
    {
        $advertisement->update($request->all() + ['is_active' => $request->boolean('is_active')]);
        return back()->with('success', 'বিজ্ঞাপন আপডেট হয়েছে।');
    }

    public function destroyAd(Advertisement $advertisement)
    {
        $advertisement->delete();
        return back()->with('success', 'বিজ্ঞাপন মুছে ফেলা হয়েছে।');
    }

    // Push notifications
    public function sendBulkNotification(Request $request)
    {
        $request->validate(['title' => 'required|string', 'message' => 'required|string']);

        User::where('role', 'user')->where('status', 'active')->chunk(100, function ($users) use ($request) {
            foreach ($users as $user) {
                Notification::create([
                    'user_id' => $user->id,
                    'title'   => $request->title,
                    'message' => $request->message,
                    'type'    => $request->type ?? 'info',
                    'link'    => $request->link,
                ]);
            }
        });

        return back()->with('success', 'সব ইউজারকে নোটিফিকেশন পাঠানো হয়েছে।');
    }
}
