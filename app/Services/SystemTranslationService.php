<?php

namespace App\Services;

use App\Models\Locale\SystemText;
use Illuminate\Support\Arr;
use Symfony\Component\Finder\Finder;

class SystemTranslationService
{
    public function importSystemText()
    {
        $language_files = ['app', 'auth', 'pagination', 'passwords', 'validation'];

        $v = [];
        foreach ($language_files as $language_file) {
            $v = array_merge($v, Arr::dot(([$language_file => __($language_file)])));
        }

        $system_texts = array_merge($this->findKeysInFiles(), $v);

        foreach ($system_texts as $key => $text) {
            $text = ($text == '' || empty($text)) ? $key : $text;

            $system_texts = SystemText::where('key', $key)->get();

            if ($system_texts->count() > 0) {
                $system_text       = $system_texts->first();
                $system_text->text = $text;
                $system_text->save();
            } else {
                SystemText::create([
                    'key'  => $key,
                    'text' => $text,
                ]);
            }

            //$data[]  = ['key' => $key, 'text' => $text];
            //$texts[] = ['text' => $text];
        }
        //SystemText::upsert($data, ['key'], ['text']);

    }

    private function findKeysInFiles(): array
    {
        $path = [
            app_path(),
            resource_path('js/components'),
            resource_path('views'),
            // database_path('seeds')
        ];
        $functions = ['__'];
        $pattern   =
        "[^\w|>]" . // Must not have an alphanum or _ or > before real method
        "(" . implode('|', $functions) . ")" . // Must start with one of the functions
        "\(" . // Match opening parenthese
        "[\'\"]" . // Match " or '
        "(" . // Start a new group to match:
        "([^\1)]+)+" . // this is the key/value
        ")" . // Close group
        "[\'\"]" . // Closing quote
        "[\),]"; // Close parentheses or new parameter
        $finder = new Finder();
        $finder->in($path)->exclude('storage')->name(['*.vue', '*.php'])->files();
        //$this->info('> ' . $finder->count() . ' vue & php files found');
        $keys = [];
        foreach ($finder as $file) {
            if (preg_match_all("/$pattern/siU", $file->getContents(), $matches)) {
                if (count($matches) < 2) {
                    continue;
                }
                //$this->info('>> ' . count($matches[2]) . ' keys found for ' . $file->getFilename());
                foreach ($matches[2] as $key) {
                    if (strlen($key) < 2) {
                        continue;
                    }
                    $keys[$key] = '';
                }
            }
        }
        uksort($keys, 'strnatcasecmp');

        return $keys;
    }
}
