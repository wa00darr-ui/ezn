<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

$email = 'admin@ezn.local';

if (User::where('email', $email)->exists()) {
    echo "Admin email already exists.";
    exit;
}

$user = new User();

$user->name = 'مديرة النظام';
$user->email = $email;
$user->password = Hash::make('MyStrongPass@2026');
$user->role = 'admin';
$user->is_active = true;

$user->save();

echo "Admin account created successfully.";