<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeDisplayNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Grade::where('name', 'CCG')->update(['display_name' => 'Cell Culture Grade']);
        Grade::where('name', 'USP')->update(['display_name' => 'USP Grade or Highest Available']);
    }
}
