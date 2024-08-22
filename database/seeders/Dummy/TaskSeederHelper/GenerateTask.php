<?php

namespace Database\Seeders\Dummy\TaskSeederHelper;

use App\Enums\ServiceType;
use App\Models\Accounting\Invoice;
use App\Models\Business\AcademicLevel;
use App\Models\Business\AuthorLevel;
use App\Models\Business\GrammaticalPerson;
use App\Models\Business\Service;
use App\Models\ProjectManagement\SubmittedWork;
use App\Models\ProjectManagement\Task;
use App\Services\CartService;
use App\Services\PriceCalculatorService;
use App\Services\ProjectManagement\TaskCreateService;
use Database\Seeders\Dummy\TaskSeederHelper\StatusTrait;
use Illuminate\Http\Request;

class GenerateTask
{
    use StatusTrait;

    public $faker;

    private $admin_id               = 1;
    private $customer_id            = 2;
    private $author_id              = 3;
    private $attachment_target_path = 'public/attachments/';

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
        // // Delete the old files:
        // File::cleanDirectory(Storage::path($this->attachment_target_path));
    }

    public function createTaskForBidding()
    {
        $container = [
            [$this->academicWriting(), $this->academicWriting()],
            [$this->contentWriting(), $this->contentWriting()],
            [$this->academicWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->academicWriting()],
            [$this->academicWriting(), $this->academicWriting()],
            [$this->contentWriting(), $this->contentWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->academicWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
        ];

        foreach ($container as $boxes) {

            foreach ($boxes as $box) {
                //$item    = (new PriceCalculatorService)->calculate($box);
                $data                   = $box->all();
                $data['is_bid_request'] = true;
                unset($data['added_additional_services']);

                $tasks[] = (new TaskCreateService())($data);
            }

        }
        return $tasks;

    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function regular()
    {
        $container = [
            [$this->academicWriting(), $this->academicWriting()],
            [$this->contentWriting(), $this->contentWriting()],
            [$this->academicWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->academicWriting()],
            [$this->academicWriting(), $this->academicWriting()],
            [$this->contentWriting(), $this->contentWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->academicWriting()],
            [$this->contentWriting()],
            [$this->contentWriting()],
            [$this->fixedPrice(4)], //4 = Video Editing
            [$this->fixedPrice(5)], //4 = Video Editing
            [$this->fixedPrice(4)], //4 = Video Editing
            [$this->fixedPrice(5)], //4 = Video Editing
            [$this->contentWriting()],
            [$this->fixedPrice(4)], //4 = Video Editing
            [$this->fixedPrice(5)], //4 = Video Editing
        ];
        foreach ($container as $boxes) {
            $cartService = app()->make(CartService::class);
            foreach ($boxes as $box) {
                $service = $box['service'];
                unset($box['service']);
                $cartService->add((new PriceCalculatorService)->calculate($service, $box));
            }
            $this->execute($cartService);
        }

        $taskCollection = Task::inRandomOrder()
            ->whereBetween('created_at', [now()->firstOfMonth(), now()->endOfMonth()])
            ->select('id')->limit(14)->get()->chunk(2);

        $changeStatuses = $this->getCallableMethods();

        foreach ($taskCollection as $index => $tasks) {
            foreach ($tasks as $task) {
                $changeStatuses[$index]($task);
            }
        }
        return $container;
    }

    public function taskWithPreviousDate()
    {
        $number_of_tasks_each_month = 10;
        $number_of_previous_months  = 3;
        $number_of_tasks            = $number_of_tasks_each_month * $number_of_previous_months;

        for ($i = 1; $i <= $number_of_tasks; $i++) {
            $box         = $this->contentWriting();
            $cartService = app()->make(CartService::class);
            $service     = $box['service'];
            unset($box['service']);
            $cartService->add((new PriceCalculatorService)->calculate($service, $box));
            $invoice_ids[] = $this->execute($cartService);

        }

        $tasks = Task::whereIn('invoice_id', $invoice_ids)->get();

        foreach ($tasks as $task) {
            $this->setTask($task)
                ->assignAuthor()
                ->statusInProgress()
                ->statusSubmittedForQA()
                ->statusSubmittedForApproval()
                ->statusComplete();

        }

        $taskCollection = Task::whereIn('invoice_id', $invoice_ids)->with(['submittedWorks'])
            ->orderBy('id', 'ASC')->get()->chunk($number_of_tasks_each_month);

        $month            = 1;
        $day_of_the_month = 0;

        foreach ($taskCollection as $tasks) {
            $date = now()->subMonths($month)->firstOfMonth();
            $month++;

            foreach ($tasks as $task) {

                $new_date = $date->addDays($day_of_the_month)->toDateString();
                $day_of_the_month++;

                $task_update_query[] = [
                    'id'         => $task->id,
                    'created_at' => $new_date,
                ];
                $invoice_update_query[] = [
                    'id'         => $task->invoice_id,
                    'created_at' => $new_date,
                ];
                $submitted_work_update_query[] = [
                    'id'         => $task->submittedWorks->first()->id,
                    'created_at' => $new_date,
                ];

            }
        }

        foreach ($task_update_query as $row) {
            Task::where('id', $row['id'])->update([
                'created_at'               => $row['created_at'],
                'is_archived_for_admin'    => true,
                'is_archived_for_customer' => true,
                'is_archived_for_author'   => true,
            ]);
        }

        foreach ($invoice_update_query as $row) {
            Invoice::where('id', $row['id'])->update([
                'created_at'   => $row['created_at'],
                'invoice_date' => $row['created_at'],
            ]);
        }

        foreach ($invoice_update_query as $row) {
            SubmittedWork::where('id', $row['id'])->update(['created_at' => $row['created_at']]);
        }

        return $tasks;
    }

    public function contentWriting(): Request
    {

        $faker   = $this->faker;
        $service = Service::with(['additionalServices' => function ($q) use ($faker) {
            $q->inRandomOrder()->limit($faker->randomElement([1, 2]))->select('additional_services.id');
        },
            'subjects'                                     => function ($q) {
                $q->inRandomOrder()->limit(1)->select('subjects.id')->first();
            }, 'assignments' => function ($q) {
                $q->inRandomOrder()->limit(1)->first();
            }])->where('id', ServiceType::CONTENT_WRITING)->get()->first();

        // Additional Services
        $added_additional_services = [];
        foreach ($service->additionalServices as $additionalService) {
            $added_additional_services[] = ['id' => $additionalService->id, 'quantity' => 1];
        }
        $assignment = $service->assignments->first();
        $fields     = [
            'service'                               => $service,
            'customer_id'                           => $this->customer_id,
            'service_id'                            => $service->id,
            'added_additional_services'             => $added_additional_services,
            'title'                                 => $this->faker->sentence,
            'instruction'                           => implode('<br><br>', $this->faker->paragraphs(4)),
            'attachments'                           => $this->getAttachments(),
            'author_level_id'                       => AuthorLevel::inRandomOrder()->select('id')->first()->id,
            'assignment_id'                         => $assignment->id,
            'subject_id'                            => $service->subjects->first()->id,
            'unit_id'                               => $assignment->units()->inRandomOrder()->select('id')->first()->id,
            'language_id'                           => 1,
            'grammatical_person_id'                 => GrammaticalPerson::inRandomOrder()->select('id')->first()->id,
            'content_goals'                         => $this->faker->realText(100),
            'target_audience'                       => $this->faker->paragraph(),
            'target_keywords'                       => \implode(",", $this->faker->words(6)),
            'links_to_example_content'              => $this->faker->url,
            'style_and_tone'                        => $this->faker->paragraph(),
            'structure_and_formatting_requirements' => $this->faker->paragraph(),
            'referencing_and_linking_preferences'   => $this->faker->paragraph(),
            'things_to_avoid'                       => $this->faker->paragraph(),
            'additional_notes'                      => $this->faker->text,

        ];

        return new Request($fields);

    }

    public function academicWriting(): Request
    {
        $faker   = $this->faker;
        $service = Service::with(['additionalServices' => function ($q) use ($faker) {
            $q->inRandomOrder()->limit($faker->randomElement([1, 2]))->select('additional_services.id');
        },
            'subjects'                                     => function ($q) {
                $q->inRandomOrder()->limit(1)->select('subjects.id')->first();
            }, 'assignments' => function ($q) {
                $q->inRandomOrder()->limit(1)->first();
            }])->where('id', ServiceType::ACADEMIC_WRITING)->get()->first();

        // Additional Services
        $added_additional_services = [];
        foreach ($service->additionalServices as $additionalService) {
            $added_additional_services[] = ['id' => $additionalService->id, 'quantity' => 1];
        }

        $assignment = $service->assignments->first();

        $fields = [
            'service'                   => $service,
            'customer_id'               => $this->customer_id,
            'service_id'                => $service->id,
            'added_additional_services' => $added_additional_services,
            'title'                     => $this->faker->sentence,
            'instruction'               => implode('<br><br>', $this->faker->paragraphs(4)),
            'attachments'               => $this->getAttachments(),
            'author_level_id'           => AuthorLevel::inRandomOrder()->select('id')->first()->id,

            'assignment_id'     => $assignment->id,
            'subject_id'        => $service->subjects->first()->id,
            'academic_level_id' => AcademicLevel::inRandomOrder()->select('id')->first()->id,
            'unit_id'           => $assignment->units()->inRandomOrder()->select('id')->first()->id,

            'paper_format_id'   => 1,
            'number_of_sources' => 1,

        ];

        return new Request($fields);

    }

    public function fixedPrice($requested_service_id): Request
    {
        $faker   = $this->faker;
        $service = Service::with(['additionalServices' => function ($q) use ($faker) {
            $q->inRandomOrder()->limit($faker->randomElement([1, 2]))
                ->select('additional_services.id');
        }, 'assignments' => function ($q) {
            $q->inRandomOrder()->limit(1)->first();
        }])->where('id', $requested_service_id)->get()->first();

        // Additional Services
        $added_additional_services = [];
        foreach ($service->additionalServices as $additionalService) {
            $added_additional_services[] = ['id' => $additionalService->id, 'quantity' => 1];
        }

        $assignment = $service->assignments->first();

        $fields = [
            'service'                   => $service,
            'customer_id'               => $this->customer_id,
            'service_id'                => $service->id,
            'added_additional_services' => $added_additional_services,
            'title'                     => $this->faker->sentence,
            'instruction'               => implode('<br><br>', $this->faker->paragraphs(4)),
            'attachments'               => $this->getAttachments(),
            'author_level_id'           => $assignment->author_level_id,
            'assignment_id'             => $assignment->id,

        ];

        return new Request($fields);

    }

}
