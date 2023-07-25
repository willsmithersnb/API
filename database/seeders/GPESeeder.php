<?php

namespace Database\Seeders;

use App\Models\NBAI\GeneProteinExpression;
use Illuminate\Database\Seeder;

class GPESeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $file = fopen('database/seeders/data/data.csv', 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($line[0] == 'Date') {
                continue;
            }

            // skip impact factor fuck-ups
            if ($line[16] == '#N/A') {
                continue;
            }

            $gpes = explode(';', $line[7]);

            foreach ($gpes as $gpe) {
                if ($gpe != "") {
                    GeneProteinExpression::firstOrCreate(['name' => trim(ltrim($gpe))]);
                }
            }
        }
    }
}
