<?php

namespace Database\Seeders;

use App\Models\NBAI\Journal;
use App\Models\NBAI\ResearchPaper;
use Brick\Math\BigDecimal;
use Illuminate\Database\Seeder;

class ResearchPaperSeeder extends Seeder
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

            $journal = Journal::updateOrCreate(
                ['name' => $line[15]],
                ['impact_factor' => BigDecimal::of($line[16])]
            );

            ResearchPaper::updateOrCreate(
                [
                    'title' => ltrim(trim($line[19])),
                    'journal_id' => $journal->id,
                    'year' => $line[18],
                ],
                [
                    'author_name' => $line[20],
                    'link' => $line[1],
                    'cited_by' => $line[17],
                    'years_out' => $line[21]
                ]
            );
        }
    }
}
