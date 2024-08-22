<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\GrammaticalPerson;
use Illuminate\Database\Seeder;

class GrammaticalPeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GrammaticalPerson::unguard();

        $records = [
            ['name' => 'First Person Singular (I)'],
            ['name' => 'First Person - Plural (we)'],
            ['name' => 'Second Person - Singular (You)'],
            ['name' => 'Second Person - Plural (You)'],
            ['name' => 'Third Person - Singular Masculine (he)'],
            ['name' => 'Third Person - Singular Feminine (she)'],
            ['name' => 'Third Person - Singular Neutral (it)'],
            ['name' => 'Third Person - Plural (they)'],
        ];

        foreach ($records as $row) {
            GrammaticalPerson::create($row);
        }
    }
}
