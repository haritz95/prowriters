<?php

namespace App\Http\Controllers\Admin;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Messages\Message;
use App\Models\Messages\MessageThread;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create(MessageThread $messageThread)
    {
        $config                        = Attachment::configForFrontEnd();
        $config['urls']['submit_form'] = route('admin.messageThreads.messages.store', $messageThread->uuid);

        return inertia()->modal('Admin/Messages/Reply', [
            'data' => [
                'config' => $config,
            ],
        ])->baseRoute('admin.messageThreads.show', $messageThread->uuid);
    }

    public function store(Request $request, MessageThread $messageThread)
    {

        $data = $request->validate([
            'message' => 'required',
            'files'   => 'nullable|array',
        ]);

        $message = $messageThread->messages()->create(['body' => $data['message'], 'user_id' => auth()->user()->id]);

        if ($data['files']) {
            (app()->makeWith('App\Services\AttachmentService', [
                'model'       => $message,
                'attachments' => $data['files'],
                'userId'      => auth()->user()->id,
            ]))->save();
        }

        // Dispatching Event
        $users = User::whereIn('id', $messageThread->participants()->where('user_id', '<>', auth()->user()->id)->pluck('user_id'))->get();

        event(new NewMessageEvent($messageThread, $users));

        return redirect()->route('admin.messageThreads.show', $messageThread->uuid)->withSuccess(__('Message sent successfully'));
    }

    public function destroy(Message $message)
    {
        $thread = $message->thread;

        return $this->removeItem(function () use ($message) {
            $message->attachments()->delete();
            $message->delete();
        }, ['admin.messageThreads.show', $thread->uuid]);
    }

}
