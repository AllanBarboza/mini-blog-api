<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (Admin::exists()) {
            return;
        }

        Admin::create([
            'name'     => 'Admin',
            'username' => config('admin.initial.username'),
            'password' => Hash::make(config('admin.initial.password')),
        ]);
    }
}
