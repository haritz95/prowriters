<?php

namespace Database\Seeders\Dummy;

use App\Models\Business\Assignment;
use App\Models\Business\Unit;
use App\Models\Business\Urgency;
use Faker\Factory;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public $faker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = Factory::create();

        Assignment::unguard();

        $this->createAcademicAssignments();
        $this->createContentAssignments();
        $this->createResumeAssignments();
        $this->createGraphicsDesignAssignments();
        $this->createVideoEditingAssignments();
    }

    private function createAcademicAssignments()
    {
        $records = [
            'Essay',
            'UK Essay',
            'Coursework',
            'Term Paper',
            'Research Paper',
            'Case study',
            'Capstone Project',
            'Outline',
            'Report',
            'Book report',
            'Book review',
            'Movie review',
            'Research summary',
            'Thesis',
            'Discussion board post',
            'Lab report',
            'Annotated bibliography',
            'Reaction paper',
        ];

        $urgencies = Urgency::all();

        foreach ($records as $name) {
            $assignment = Assignment::create([
                'service_id'                  => 2,
                'name'                        => $name,
                'number_of_revisions_allowed' => 2,
            ]);

            $this->createUnit($urgencies, $assignment);
        }

    }

    private function createContentAssignments()
    {
        $records = [
            'Article',
            'Product Description',
            'Press Release',
            'Blog Post',
            'News Article',
            'Website Content',
            'Product review',
            'Facebook Post',
            'Twitter Post',
            'LinkedIn Post',
            'Email Newsletter',
            'White Paper',
        ];
        $urgencies = Urgency::all();

        foreach ($records as $name) {
            $assignment = Assignment::create([
                'service_id'                  => 1,
                'name'                        => $name,
                'number_of_revisions_allowed' => 2,
            ]);

            $this->createUnit($urgencies, $assignment);

        }

    }

    private function createUnit($urgencies, $assignment)
    {
        $start      = 100;
        $difference = 400;
        $price      = $this->faker->randomFloat(1, 15, 30);

        Unit::unguard();

        foreach ($urgencies as $urgency) {
            $end = $start + $difference;
            Unit::create([
                'name'          => $start . ' - ' . $end . ' ' . ' words',
                'quantity'      => $end,
                'assignment_id' => $assignment->id,
                'urgency_id'    => $urgency->id,
                'price'         => $price,
            ]);
            $start = $end;
            $price = $price + 20;
        }
    }
    private function createResumeAssignments()
    {
        $urgencies = Urgency::orderBy('id', 'DESC')->limit(3)->pluck('id')->toArray();
        sort($urgencies);

        $service_id = 3;

        $records = [
            [
                'service_id'                  => $service_id,
                'name'                        => 'Starter - Professional Growth',
                'price'                       => 149,
                'description'                 => 'An expertly written and keyword-optimized resume that sets you apart',
                'deliverables'                => '<ul><li>Professionally written - By experts that know your industry.</li><li>Formatted for success - Formatting that will get an employer\'s attention.</li><li>Keyword optimized - Your resume will be optimized to pass through Applicant Tracking Systems.</li></ul>',
                'author_level_id'             => 1,
                'author_payment_amount'       => 100,
                'urgency_id'                  => $urgencies[0],
                'number_of_revisions_allowed' => 2,

            ],
            [
                'service_id'                  => $service_id,
                'name'                        => 'Premium - Career Evolution',
                'price'                       => 219,
                'description'                 => 'Everything you need to apply to jobs, including a resume and cover letter.',
                'deliverables'                => '<ul><li>Professionally written - By experts that know your industry.</li><li>Formatted for success - Formatting that will get an employer\'s attention.</li><li>Keyword optimized - Optimized to pass through Applicant Tracking Systems.</li><li>Cover letter - Employers are 40% more likely to read a resume with a cover letter.</li><li>60-day interview guarantee.</li></ul>',
                'author_level_id'             => 2,
                'author_payment_amount'       => 180,
                'urgency_id'                  => $urgencies[1],
                'number_of_revisions_allowed' => 3,
            ],
            [
                'service_id'                  => $service_id,
                'name'                        => 'Ultimate - Executive Priority',
                'price'                       => 349,
                'description'                 => 'Resume, cover letter, and LinkedIn profile, created by an executive writer.',
                'deliverables'                => '<ul><li>Professionally written - By experts that know your industry.</li><li>Formatted for success - Formatting that will get an employer\'s attention.</li><li>Keyword optimized - Your resume will be optimized to pass through Applicant Tracking Systems.</li><li>Cover letter - Employers are 40% more likely to read a resume with a cover letter.</li><li>60-day interview guarantee.</li><li>LinkedIn Makeover - 97% of employers use LinkedIn; we\'ll rewrite your profile.</li></ul>',
                'author_level_id'             => 3,
                'author_payment_amount'       => 200,
                'urgency_id'                  => $urgencies[2],
                'number_of_revisions_allowed' => 4,
            ],

        ];

        foreach ($records as $record) {
            Assignment::create($record);
        }
    }

    private function createVideoEditingAssignments()
    {
        $urgencies = Urgency::orderBy('id', 'DESC')->limit(3)->pluck('id')->toArray();
        sort($urgencies);

        $service_id = 4;

        $records = [
            [
                'service_id'                  => $service_id,
                'name'                        => '15 - 30 minutes',
                'price'                       => 149,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 1,
                'author_payment_amount'       => 100,
                'urgency_id'                  => $urgencies[0],
                'number_of_revisions_allowed' => 2,

            ],
            [
                'service_id'                  => $service_id,
                'name'                        => '30 - 60 minutes',
                'price'                       => 219,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 2,
                'author_payment_amount'       => 180,
                'urgency_id'                  => $urgencies[1],
                'number_of_revisions_allowed' => 3,
            ],
            [
                'service_id'                  => $service_id,
                'name'                        => '1 Hour 30 Minutes - 2 Hours',
                'price'                       => 500,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 3,
                'author_payment_amount'       => 250,
                'urgency_id'                  => $urgencies[2],
                'number_of_revisions_allowed' => 4,
            ],

        ];

        foreach ($records as $record) {
            Assignment::create($record);
        }
    }

    private function createGraphicsDesignAssignments()
    {
        $urgencies = Urgency::orderBy('id', 'DESC')->limit(3)->pluck('id')->toArray();
        sort($urgencies);

        $service_id = 5;

        $records = [
            [
                'service_id'                  => $service_id,
                'name'                        => 'Logo',
                'price'                       => 149,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 1,
                'author_payment_amount'       => 100,
                'urgency_id'                  => $urgencies[0],
                'number_of_revisions_allowed' => 2,

            ],
            [
                'service_id'                  => $service_id,
                'name'                        => 'Brochure',
                'price'                       => 219,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 2,
                'author_payment_amount'       => 180,
                'urgency_id'                  => $urgencies[1],
                'number_of_revisions_allowed' => 3,
            ],
            [
                'service_id'                  => $service_id,
                'name'                        => 'Facebook Cover Image',
                'price'                       => 500,
                'description'                 => $this->faker->sentence,
                'deliverables'                => $this->faker->paragraph(3),
                'author_level_id'             => 3,
                'author_payment_amount'       => 250,
                'urgency_id'                  => $urgencies[2],
                'number_of_revisions_allowed' => 4,
            ],

        ];

        foreach ($records as $record) {
            Assignment::create($record);
        }
    }
}
