<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$b = App\Models\Book::where('slug', 'habib')->first();
if ($b) {
    echo "Book: " . $b->title . "\n";
    echo "PDF Path: " . $b->pdf_path . "\n";
    echo "Storage public exists: " . (Illuminate\Support\Facades\Storage::disk('public')->exists($b->pdf_path) ? "YES" : "NO") . "\n";
} else {
    echo "Book not found\n";
}
