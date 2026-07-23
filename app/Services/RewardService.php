<?php

namespace App\Services;

use App\Models\User;
use App\Models\PointTransaction;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

class RewardService
{
    public static function getPointValues(): array
    {
        return [
            'signup'        => (int) SiteSetting::get('points_signup', 50),
            'daily_login'   => (int) SiteSetting::get('points_daily_login', 10),
            'ad_watch'      => (int) SiteSetting::get('points_ad_watch', 5),
            'book_download' => (int) SiteSetting::get('points_book_download', 2),
            'article_read'  => (int) SiteSetting::get('points_article_read', 3),
            'referral'      => (int) SiteSetting::get('points_referral', 20),
            'quiz'          => (int) SiteSetting::get('points_quiz', 15),
        ];
    }

    public static function addPoints(User $user, int $points, string $type, string $description = '', ?object $reference = null): bool
    {
        if ($points <= 0) return false;

        DB::transaction(function () use ($user, $points, $type, $description, $reference) {
            $user->increment('points', $points);

            $data = [
                'user_id'     => $user->id,
                'points'      => $points,
                'type'        => $type,
                'description' => $description ?: self::getTypeLabel($type),
            ];

            if ($reference) {
                $data['reference_type'] = get_class($reference);
                $data['reference_id']   = $reference->id;
            } else {
                $data['reference_type'] = null;
                $data['reference_id']   = null;
            }

            PointTransaction::create($data);
        });

        return true;
    }

    public static function deductPoints(User $user, int $points, string $type, string $description = ''): bool
    {
        if ($user->points < $points) return false;

        DB::transaction(function () use ($user, $points, $type, $description) {
            $user->decrement('points', $points);
            PointTransaction::create([
                'user_id'        => $user->id,
                'points'         => -$points,
                'type'           => $type,
                'description'    => $description ?: 'পয়েন্ট কাটা হয়েছে',
                'reference_type' => null,
                'reference_id'   => null,
            ]);
        });

        return true;
    }

    public static function giveSignupBonus(User $user): void
    {
        $pts = self::getPointValues()['signup'];
        self::addPoints($user, $pts, 'signup', 'সাইনআপ বোনাস');
    }

    public static function giveDailyLoginBonus(User $user): void
    {
        if (!$user->canClaimDailyBonus()) return;

        $pts = self::getPointValues()['daily_login'];
        self::addPoints($user, $pts, 'daily_login', 'দৈনিক লগইন বোনাস');
        $user->update(['last_daily_bonus_at' => now()->toDateString()]);
    }

    public static function giveDownloadBonus(User $user, object $book): void
    {
        $pts = self::getPointValues()['book_download'];
        self::addPoints($user, $pts, 'book_download', '"' . $book->title . '" ডাউনলোড বোনাস', $book);
    }

    public static function giveAdWatchBonus(User $user): void
    {
        $pts = self::getPointValues()['ad_watch'];
        self::addPoints($user, $pts, 'ad_watch', 'বিজ্ঞাপন দেখার বোনাস');
    }

    public static function giveArticleReadBonus(User $user): void
    {
        $pts = self::getPointValues()['article_read'];
        self::addPoints($user, $pts, 'article_read', 'প্রবন্ধ পড়ার বোনাস');
    }

    public static function giveReferralBonus(User $referrer, User $newUser): void
    {
        $pts = self::getPointValues()['referral'];
        self::addPoints($referrer, $pts, 'referral', '"' . $newUser->name . '" রেফার করার বোনাস', $newUser);
    }

    public static function giveQuizBonus(User $user, object $quiz, int $overridePoints = 0): void
    {
        $pts = $overridePoints > 0 ? $overridePoints : (self::getPointValues()['quiz'] ?? 15);
        self::addPoints($user, $pts, 'quiz', '"' . $quiz->title . '" কুইজ বোনাস', $quiz);
    }

    public static function getPointsForWithdrawal(int $points): float
    {
        $rate = (float) SiteSetting::get('points_to_bdt_rate', 0.1); // 10 points = 1 BDT
        return round($points * $rate, 2);
    }

    public static function getMinWithdrawalPoints(): int
    {
        return (int) SiteSetting::get('min_withdrawal_points', 500);
    }

    private static function getTypeLabel(string $type): string
    {
        return match ($type) {
            'signup'        => 'সাইনআপ বোনাস',
            'daily_login'   => 'দৈনিক লগইন বোনাস',
            'ad_watch'      => 'বিজ্ঞাপন দেখার বোনাস',
            'book_download' => 'বই ডাউনলোড বোনাস',
            'article_read'  => 'প্রবন্ধ পড়ার বোনাস',
            'referral'      => 'রেফার বোনাস',
            'quiz'          => 'কুইজ বোনাস',
            'withdrawal'    => 'পয়েন্ট উইড্র',
            default         => 'পয়েন্ট লেনদেন',
        };
    }
}
