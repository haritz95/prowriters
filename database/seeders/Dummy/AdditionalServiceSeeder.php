<?php

namespace Database\Seeders\Dummy;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Business\AdditionalService;


class AdditionalServiceSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		foreach ($this->getAdditionalServices() as $row) {
			$additionalService = AdditionalService::create($row);
			$this->attachServiceAndAdditionalService(2, $additionalService->id);
		}

		$this->attachServiceAndAdditionalService(1, 1);
		$this->attachServiceAndAdditionalService(1, 2);
		$this->attachServiceAndAdditionalService(1, 3);
		$this->attachServiceAndAdditionalService(1, 4);
		
		$this->attachServiceAndAdditionalService(4, 5);
		$this->attachServiceAndAdditionalService(4, 6);
		$this->attachServiceAndAdditionalService(4, 7);
	}

	private function attachServiceAndAdditionalService($service_id, $additional_service_id)
	{
		DB::table('service_additional_service')->insert([
			'service_id' => $service_id,
			'additional_service_id' => $additional_service_id,
		]);
	}

	private function getAdditionalServices()
	{
		return [
			[
				'type' => 'fixed',
				'name' => 'Plagiarism report',
				'description' => 'You will receive a detailed plagiarism report in PDF format.',
				'per_entered_quantity_label' => NULL,
				'price' => 29.99,
			],
			[
				'type' => 'percentage',
				'name' => 'I\'d like to get a draft',
				'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
				'per_entered_quantity_label' => NULL,
				'price' => 20,
			],
			// [
			// 	'type' => 'per_unit',
			// 	'name' => 'Proofread by editor',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => NULL,
			// 	'price' => 2.55,
			// ],
			[
				'type' => 'percentage',
				'name' => 'Proofread by editor',
				'description' => 'Proofread by an expert editor',
				'per_entered_quantity_label' => NULL,
				'price' => 30,
			],
			[
				'type' => 'per_entered_quantity',
				'name' => 'Royalty free images',
				'description' => 'Writer will use free stock images',
				'per_entered_quantity_label' => 'Quantity',
				'price' => 10,
			],
			[
				'type' => 'percentage',
				'name' => 'Color Correction',
				'description' => 'Color Correction & Grading',
				'per_entered_quantity_label' => NULL,
				'price' => 30,
			],
			[
				'type' => 'percentage',
				'name' => 'Audio Mixing',
				'description' => 'Audio Editing & Mixing',
				'per_entered_quantity_label' => NULL,
				'price' => 20,
			],
			[
				'type' => 'fixed',
				'name' => 'Visual Effects',
				'description' => 'Add Motion Graphics & Visual Effects',
				'per_entered_quantity_label' => NULL,
				'price' => 50,
			],
			// [
			// 	'type' => 'fixed',
			// 	'name' => 'Copy of sources',
			// 	'description' => 'You will get extracts from books or articles or direct links to the materials used in your paper.',
			// 	'per_entered_quantity_label' => NULL,
			// ],
			// [
			// 	'type' => 'fixed',
			// 	'name' => 'Add a title page',
			// 	'description' => 'A title page will be added',
			// 	'per_entered_quantity_label' => NULL,
			// ],
			// [
			// 	'type' => 'percentage',
			// 	'name' => '1-page summary',
			// 	'description' => '1-page summary of your paper to get the whole idea and present it to your instructor.',
			// 	'per_entered_quantity_label' => NULL,
			// ],

			// [
			// 	'type' => 'per_unit',
			// 	'name' => 'Proofread by editor',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => NULL,
			// ],
			// [
			// 	'type' => 'percentage',
			// 	'name' => 'I\'d like to get a draft',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => NULL,
			// ],
			// [
			// 	'type' => 'per_entered_quantity',
			// 	'name' => 'Example of Price based on quantity',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => NULL,
			// ],

			// [
			// 	'type' => 'fixed',
			// 	'name' => 'Thank You & Follow-up Letters',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => NULL,
			// ],

			// [
			// 	'type' => 'per_entered_quantity',
			// 	'name' => 'KSAs - Knowledge, Skills, Abilities',
			// 	'description' => 'Be first in line to get all of your questions or concerns addressed by first-class professionals.',
			// 	'per_entered_quantity_label' => 'Number of pages of KSA document',
			// ],

		];
	}
}
