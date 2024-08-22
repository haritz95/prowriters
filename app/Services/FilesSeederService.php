<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class FilesSeederService
{
    function listFolders($dir)
    {
        $folders  = [];
        $contents = scandir($dir);
        foreach ($contents as $item) {
            if ($item != "." && $item != "..") {
                $path = $dir . '/' . $item;
                if (is_dir($path)) {
                    $folders[] = $path;
                    $folders   = array_merge($folders, $this->listFolders($path));
                }
            }
        }
        return $folders;
    }

    function checkFolderPermission()
    {
        $dir                = storage_path('app/public');
        $folders            = $this->listFolders($dir);
        $faulty_directories = [];
        foreach ($folders as $folder) {
            $permission = substr(sprintf('%o', fileperms($folder)), -4);

            if (!in_array($permission, ["0777", "0755"])) {
                $faulty_directories[] = ['is_set' => FALSE, 'folder' => $folder];
            }
        }
        return $faulty_directories;

    }

    function generate()
    {

        $this->service();

        $this->webpage();

        $this->copyLogo();

        $this->blog();

        $this->user();

        $this->testimonial();

        $this->homepageSection();

        $this->attachments();

    }

    private function service()
    {
        // Service Seeder
        //$this->copyFolder(storage_path('app/dummy-content/system'), storage_path('app/public/system'));
        $this->copyFolder(storage_path('app/dummy-content/system'), storage_path('app/public/system'));
    }

    private function webpage()
    {
        // WebsitePage Seeder
        $from = Storage::path('dummy-content/website/pages');
        $to   = Storage::path('public/photos/1/pages');
        $this->copyFolder($from, $to);
    }

    private function copyLogo()
    {
        $file = 'dummy-content/logo.png';
        $logo = 'public/photos/1/logo.png';

        if (Storage::exists($logo)) {
            Storage::delete($logo);
        }

        Storage::copy($file, $logo);

        $this->copyFolder(storage_path('app/dummy-content/thumbs'), storage_path('app/public/photos/1/thumbs/'));

        Storage::copy('dummy-content/we_accept.png', 'public/photos/1/we_accept.png');
    }

    private function blog()
    {
        $from = Storage::path('dummy-content/website/blog');
        $to   = Storage::path('public/photos/1/blog');
        $this->copyFolder($from, $to);
    }

    private function user()
    {
        $from = Storage::path('dummy-content/avatars');
        $to   = Storage::path('public/uploads/avatars');
        $this->copyFolder($from, $to);
    }

    private function testimonial()
    {
        $from = Storage::path('dummy-content/website/testimonial');
        $to   = Storage::path('public/photos/1/testimonial');
        $this->copyFolder($from, $to);
    }

    private function homepageSection()
    {
        $from = Storage::path('dummy-content/website/homepage');
        $to   = Storage::path('public/photos/1/homepage');
        $this->copyFolder($from, $to);
    }

    private function attachments()
    {       
        if (Storage::exists('public/attachments')) {
            File::deleteDirectory(storage_path('app/public/attachments'));
        }

        mkdir(storage_path('app/public/attachments'), 0755, true);
        $file = 'Dummy_attachment.txt';

        copy(storage_path('app/dummy-content/payment/' . $file), storage_path('app/public/attachments/' . $file));

    }

    function copyFolder($source, $destination)
    {
        // Check if source is a directory
        if (!is_dir($source)) {
            return false;
        }

        // Create destination directory if it doesn't exist
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        // Open source directory
        $dir = opendir($source);
        if (!$dir) {
            return false;
        }

        // Loop through files and subdirectories
        while (($file = readdir($dir)) !== false) {
            if ($file != '.' && $file != '..') {
                $sourcePath      = $source . '/' . $file;
                $destinationPath = $destination . '/' . $file;

                // Recursively copy subdirectories
                if (is_dir($sourcePath)) {
                    $this->copyFolder($sourcePath, $destinationPath);
                } else {
                    // Copy file
                    copy($sourcePath, $destinationPath);
                }
            }
        }

        // Close directory
        closedir($dir);

        return true;
    }

}
