<?php

namespace App\Jobs;


use App\Models\Orders\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\NewOrderIsWaitingForYou;

class NotifyWritersAboutNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $writers = DB::select('SELECT email, first_name, last_name FROM user_records
        INNER JOIN users ON users.id = user_records.user_id
        WHERE option_key = ? AND option_value = ?', ['writer_level', $this->order->work_level_id]);


        if (is_array($writers) && count($writers) > 0) {
            
            foreach($writers as $writer)
            {
                Notification::route('mail', [
                    $writer->email => $writer->first_name . ' '. $writer->last_name,
                ])->notify(new NewOrderIsWaitingForYou());
            }
            
        }

        
    }
}
