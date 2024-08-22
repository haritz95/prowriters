<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\Service;
use App\Models\Author\Applicant;
use App\Models\Author\ApplicantStatus;
use App\Models\Author\EducationLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApplicantSeeder extends Seeder
{
    public $faker;

    public function __construct()
    {

        $this->faker = \Faker\Factory::create();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $number          = 20;
        $statuses        = ApplicantStatus::pluck('id')->toArray();
        $educationLevels = EducationLevel::pluck('id')->toArray();

        $services = Service::active()->limit(2)->with(['subjects' => function ($q) {
            $q->select(['subjects.id', 'name']);
        }])->orderBy('id', 'ASC')->get();

        foreach ($services as $service) {
            $service_list[]             = $service->id;
            $subject_list[$service->id] = $service->subjects->pluck('id')->toArray();
        }

        $applicants = [];

        for ($i = 1; $i <= $number; $i++) {
            $service_id = $this->faker->randomElement($service_list);
            $subjects = $this->faker->randomElements($subject_list[$service_id], 5);

            

            $applicants[] = [
                'uuid'                 => Str::orderedUuid(),
                'number'               => mt_rand(100000, 999999),
                'applicant_status_id'  => $this->faker->randomElement($statuses),
                'first_name'           => $this->faker->firstName,
                'last_name'            => $this->faker->lastName,
                'email'                => $this->faker->email,
                'phone'                => $this->faker->phoneNumber,
                'country_code'         => 'US',
                'timezone'             => 'America/New_York',
                'education_level_id'   => $this->faker->randomElement($educationLevels),
                'bio'                  => $this->faker->paragraph(4),
                'address'              => $this->faker->address,
                'city'                 => $this->faker->city,
                'state'                => $this->faker->state,
                'blog_url'             => $this->faker->url,
                'online_portfolio_url' => $this->faker->url,
                'linked_in_url'        => 'http://linkedin.com/' . $this->faker->userName,
                'years_of_experience'  => $this->faker->randomElement(range(1, 10)),
                'language_id_1'        => 1,
                'service_id_1'         => $service_id,
                'subject_id_1'         => $subjects[0] ?? null,
                'subject_id_2'         => $subjects[1] ?? null,
                'subject_id_3'         => $subjects[2] ?? null,
                'subject_id_4'         => $subjects[3] ?? null,
                'subject_id_5'         => $subjects[4] ?? null,
                'note'                 => $this->faker->sentence(4),
                'created_at'           => now(),

            ];

        }
        Applicant::insert($applicants);
    }
}
