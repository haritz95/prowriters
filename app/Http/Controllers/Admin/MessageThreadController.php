<?php

namespace App\Http\Controllers\Admin;

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
        return inertia('Admin/Messages/Index', [
            'data'    => [
                'title' => __('Message Threads'),
            ],
            'filters' => $request->only('filters'),
            'threads' => MessageThread::forUserWithNewMessages(auth()->user()->id)
                ->latest('updated_at')
                ->with([
                    'sender' => function ($q) {
                        $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                    }, 'recipient' => function ($q) {
                        $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                    },
                ])
                ->paginate(config('app.pagination.per_page'))->withQueryString(),

        ]);
    }

    public function create()
    {
        return inertia('Admin/Messages/Create', [
            'data' => [
                'title'      => __('Compose Message'),
                'config'     => Attachment::configForFrontEnd(),
                'recipients' => [],
            ],

        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_id' => 'required',
            'participants' => 'nullable|array',
            'subject'      => 'required|max:192',
            'body'         => 'required',
            'files'        => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {

            $thread = MessageThread::create([
                'subject'      => $data['subject'],
                'user_id'      => auth()->user()->id,
                'recipient_id' => $data['recipient_id'],
            ]);

            $message = $thread->messages()->create(['body' => $data['body'], 'user_id' => auth()->user()->id]);

            if ($data['files']) {
                (app()->makeWith('App\Services\AttachmentService', [
                    'model'       => $message,
                    'attachments' => $data['files'],
                    'userId'      => auth()->user()->id,
                ]))->save();
            }

            if ($data['participants'] && is_array($data['participants'])) {
                $recipients_including_the_sender = $data['participants'];
            }

            $recipients_including_the_sender[] = $data['recipient_id'];
            $recipients_including_the_sender[] = auth()->user()->id;

            // Remove the duplicates
            $recipients_including_the_sender = array_unique($recipients_including_the_sender);

            foreach ($recipients_including_the_sender as $recipient) {
                $message_participants[] = new MessageParticipant(['user_id' => $recipient]);
            }
            $thread->participants()->saveMany($message_participants);

            $all_recipients        = $recipients_including_the_sender;
            $key_of_sender_user_id = array_search(auth()->user()->id, $all_recipients);
            unset($all_recipients[$key_of_sender_user_id]);

            // Dispatching Event
            $users = User::whereIn('id', $all_recipients)->get();

            event(new NewMessageEvent($thread, $users));

            DB::commit();

            return redirect()->route('admin.messageThreads.index')->withSuccess(__('Successfully sent'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withFail(__('Could not perform the requested action'));

        }

    }

    public function show(MessageThread $messageThread)
    {
        $user_types = UserType::get();
        return inertia('Admin/Messages/Show', [
            'data'     => [
                'title'         => __('Messages'),
                'user_types'    => UserType::get(),
                'messageThread' => $messageThread->load([
                    'sender'            => function ($q) {
                        $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                    }, 'recipient' => function ($q) {
                        $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                    },
                    'participants.user' => function ($q) {
                        $q->select(['id', 'uuid', 'type', 'first_name', 'last_name', 'photo']);
                    },
                ]),
                'urls'          => [
                    'route_name_destroy'    => 'admin.messages.destroy',
                    'sender_profile_url'    => ($messageThread->sender->type == $user_types['admin']) ? route('admin.users.show', $messageThread->sender->uuid) : route('admin.authors.show', $messageThread->sender->uuid),
                    'recipient_profile_url' => ($messageThread->recipient->type == $user_types['admin']) ? route('admin.users.show', $messageThread->recipient->uuid) : route('admin.authors.show', $messageThread->recipient->uuid),
                ],
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

    public function edit(MessageThread $messageThread)
    {
        $messageThread->participants = $messageThread->participants()->whereNotIn('user_id', [$messageThread->user_id, $messageThread->recipient_id])->pluck('user_id');

        return inertia('Admin/Messages/Edit', [
            'data'            => [
                'title'        => __('Edit Thread') . ' ' . $messageThread->number,
                'participants' => User::whereIn('id', $messageThread->participants)->get(),
            ],
            'existing_record' => $messageThread,
        ]);
    }

    public function update(Request $request, MessageThread $messageThread)
    {
        $data = $request->validate([
            'participants' => 'nullable|array',
            'subject'      => 'required|max:192',
        ]);

        DB::beginTransaction();

        try {

            $data['participants'][] = $messageThread->user_id;
            $data['participants'][] = $messageThread->recipient_id;

            $messageThread->subject = $data['subject'];
            $messageThread->save();

            foreach ($data['participants'] as $participant) {
                $message_participants[] = new MessageParticipant(['user_id' => $participant]);
            }
            $messageThread->participants()->delete();
            $messageThread->participants()->saveMany($message_participants);

            DB::commit();

            return redirect()->route('admin.messageThreads.show', $messageThread->uuid)->withSuccess(__('Successfully updated'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withFail(__('Could not perform the requested action'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageThread $messageThread)
    {
        $redirect = redirect()->route('admin.messageThreads.index');
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
