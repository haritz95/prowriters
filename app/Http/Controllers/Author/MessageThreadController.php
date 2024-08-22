<?php

namespace App\Http\Controllers\Author;

use App\Enums\UserType;
use App\Events\NewMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\Messages\MessageParticipant;
use App\Models\Messages\MessageThread;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageThreadController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Author/Messages/Index', [
            'data' => [
                'title'   => __('Message Threads'),

            ],
            'filters' => $request->only('filters'),
            'threads' => MessageThread::forUserWithNewMessages(auth()->user()->id)->latest('updated_at')
                ->paginate(config('app.pagination.per_page'))->withQueryString(),

        ]);
    }

    public function create()
    {
        return inertia('Author/Messages/Create', [
            'data' => [
                'title'  => __('Compose Message'),
                'config' => Attachment::configForFrontEnd(),
            ],

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|max:192',
            'body'    => 'required',
            'files'   => 'nullable|array',
        ]);

        DB::beginTransaction();

        $sender_user_id = settings('default_receipt_id_for_incoming_messages');

        if (empty($sender_user_id)) {
            return redirect()->back()->withFail(__('Could not perform the requested action'));
        }

        try {

            $thread = MessageThread::create([
                'subject'      => $data['subject'],
                'user_id'      => auth()->user()->id,
                'recipient_id' => $sender_user_id,
            ]);

            $message = $thread->messages()->create(['body' => $data['body'], 'user_id' => auth()->user()->id]);

            if ($data['files']) {
                (app()->makeWith('App\Services\AttachmentService', [
                    'model'       => $message,
                    'attachments' => $data['files'],
                    'userId'      => auth()->user()->id,
                ]))->save();
            }

            $data['recipients'] = [$thread->recipient_id];

            $recipients_including_the_sender   = $data['recipients'];
            $recipients_including_the_sender[] = auth()->user()->id;

            foreach ($recipients_including_the_sender as $recipient) {
                $message_participants[] = new MessageParticipant(['user_id' => $recipient]);
            }
            $thread->participants()->saveMany($message_participants);

            DB::commit();

            // Dispatching Event
            $users = User::whereIn('id', $data['recipients'])->get();

            event(new NewMessageEvent($thread, $users));

            return redirect()->route('author.messageThreads.index')->withSuccess(__('Successfully sent'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withFail(__('Could not perform the requested action'));

        }

    }

    public function show(MessageThread $messageThread)
    {
        return inertia('Author/Messages/Show', [
            'data'     => [
                'title'         => __('Messages'),
                'user_types'    => UserType::get(),
                'urls'          => [
                    'route_name_destroy' => 'author.messageThreads.destroy',
                ],
                'messageThread' => $messageThread->load(['sender' => function ($q) {
                    $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                }, 'recipient' => function ($q) {
                    $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                }]),
            ],
            'messages' => $messageThread->messages()->with([
                'attachments' => function ($q) {
                    $q->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
                },
                'user'        => function ($q) {
                    $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'code', 'photo']);
                },
            ])->orderBy('id', 'DESC')->paginate(config('app.pagination.per_page')),

        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageThread $messageThread)
    {
        $redirect = redirect()->route('author.messageThreads.index');
        try {
            $messageThread->delete();
            $redirect->withSuccess(__('Successfully deleted'));
        } catch (\Illuminate\Database\QueryException $e) {
            $redirect->withFail(__('You cannot delete the item as it is associated with one or multiple records'));
        } catch (\Exception $e) {
            $redirect->withFail(__('Could not perform the requested action'));
        }

        return $redirect;
    }

}
