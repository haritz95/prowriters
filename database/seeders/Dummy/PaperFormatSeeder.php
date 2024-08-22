<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\PaperFormat;
use Illuminate\Database\Seeder;

class PaperFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaperFormat::unguard();

        $records = [
            ['name' => 'MLA'],
            ['name' => 'APA'],
            ['name' => 'Chicago'],
            ['name' => 'Harvard'],
            ['name' => 'Turabian with footnotes'],

        ];

        foreach ($records as $row) {
            PaperFormat::create($row);
        }
    }
}
