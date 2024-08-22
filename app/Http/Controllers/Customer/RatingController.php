<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProjectManagement\Task;
use App\Models\ProjectManagement\Rating;
use App\Models\ProjectManagement\TaskStatus;

class RatingController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Task $task)
    {
        if ($task->task_status_id != TaskStatus::COMPLETE) {
            return redirect()->route('customer.tasks.show', $task->uuid);
        }

        return inertia('Customer/Tasks/Ratings/Create', [
            'task' => $task,
            'data' => [
                'title' => __('Tell us about your experience'),
                'description' =>  __('We\'ve got a short survey that we\'d really appreciate you filling out. It\'s so we can know what we\'re doing well, and what we need to do better.'),
                'rating_description' => [
                    1 => 'I hated it',
                    2 => 'I didn\'t like it',
                    3 => 'It was okay',
                    4 => 'I liked it',
                    5 => 'I loved it',
                ]
            ]

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'number' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:500|min:10',
        ], [
            'number.required' => __('Please select a rating'),
            'number.integer' => __('Please select a rating'),
            'number.between' => __('Please select a rating'),
        ]);

        if ($task->task_status_id != TaskStatus::COMPLETE) {
            return redirect()->route('customer.tasks.show', $task->uuid);
        }
        
       
        $rating = new Rating();
        $rating->uuid = Str::uuid();
        $rating->task_id = $task->id;
        $rating->number = $request->number;
        $rating->comment = $request->comment;
        $rating->user_id = auth()->user()->id;       
        
        $rating->save();    
        return redirect()->route('customer.tasks.index')->withSuccess(__('Thank you for your feedback!'));
    }
}
