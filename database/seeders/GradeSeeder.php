<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grade::create(['id' => 1, 'name' => 'CCG']);
        Grade::create(['id' => 2, 'name' => 'USP']);
    }
}
