<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'user_id' => 1,
                'name' => 'test1',
            ],
            [
                'user_id' => 1,
                'name' => 'test2',
            ],
            [
                'user_id' => 1,
                'name' => 'test3',
            ],
        ]);
    }
}
