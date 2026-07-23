<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequiredRowsSeeder extends Seeder
{
    /**
     * Seed starter rows needed by the app without duplicating existing data.
     */
    public function run(): void
    {
        DB::unprepared(file_get_contents(database_path('athar_phpmyadmin_seed_required_rows.sql')));
    }
}
