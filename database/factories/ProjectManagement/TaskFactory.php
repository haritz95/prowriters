<?php

namespace Database\Factories\ProjectManagement;

use App\Enums\UnitType;
use App\Enums\WorkType;
use App\Enums\ServiceType;
use App\Enums\SpacingType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\NumberGenerator;
use App\Models\Business\Subject;
use App\Models\Business\Urgency;
use App\Models\Business\WorkLevel;
use App\Models\Business\Assignment;
use App\Models\ProjectManagement\Task;
use App\Models\Business\AdditionalService;
use App\Models\ProjectManagement\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /*

    const NEW = 1;
    const IN_PROGRESS = 2;
    const SUBMITTED_FOR_APPROVAL = 3;
    const REQUESTED_FOR_REVISION = 4;
    const COMPLETE = 5;
    const ON_HOLD = 6;
    const CANCELED = 7;
    const SUBMITTED_FOR_QA = 8;
    const QA_REJECTED = 9;
     */
    // Academic Writing

    // Content Writing

    protected $model = Task::class;

    public $faker;

    private $customer_id = 2;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $fields = [
            'customer_id' => $this->customer_id,
            'project_id' => NULL,
            'service_id' => ServiceType::ACADEMIC_WRITING,
            'added_services' => [
                ['id' => 1, 'quantity' => 1]
            ],
            'title' => $this->faker->title,
            'instruction' => $this->faker->realText,
            'files_data' => [],
            'author_level_id' => 2,
            'service_level_id' => 2,
            'work_type_id' => WorkType::WRITING,
            'assignment_id' => 1,
            'subject_id' => 1,
            'academic_level_id' => 1,
            'urgency_id' => 1,
            'quantity' => 500,
            'paper_format_id' => 1,
            'unit_name' => UnitType::WORD,
            'spacing_type_id' => SpacingType::DOUBLE,
            'number_of_sources' => 1,

        ];
     
        $request = new Request($fields);


       

        $cartService = app()->make(CartService::class);
        $cartService->add((new PriceCalculatorService)->calculate($request));

        
        $token = $cartService->saveCart(CartType::ORDER, $this->customer_id);

        $paymentRecordService = app()->make('App\Services\PaymentRecordService');

        $userCart = $cartService->getSavedCart($token, $this->customer_id);


             // Record the Payment Information        
             $payment = $paymentRecordService->store($this->customer_id, 'stripe', $userCart->total, 'referenceNumber');

             // // Mark in the cart that payment has been made
             // $token = bin2hex(random_bytes(5));
             $cartService->markPaymentAsComplete($token, $payment->id);

             

             (new SavedCartProcessingService)->onlinePayment($userCart, User::find($this->customer_id));


       
        return [
            'uuid'       => Str::orderedUuid(),
            'number'     => NumberGenerator::gen(Task::class),
            'title'      => $this->faker->title,
            'project_id' => null,
            'task_status_id' => TaskStatus::NEW,
            'customer_id' => $this->customer_id,
            'service_id',
            'urgency_id' => $this->faker->randomElement(Urgency::get()->pluck('id')->toArray()),
            'language_id',
            'service_level_id',
            'author_level_id',
            'dead_line',
            'dead_line_for_author',
            'author_id',
            'update_via_sms',
            'revisions_allowed',
            'revisions_taken',
            'basic_price',
            'service_level_price',
            'additional_services_price',
            'is_total_overridden',
            'original_total',
            'total',
            'author_payment_amount',
            'user_id',
        ];

        /*

        Array
(
    [id] => 
    [customer_id] => 
    [project_id] => 
    [service_id] => 1
    [added_services] => Array
        (
            [0] => Array
                (
                    [id] => 1
                    [type] => fixed
                    [name] => Plagiarism report
                    [description] => You will receive a detailed plagiarism report in PDF format.
                    [price] => 29.990000
                    [per_entered_quantity_label] => 
                    [created_at] => 2022-10-13T06:19:31.000000Z
                    [updated_at] => 2022-10-13T06:19:31.000000Z
                    [pivot] => Array
                        (
                            [service_id] => 1
                            [additional_service_id] => 1
                        )

                    [calculated_price] => 29.990000
                )

        )

    [title] => SOme title
    [instruction] => SOme instruction
    [files_data] => Array
        (
            [0] => Array
                (
                    [name] => public/attachments/KuOQVD1s5f1ijzafcUaUxY1pGsq7bfGN5Eo8PseS.jpg
                    [display_name] => 79.jpg
                    [size] => 5270
                )

            [1] => Array
                (
                    [name] => public/attachments/RHTWYWkyooAi8JysD0EBtE0sv7nZmAUDj81j9pv6.jpg
                    [display_name] => 91.jpg
                    [size] => 4859
                )

        )

    [dead_line] => 11/2/2022, 1:59:37 PM
    [author_level_id] => 2
    [service_level_id] => 1
    [is_total_overridden] => 
    [updated_total] => 0
    [work_type_id] => writing
    [assignment_id] => 1
    [subject_id] => 1
    [academic_level_id] => 1
    [urgency_id] => 15
    [quantity] => 500
    [paper_format_id] => 1
    [unit_name] => word
    [spacing_type_id] => double
    [number_of_sources] => 0
)

        */

        // Result

        /*

        Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [type] => task
            [name] => Academic Writing
            [title] => SOme title
            [price] => 34.49
            [quantity] => 1
            [sub_total] => 34.49
            [fields] => Array
                (
                    [id] => 
                    [customer_id] => 
                    [project_id] => 
                    [service_id] => 1
                    [added_services] => Array
                        (
                            [0] => Array
                                (
                                    [id] => 1
                                    [type] => fixed
                                    [name] => Plagiarism report
                                    [description] => You will receive a detailed plagiarism report in PDF format.
                                    [price] => 29.990000
                                    [per_entered_quantity_label] => 
                                    [created_at] => 2022-10-13T06:19:31.000000Z
                                    [updated_at] => 2022-10-13T06:19:31.000000Z
                                    [pivot] => Array
                                        (
                                            [service_id] => 1
                                            [additional_service_id] => 1
                                        )

                                    [calculated_price] => 29.990000
                                )

                        )

                    [title] => SOme title
                    [instruction] => SOme instruction
                    [files_data] => Array
                        (
                        )

                    [dead_line] => 11/2/2022, 1:59:37 PM
                    [author_level_id] => 2
                    [service_level_id] => 1
                    [is_total_overridden] => 
                    [updated_total] => 0
                    [work_type_id] => writing
                    [assignment_id] => 1
                    [subject_id] => 1
                    [academic_level_id] => 1
                    [urgency_id] => 15
                    [quantity] => 500
                    [paper_format_id] => 1
                    [unit_name] => word
                    [spacing_type_id] => double
                    [number_of_sources] => 0
                    [per_word_price] => 0.008000
                    [number_of_words] => 500
                    [assignment_price] => 0
                    [academic_level_price] => 0.02
                    [subject_price] => 0
                    [urgency_price] => 0.48
                    [amount] => 4.5
                    [basic_price] => 4.5
                    [service_level_price] => 0.000000
                    [additional_services_price] => 29.99
                    [total] => 34.49
                    [additional_services] => Array
                        (
                            [0] => Array
                                (
                                    [additional_service_id] => 1
                                    [price] => 29.990000
                                    [quantity] => 1
                                )

                        )

                    [attachments] => Array
                        (
                        )

                )

        )

    [escapeWhenCastingToString:protected] => 
)

        */
    }

    public function academicWriting($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name'                         => $name,
                'description'                  => $this->faker->text,
                'show_description'             => false,
                'minimum_order_quantity'       => 275,
                'has_subject'                  => true,
                'has_work_level'               => true,
                'has_paper_format'             => true,
                'has_slide'                    => true,
                'per_slide_price'              => $this->faker->numberBetween(10, 20),
                'has_speaker_note_for_slide'   => true,
                'per_slide_speaker_note_price' => $this->faker->numberBetween(10, 20),
                'has_source'                   => true,

                'has_soft_copy_for_source'   => true,
                'per_source_soft_copy_price' => $this->faker->numberBetween(10, 20),
                'maximum_file_size'          => 10,
                'allowed_file_extensions'    => '.jpg,.png,.gif, .doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar',
            ];
        });
    }

    public function copyWriting($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name'                         => $name,
                'description'                  => $this->faker->text,
                'show_description'             => false,
                'minimum_order_quantity'       => 275,
                'has_subject'                  => false,
                'has_work_level'               => false,
                'has_paper_format'             => false,
                'has_slide'                    => false,
                'per_slide_price'              => $this->faker->numberBetween(10, 20),
                'has_speaker_note_for_slide'   => false,
                'per_slide_speaker_note_price' => $this->faker->numberBetween(10, 20),
                'has_source'                   => false,

                'has_soft_copy_for_source'   => false,
                'per_source_soft_copy_price' => $this->faker->numberBetween(10, 20),
                'maximum_file_size'          => null,
                'allowed_file_extensions'    => null,
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

            if ($assignment->has_subject) {
                $this->attachSubject($assignment, 'Accounting');
                $this->attachSubject($assignment, 'Advertising');
                $this->attachSubject($assignment, 'Business & Economics');
            }

            if ($assignment->has_work_level) {
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


/*

Array
(
    [id] => 
    [customer_id] => 
    [project_id] => 
    [service_id] => 2
    [added_services] => Array
        (
        )

    [title] => asd
    [instruction] => 
    [files_data] => Array
        (
        )

    [dead_line] => 11/3/2022, 6:33:33 PM
    [author_level_id] => 2
    [service_level_id] => 2
    [is_total_overridden] => 
    [updated_total] => 0
    [work_type_id] => writing
    [assignment_id] => 21
    [subject_id] => 81
    [academic_level_id] => 1
    [urgency_id] => 15
    [quantity] => 275
    [paper_format_id] => 1
    [unit_name] => word
    [spacing_type_id] => double
    [language_id] => 1
    [grammatical_person_id] => 1
    [content_goals] => asd
    [target_audience] => asd
    [target_keywords] => 
    [links_to_example_content] => 
    [style_and_tone] => 
    [structure_and_formatting_requirements] => 
    [referencing_and_linking_preferences] => 
    [things_to_avoid] => 
    [additional_notes] => 
)

// After calculation:

Illuminate\Support\Collection Object
(
    [items:protected] => Array
        (
            [type] => task
            [name] => Content Writing
            [title] => asd
            [price] => 8.45
            [quantity] => 1
            [sub_total] => 8.45
            [fields] => Array
                (
                    [id] => 
                    [customer_id] => 
                    [project_id] => 
                    [service_id] => 2
                    [added_services] => Array
                        (
                        )

                    [title] => asd
                    [instruction] => 
                    [files_data] => Array
                        (
                        )

                    [dead_line] => 11/3/2022, 6:33:33 PM
                    [author_level_id] => 2
                    [service_level_id] => 2
                    [is_total_overridden] => 
                    [updated_total] => 0
                    [work_type_id] => writing
                    [assignment_id] => 21
                    [subject_id] => 81
                    [academic_level_id] => 1
                    [urgency_id] => 15
                    [quantity] => 275
                    [paper_format_id] => 1
                    [unit_name] => word
                    [spacing_type_id] => double
                    [language_id] => 1
                    [grammatical_person_id] => 1
                    [content_goals] => asd
                    [target_audience] => asd
                    [target_keywords] => 
                    [links_to_example_content] => 
                    [style_and_tone] => 
                    [structure_and_formatting_requirements] => 
                    [referencing_and_linking_preferences] => 
                    [things_to_avoid] => 
                    [additional_notes] => 
                    [per_word_price] => 0.008000
                    [number_of_words] => 275
                    [assignment_price] => 0
                    [subject_price] => 0
                    [urgency_price] => 0.264
                    [language_price] => 0
                    [amount] => 2.464
                    [basic_price] => 2.46
                    [service_level_price] => 5.990000
                    [additional_services_price] => 0
                    [total] => 8.45
                    [additional_services] => Array
                        (
                        )

                    [attachments] => Array
                        (
                        )

                )

        )

    [escapeWhenCastingToString:protected] => 
)

*/