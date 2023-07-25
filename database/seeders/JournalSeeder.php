<?php

namespace Database\Seeders;

use App\Models\NBAI\Journal;
use Brick\Math\BigDecimal;
use Illuminate\Database\Seeder;

class JournalSeeder extends Seeder
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

            Journal::updateOrCreate(
                ['name' => trim(ltrim($line[15]))],
                ['impact_factor' => BigDecimal::of($line[16])]
            );
        }
    }
}
