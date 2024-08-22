<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AvatarUploadService
{

    public function upload(Request $request, $user)
    {
        try {
            $attachment = $this->StoreBase64ToFile($request->input('file'));

            // Delete previous avatar
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $user->photo = $attachment;
            $user->save();
            return asset(Storage::url($attachment));
        } catch (\Exception$e) {
        }
    }

    public function StoreBase64ToFile($base64Data)
    {
        $image    = base64_decode($base64Data);
        $filename = uniqid() . '_' . time() . '.png';
        $path     = 'uploads/avatars/' . $filename;
        Storage::disk('public')->put($path, $image, 'public');

        return $path;
    }

    public function storeAvatarFromRemoteURL($url)
    {
        $contents = file_get_contents($url);

        $path = 'uploads/avatars/' . rand() . '.jpg';
        Storage::disk('public')->put($path, $contents, 'public');

        return $path;
    }
}
