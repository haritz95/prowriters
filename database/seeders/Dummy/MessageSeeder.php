<?php

namespace Database\Seeders\Dummy;

use App\Models\Messages\MessageParticipant;
use App\Models\Messages\MessageThread;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{

    public $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    public function run()
    {
        $thread = $this->createMessageThread(1, 3);
        $this->createMessage($thread, 3);
        $this->createMessage($thread, 1);

        $thread = $this->createMessageThread(3, 1);
        $this->createMessage($thread, 1);
        $this->createMessage($thread, 3);
    }

    private function createMessageThread($user_id, $recipient_id)
    {
        $thread = MessageThread::create([
            'subject'      => $this->faker->sentence(),
            'user_id'      => $user_id,
            'recipient_id' => $recipient_id,
        ]);

        $thread->messages()->create(['body' => $this->generateMessage(), 'user_id' => $user_id]);

        // user 5 = editor

        foreach ([$user_id, $recipient_id, 5] as $recipient) {
            $message_participants[] = new MessageParticipant(['user_id' => $recipient]);
        }
        $thread->participants()->saveMany($message_participants);

        return $thread;
    }

    private function createMessage($messageThread, $user_id)
    {
        $message = $messageThread->messages()->create(['body' => $this->generateMessage(), 'user_id' => $user_id]);
        return $message;
    }

    private function generateMessage()
    {
        return implode('<br>', $this->faker->paragraphs(3));
    }

}
