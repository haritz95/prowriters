<?php

namespace Database\Seeders\Dummy;

use Illuminate\Database\Seeder;
use App\Models\Business\Urgency;


class UrgenciesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Urgency::insert([
			// [
			// 	'name' => '3 Hours',
			// 	'value' => 3,
			// 	'type' => 'hours',
			// 	'value_for_author' => 2,
			// 	'type_for_author' => 'hours',
			// 	'percentage' => 50,
			// ],

			// [
			// 	'name' => '6 Hours',
			// 	'value' => 6,
			// 	'type' => 'hours',
			// 	'value_for_author' => 5,
			// 	'type_for_author' => 'hours',
			// 	'percentage' => 45,
			// ],

			// [
			// 	'name' => '12 Hours',
			// 	'value' => 12,
			// 	'type' => 'hours',
			// 	'value_for_author' => 11,
			// 	'type_for_author' => 'hours',
			// 	'percentage' => 40,
			// ],

			// [
			// 	'name' => '24 Hours',
			// 	'value' => 24,
			// 	'type' => 'hours',
			// 	'value_for_author' => 23,
			// 	'type_for_author' => 'hours',
			// 	'percentage' => 35,
			// ],

			[
				'name' => '2 Days',
				'value' => 2,
				'type' => 'days',
				'value_for_author' => 1,
				'type_for_author' => 'days',
				'percentage' => 32,
			],

			[
				'name' => '3 Days',
				'value' => 3,
				'type' => 'days',
				'value_for_author' => 2,
				'type_for_author' => 'days',
				'percentage' => 30,
			],

			[
				'name' => '4 Days',
				'value' => 4,
				'type' => 'days',
				'value_for_author' => 2,
				'type_for_author' => 'hours',
				'percentage' => 28,
			],

			[
				'name' => '5 Days',
				'value' => 5,
				'type' => 'days',
				'value_for_author' => 4,
				'type_for_author' => 'days',
				'percentage' => 26,
			],

			[
				'name' => '6 Days',
				'value' => 6,
				'type' => 'days',
				'value_for_author' => 5,
				'type_for_author' => 'days',
				'percentage' => 24,
			],

			[
				'name' => '7 Days',
				'value' => 7,
				'type' => 'days',
				'value_for_author' => 6,
				'type_for_author' => 'days',
				'percentage' => 22,
			],

			[
				'name' => '8 Days',
				'value' => 8,
				'type' => 'days',
				'value_for_author' => 7,
				'type_for_author' => 'days',
				'percentage' => 20,
			],

			[
				'name' => '9 Days',
				'value' => 9,
				'type' => 'days',
				'value_for_author' => 8,
				'type_for_author' => 'days',
				'percentage' => 18,
			],

			[
				'name' => '10 Days',
				'value' => 10,
				'type' => 'days',
				'value_for_author' => 8,
				'type_for_author' => 'days',
				'percentage' => 16,
			],

			[
				'name' => '14 Days',
				'value' => 14,
				'type' => 'days',
				'value_for_author' => 10,
				'type_for_author' => 'days',
				'percentage' => 14,
			],

			[
				'name' => '20 Days',
				'value' => 20,
				'type' => 'days',
				'value_for_author' => 15,
				'type_for_author' => 'days',
				'percentage' => 12,
			],
		]);
	}
}
