<?php

namespace Database\Seeders\Dummy;

use Illuminate\Database\Seeder;
use App\Models\Business\Subject;
use Illuminate\Support\Facades\DB;

class SubjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->getSubjects() as $row) {
            foreach ($row['subjects'] as $subject) {
                $subject = Subject::create(['name' => $subject]);
                $this->attachServiceAndSubject($row['service_id'], $subject->id);
            }
        }
    }

    private function attachServiceAndSubject($service_id, $subject_id)
    {
        DB::table('service_subject')->insert([
            'service_id' => $service_id,
            'subject_id' => $subject_id,
        ]);
    }

    private function getSubjects()
    {
        return [
            [
                'service_id' => 1,
                'subjects' => $this->getAcademicSubjects()
            ],
            [
                'service_id' =>  2,
                'subjects' => $this->contentWritingSubjects()
            ]
        ];
    }

    private function getAcademicSubjects()
    {
        return [
            'Accounting',
            'Advertising',
            'Anthropology',
            'Agriculture & Food Science',
            'Art & Architecture',
            'Astronomy',
            'Aviation',
            'Biology & Life Sciences',
            'Business & Economics',
            'Chemistry',
            'Classic English Literature',
            'Communication',
            'Computer Science',
            'Criminal Law',
            'Culture & Ethnic Studies',
            'Design',
            'Economics',
            'Ecology',
            'Education',
            'Engineering',
            'English',
            'Environmental Studies',
            'Ethics',
            'Family and Consumer Science',
            'Film Studies',
            'Finance',
            'Financial Management',
            'Geology',
            'Geography',
            'Government',
            'Health Care',
            'History',
            'Homeland Security',
            'Human Resource Management',
            'Human Services',
            'Humanities',
            'Investments',
            'International law',
            'Journalism',
            'Law',
            'Leadership',
            'Linguistics',
            'Literature',
            'Logic',
            'Logistics',
            'Management',
            'Marketing',
            'Mathematics',
            'Medicine',
            'Mexican and Latin-American Studies',
            'Military Science',
            'Music',
            'Nursing',
            'Nutrition',
            'Pharmacology',
            'Philosophy',
            'Physics',
            'Poetry',
            'Political Science',
            'Programming',
            'Project Management',
            'Psychology',
            'Public Administration',
            'Public Health',
            'Religious Studies',
            'Risk Management',
            'Science',
            'Shakespeare studies',
            'Social Work',
            'Sociology',
            'Sports',
            'Statistics',
            'Supply Chain Management',
            'Technology',
            'Theater studies',
            'Theology',
            'Tourism',
            'Women and gender studies',
            'World affairs',
            'World literature',
        ];
    }


    private function contentWritingSubjects()
    {
        return [
            'Travel and Lifestyle',
            'Legal',
            'Food & Beverage',
            'Medical and Healthcare',
            'Fashion, Music and Entertainment',
            'Tech and Internet',
            'Governments and Non Profits',
            'Sports, Gaming and Fitness',
            'Finance, Business and Real Estate',
            'Education and Day Care',
            'Pet and Vet Business',
            'Environmental Services',
            'Art and Architecture',
            'Building and Hardware',
            'Automotive',
        ];
    }
}
