<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'name' => 'Alice Johnson',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bob Smith',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Charlie Brown',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
