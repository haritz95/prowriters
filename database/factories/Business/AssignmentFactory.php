<?php

namespace Database\Factories\Business;

use App\Models\Business\AdditionalService;
use App\Models\Business\Assignment;
use App\Models\Business\Subject;
use App\Models\Business\Urgency;
use App\Models\Business\WorkLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssignmentFactory extends Factory
{
    
    protected $model = Assignment::class;    

    // public $faker;

    // function __construct()
    // {
    //     $this->faker = \Faker\Factory::create();
    // }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [            
            'name' => NULL,          
        ];
    }    
    

    public function writing($name)
    {
        return $this->state(function (array $attributes) use($name){
            return [ 
                'name' => $name,               
                'description' => $this->faker->text,
                'show_description' => FALSE,
                'minimum_order_quantity' => 275,       
                'has_subject' => TRUE,
                'has_work_level' => TRUE,
                'has_paper_format' => TRUE,
                'has_slide' => TRUE,
                'per_slide_price' => $this->faker->numberBetween(10, 20),
                'has_speaker_note_for_slide' => TRUE,
                'per_slide_speaker_note_price' => $this->faker->numberBetween(10, 20),
                'has_source' => TRUE, 
                
                'has_soft_copy_for_source' => TRUE, 
                'per_source_soft_copy_price' => $this->faker->numberBetween(10, 20),  
                'maximum_file_size' => 10,
                'allowed_file_extensions' => '.jpg,.png,.gif, .doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar',
            ];
        });
    }

    public function copyWriting($name)
    {
        return $this->state(function (array $attributes) use($name){
            return [ 
                'name' => $name,               
                'description' => $this->faker->text,
                'show_description' => FALSE,
                'minimum_order_quantity' => 275,       
                'has_subject' => FALSE,
                'has_work_level' => FALSE,
                'has_paper_format' => FALSE,
                'has_slide' => FALSE,
                'per_slide_price' => $this->faker->numberBetween(10, 20),
                'has_speaker_note_for_slide' => FALSE,
                'per_slide_speaker_note_price' => $this->faker->numberBetween(10, 20),
                'has_source' => FALSE, 
                
                'has_soft_copy_for_source' => FALSE, 
                'per_source_soft_copy_price' => $this->faker->numberBetween(10, 20), 
                'maximum_file_size' => NULL,
                'allowed_file_extensions' => NULL, 
            ];
        });
    }

    public function configure()
    {
        return $this->afterMaking(function (Assignment $assignment) {
            //
        })->afterCreating(function (Assignment $assignment) {
     
            $this->attachUrgency($assignment, '3 Hours');         
            $this->attachUrgency($assignment, '6 Hours');         
            $this->attachUrgency($assignment, '12 Hours');               
            $this->attachUrgency($assignment, '24 Hours');               
            $this->attachUrgency($assignment, '2 Days');       
            $this->attachUrgency($assignment, '3 Days');       
            $this->attachUrgency($assignment, '4 Days');       
            $this->attachUrgency($assignment, '5 Days');                 
            $this->attachUrgency($assignment, '7 Days');       

           
            if($assignment->has_subject)
            {       
                $this->attachSubject($assignment, 'Accounting');         
                $this->attachSubject($assignment, 'Advertising');         
                $this->attachSubject($assignment, 'Business & Economics');               
            }

            if($assignment->has_work_level)
            {                
                $this->attachWorkLevel($assignment, 'High School');
                $this->attachWorkLevel($assignment, 'Freshman');
                $this->attachWorkLevel($assignment, 'Sophomore');
                $this->attachWorkLevel($assignment, 'Junior');
                $this->attachWorkLevel($assignment, 'Senior');
                $this->attachWorkLevel($assignment, 'Masters');
                $this->attachWorkLevel($assignment, 'Doctoral');                
            }

            $this->attachAdditionalService($assignment, 'Plagiarism report');
            $this->attachAdditionalService($assignment, 'Copy of sources');
            $this->attachAdditionalService($assignment, '1-page summary');
            
        });
    }
    

    private function attachSubject($assignment, $name)
    {
        $object = Subject::where('name', $name)->get()->first();
        $assignment->subjects()->attach($object->id, ['price' => $this->faker->numberBetween(10, 20)]);
    }

    private function attachWorkLevel($assignment, $name)
    {
        $object = WorkLevel::where('name', $name)->get()->first();
        $assignment->workLevels()->attach($object->id, ['price' => $this->faker->numberBetween(10, 20)]);
    }

    private function attachUrgency($assignment, $name)
    {
        $object = Urgency::where('name', $name)->get()->first();
        $assignment->urgencies()->attach($object->id, ['price' => $this->faker->numberBetween(10, 20)]);
    
    }
    private function attachAdditionalService($assignment, $name)
    {
        $object = AdditionalService::where('name', $name)->get()->first();
        $assignment->additionalServices()->attach($object->id, ['price' => $this->faker->numberBetween(10, 20)]);
    }
    
   
}
