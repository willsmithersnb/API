<?php

namespace Database\Seeders;

use App\Models\NBAI\CriticalQualityAttribute;
use Illuminate\Database\Seeder;

class CQASeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cqa_list = [
            'Activity',
            'Adherence',
            'Cytotoxicity',
            'Metabolism',
            'Morphology',
            'Proliferation',
            'Viability',
            'Gene/Protein Expression'
        ];
        foreach($cqa_list as $cqa){
            CriticalQualityAttribute::firstOrCreate(['name'=>$cqa]);
        }
    }
}
