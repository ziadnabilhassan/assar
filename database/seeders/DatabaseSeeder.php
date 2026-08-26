<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'first_name' => 'Ahmed',
                'last_name' => 'Admin',
                'phone' => '01000000001',
                'password' => Hash::make('admin123'),
            ]
        )->forceFill(['is_admin' => 1])->save();

        $this->call(DemoCatalogSeeder::class);
    }
}
