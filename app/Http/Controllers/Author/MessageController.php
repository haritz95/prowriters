<?php

namespace App\Http\Controllers\Author;

use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Messages\MessageThread;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create(MessageThread $messageThread)
    {
        $config                        = Attachment::configForFrontEnd();
        $config['urls']['submit_form'] = route('author.messageThreads.messages.store', $messageThread->uuid);

        return inertia()->modal('Author/Messages/Reply', [
            'data' => [
                'config' => $config,
            ],
        ])->baseRoute('author.messageThreads.show', $messageThread->uuid);
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

        return redirect()->route('author.messageThreads.show', $messageThread->uuid)->withSuccess(__('Message sent successfully'));
    }

}
