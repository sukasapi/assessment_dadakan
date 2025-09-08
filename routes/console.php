<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:reset-pass', function () {
    $admin = User::where('name', 'Administrator')->first();
    if (!$admin) {
        $this->error('User Administrator tidak ditemukan.');
        return 1;
    }
    $admin->password = Hash::make('pass123');
    $admin->save();
    $this->info('Password Administrator berhasil diubah ke pass123');
    return 0;
})->describe('Reset password Administrator menjadi pass123');
