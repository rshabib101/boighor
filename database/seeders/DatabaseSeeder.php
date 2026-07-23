<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name'           => 'Admin',
            'email'          => 'admin@boighor.com',
            'mobile'         => '01700000000',
            'password'       => Hash::make('admin123'),
            'role'           => 'admin',
            'status'         => 'active',
            'points'         => 0,
            'referral_code'  => 'ADMIN001',
            'last_login_at'  => now(),
        ]);

        // Demo user
        User::create([
            'name'           => 'Demo User',
            'email'          => 'user@boighor.com',
            'mobile'         => '01711111111',
            'password'       => Hash::make('user123'),
            'role'           => 'user',
            'status'         => 'active',
            'points'         => 150,
            'referral_code'  => 'USER001',
            'last_login_at'  => now(),
        ]);

        // Categories
        $categories = [
            ['name' => 'ইসলামিক', 'name_en' => 'Islamic', 'icon' => 'fas fa-moon', 'color' => '#10b981', 'sort_order' => 1],
            ['name' => 'উপন্যাস', 'name_en' => 'Novel', 'icon' => 'fas fa-book-open', 'color' => '#6366f1', 'sort_order' => 2],
            ['name' => 'গল্প', 'name_en' => 'Story', 'icon' => 'fas fa-feather-alt', 'color' => '#f59e0b', 'sort_order' => 3],
            ['name' => 'কবিতা', 'name_en' => 'Poetry', 'icon' => 'fas fa-pen-fancy', 'color' => '#ec4899', 'sort_order' => 4],
            ['name' => 'ইতিহাস', 'name_en' => 'History', 'icon' => 'fas fa-landmark', 'color' => '#8b5cf6', 'sort_order' => 5],
            ['name' => 'জীবনী', 'name_en' => 'Biography', 'icon' => 'fas fa-user-circle', 'color' => '#14b8a6', 'sort_order' => 6],
            ['name' => 'বিজ্ঞান', 'name_en' => 'Science', 'icon' => 'fas fa-atom', 'color' => '#3b82f6', 'sort_order' => 7],
            ['name' => 'প্রযুক্তি', 'name_en' => 'Technology', 'icon' => 'fas fa-microchip', 'color' => '#64748b', 'sort_order' => 8],
            ['name' => 'শিক্ষামূলক', 'name_en' => 'Educational', 'icon' => 'fas fa-graduation-cap', 'color' => '#f97316', 'sort_order' => 9],
            ['name' => 'শিশু-কিশোর', 'name_en' => 'Children', 'icon' => 'fas fa-child', 'color' => '#06b6d4', 'sort_order' => 10],
            ['name' => 'বিসিএস/চাকরি', 'name_en' => 'BCS/Job', 'icon' => 'fas fa-briefcase', 'color' => '#84cc16', 'sort_order' => 11],
            ['name' => 'ম্যাগাজিন', 'name_en' => 'Magazine', 'icon' => 'fas fa-newspaper', 'color' => '#ef4444', 'sort_order' => 12],
        ];

        foreach ($categories as $cat) {
            Category::create(array_merge($cat, ['slug' => Str::slug($cat['name_en'])]));
        }

        // Sample Authors
        $authors = [
            'হুমায়ূন আহমেদ', 'মুহম্মদ জাফর ইকবাল', 'আনিসুল হক',
            'সৈয়দ শামসুল হক', 'ইমদাদুল হক মিলন', 'রবীন্দ্রনাথ ঠাকুর',
            'কাজী নজরুল ইসলাম', 'ড. মুহাম্মদ জাফর ইকবাল', 'নীলুফার মান্নান',
        ];

        foreach ($authors as $name) {
            Author::create([
                'name'    => $name,
                'slug'    => Str::slug($name) . '-' . Str::random(4),
                'is_active' => true,
            ]);
        }

        // Sample Publisher
        Publisher::create(['name' => 'অনন্যা', 'slug' => 'ananya', 'is_active' => true]);
        Publisher::create(['name' => 'প্রথমা', 'slug' => 'prothoma', 'is_active' => true]);
        Publisher::create(['name' => 'অন্যধারা', 'slug' => 'onnodhara', 'is_active' => true]);

        // Sample Books
        $bookTitles = [
            ['title' => 'মিসির আলি সমগ্র', 'cat' => 2, 'author' => 1],
            ['title' => 'হিমু সমগ্র', 'cat' => 2, 'author' => 1],
            ['title' => 'রিহার্সেল', 'cat' => 3, 'author' => 3],
            ['title' => 'আলো আঁধারী', 'cat' => 2, 'author' => 2],
            ['title' => 'বাংলাদেশের ইতিহাস', 'cat' => 5, 'author' => 4],
            ['title' => 'গীতাঞ্জলি', 'cat' => 4, 'author' => 6],
            ['title' => 'বিজ্ঞান ও প্রযুক্তি', 'cat' => 7, 'author' => 2],
            ['title' => 'BCS প্রিলিমিনারি গাইড', 'cat' => 11, 'author' => 9],
            ['title' => 'ছোটদের রহস্য গল্প', 'cat' => 10, 'author' => 2],
            ['title' => 'ইসলামের ইতিহাস', 'cat' => 1, 'author' => 4],
            ['title' => 'রবীন্দ্র রচনাবলী', 'cat' => 4, 'author' => 6],
            ['title' => 'প্রোগ্রামিং শেখার সহজ পথ', 'cat' => 8, 'author' => 2],
        ];

        foreach ($bookTitles as $i => $b) {
            Book::create([
                'title'            => $b['title'],
                'slug'             => Str::slug($b['title']) . '-pdf',
                'category_id'      => $b['cat'],
                'author_id'        => $b['author'],
                'publisher_id'     => rand(1, 3),
                'description'      => 'এটি একটি জনপ্রিয় বাংলা বই। এই বইতে অনেক চমৎকার বিষয় আলোচনা করা হয়েছে।',
                'language'         => 'Bengali',
                'pages'            => rand(150, 500),
                'publication_year' => rand(2015, 2024),
                'file_format'      => 'PDF',
                'file_size_mb'     => round(rand(10, 50) / 10, 1),
                'download_count'   => rand(100, 5000),
                'view_count'       => rand(500, 20000),
                'rating'           => round(rand(35, 50) / 10, 1),
                'total_ratings'    => rand(10, 200),
                'is_featured'      => $i < 5,
                'is_active'        => true,
            ]);
        }

        // Site Settings
        $settings = [
            'site_name'               => 'বইঘর',
            'site_tagline'            => 'বাংলা বইয়ের সেরা ঠিকানা',
            'contact_email'           => 'info@boighor.com',
            'contact_phone'           => '01700000000',
            'facebook_url'            => 'https://facebook.com/boighor',
            'telegram_url'            => 'https://t.me/boighor',
            'youtube_url'             => '',
            'points_signup'           => '50',
            'points_daily_login'      => '10',
            'points_ad_watch'         => '5',
            'points_book_download'    => '2',
            'points_article_read'     => '3',
            'points_referral'         => '20',
            'points_quiz'             => '15',
            'points_to_bdt_rate'      => '0.1',
            'min_withdrawal_points'   => '500',
            'footer_text'             => '© ' . date('Y') . ' বইঘর। সর্বস্বত্ব সংরক্ষিত।',
            'about_text'              => 'বইঘর হলো বাংলা বইয়ের সেরা অনলাইন প্ল্যাটফর্ম।',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::create(['key' => $key, 'value' => $value]);
        }
    }
}
