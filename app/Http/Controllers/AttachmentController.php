<?php
namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

    public function upload(Request $request)
    {
        $attachment = Storage::putFile('public/attachments', $request->file('file'));

        return response()->json([
            'name'         => $attachment,
            'display_name' => $request->file->getClientOriginalName(),
            'size'         => $request->file->getSize(),
            'url'          => Storage::url($attachment),
        ], 200);
    }

    public function remove(Attachment $attachment)
    {
        if (Storage::exists($attachment->name)) {
            Storage::delete($attachment->name);
        }
        return redirect()->back();
    }

    public function download(Attachment $attachment)
    {
        try {
            return Storage::download($attachment->name, $attachment->display_name);
        } catch (\Exception$e) {
            //
            abort(404);
        }
    }
}
