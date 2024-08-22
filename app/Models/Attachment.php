<?php

namespace App\Models;

use App\Models\Business\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($attachment) {
            if (Storage::exists($attachment->name)) {
                Storage::delete($attachment->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    public function scopeSelectAll(Builder $query)
    {
        $query->select(['id', 'uuid', 'name', 'display_name', 'attachable_id', 'attachable_type']);
    }

    public static function configForFrontEnd()
    {
        return [
            'default_message'                   => '',
            'allowed_file_extensions'           => '.jpg,.png,.gif, .doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.pdf,.zip,.rar',
            'maximum_number_of_files_to_upload' => 5,
            'maximum_file_size'                 => 10,
            'existing_files'                    => [],
            'urls'                              => [
                'upload_attachment' => route('attachments.store'),
            ],
        ];
    }

    public static function configForCreateTask(Service $service)
    {
        $config                                      = self::configForFrontEnd();
        $config['allowed_file_extensions']           = $service->allowed_file_extensions;
        $config['maximum_file_size']                 = $service->maximum_file_size;
        $config['maximum_number_of_files_to_upload'] = $service->maximum_number_of_files_to_upload;
        $config['default_message']                   = $service->attachment_message;

        return $config;
    }

    public static function configForAuthorApplication()
    {
        $config                                      = self::configForFrontEnd();
        $config['allowed_file_extensions']           = '.doc,.docx,.pdf';
        $config['maximum_number_of_files_to_upload'] = 1;

        return $config;
    }

}
