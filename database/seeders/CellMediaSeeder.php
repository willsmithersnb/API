<?php

namespace Database\Seeders;

use App\Models\NBAI\CellMedia;
use Illuminate\Database\Seeder;

class CellMediaSeeder extends Seeder
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
            
            $medias = [
                trim(ltrim($line[26])),
                trim(ltrim($line[27])),
                trim(ltrim($line[28])),
            ];

            foreach($medias as $media){
                if($media != "" && $media != 'Restricted Access'){
                    CellMedia::firstOrCreate(['name'=>$media]);
                }
            }
        }
    }
}
