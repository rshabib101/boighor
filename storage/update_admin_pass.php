<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$u = App\Models\User::where('email', 'admin@boighor.com')->first();
if ($u) {
    $u->update(['password' => Illuminate\Support\Facades\Hash::make('12345678')]);
    echo "Admin password updated to 12345678\n";
} else {
    echo "Admin user not found\n";
}
