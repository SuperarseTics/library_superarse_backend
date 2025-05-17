<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'id' => 1,
                'section' => 'system',
                'properties' => json_encode([
                    'max_loan_days' => 2,
                    'max_loan_books' => 2
                ])
            ],
            [
                'id' => 2,
                'section' => 'notifications',
                'properties' => json_encode([
                    'email' => 'chistoploy@gmail.com',
                    'days_advance' => 2,
                    'last_day' => true
                ])
            ],
            [
                'id' => 3,
                'section' => 'rules',
                'properties' => json_encode([
                    '0' => null,
                    '1' => null,
                    '2' => null,
                    '3' => null,
                    '4' => null,
                    '5' => null
                ])
            ]
        ]);
    }
}
