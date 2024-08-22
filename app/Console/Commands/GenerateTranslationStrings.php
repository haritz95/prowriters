<?php

namespace App\Console\Commands;

use App\Models\Locale\LanguageLine;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class GenerateTranslationStrings extends Command
{
    private $target_language = 'ar';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    function getContent($file)
    {
        $file = resource_path('lang/' . $file . '.json');
        $jsonStrings = file_get_contents($file);
        return json_decode($jsonStrings, true);
    }

    function saveContent($records, $file)
    {
        $file = resource_path('lang/' . $file . '.json');
        $newJsonString = json_encode($records, JSON_UNESCAPED_UNICODE);
        file_put_contents($file, $newJsonString);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    // public function handle2()
    // {
    //     /*
    //     Create fr.json
    //     copy fr.json to fr_app.json 
    //     Copy fr_official to fr.json
    //     */

    //     //$this->importTranslationString();
    //     //1. Create fr.json
    //     \Artisan::call("translation:scan");
    //     $existingStrings = $this->getContent('fr');
    //     $records = array_merge(config('trans'), $existingStrings);
    //     $this->saveContent($records, 'fr');
    //     $this->fakeTranslateToFrench();

    //     // 2 copy fr.json to fr_app.json 
    //     $this->copyFrToApp();

    //     // 3 Copy fr_official to fr.json
    //     $this->copyFrOfficialToFr();
    // }

    public function handle()
    {
        //$this->copyValidationMessages();
        //\Artisan::call("translation:scan");       
        $this->importTranslationStringInDatabase();
    }


    private function whatever()
    {
        $jsonStrings = file_get_contents(resource_path('lang/' . $this->target_language . '.json'));
        $textsInArray = json_decode($jsonStrings, true, 512, JSON_UNESCAPED_UNICODE);
        $records = [];
        foreach ($textsInArray as $key => $value) {
            $records[$key] = $key;
        }
        $this->saveContent($records, $this->target_language . '_test');
    }

    private function importTranslationStringInDatabase()
    {
        $jsonStrings = file_get_contents(resource_path('lang/' . $this->target_language . '.json'));
        $textsInArray = json_decode($jsonStrings, true, 512, JSON_UNESCAPED_UNICODE);
        $strings = [];

        $texts = array_unique(array_keys($textsInArray));
        $texts = array_map('trim', $texts);

        foreach ($texts as $text) {
            $count = LanguageLine::where('text', $text)->get()->count();
            if ($count == 0) {
                LanguageLine::create(['text' => $text]);
            }
        }
    }


    // public function copyFrToApp()
    // {
    //     $content = $this->getContent('fr');
    //     $this->saveContent($content, 'fr_app');
    // }

    public function copyValidationMessages()
    {
        $data = File::getRequire(resource_path('lang/' . $this->target_language . '/auth.php'));
        $data += File::getRequire(resource_path('lang/' . $this->target_language . '/pagination.php'));
        $data += File::getRequire(resource_path('lang/' . $this->target_language . '/passwords.php'));
        $data += File::getRequire(resource_path('lang/' . $this->target_language . '/validation.php'));
        $data += File::getRequire(resource_path('lang/' . $this->target_language . '/validation-inline.php'));

        $this->saveContent($data, $this->target_language);
    }

    // function fakeTranslateToFrench()
    // {

    //     $records = $this->getContent('fr');

    //     $faker = \Faker\Factory::create('fr_FR');

    //     foreach ($records as $key => $value) {
    //         $MonthValue = $this->replaceMonths($key);
    //         if ($MonthValue) {
    //             $data[$key] = $MonthValue;
    //         } else {
    //             $numberOfWords = str_word_count($key);

    //             if ($numberOfWords > 1) {
    //                 $data[$key] = $faker->sentence($numberOfWords);
    //             } else {
    //                 $data[$key] = $faker->word();
    //             }
    //         }
    //     }
    //     //Save data
    //     $this->saveContent($data, 'fr');
    // }
    // function replaceMonths($text)
    // {
    //     $text = strtolower($text);

    //     $monthsBig = array_flip([
    //         'janvier' => 'january',
    //         'février' => 'february',
    //         'mars' => 'march',
    //         'avril' => 'april',
    //         'mai' => 'may',
    //         'juin' => 'june',
    //         'juillet' => 'july',
    //         'août' => 'august',
    //         'septembre' => 'september',
    //         'octobre' => 'october',
    //         'novembre' => 'november',
    //         'décembre' => 'december',


    //     ]);
    //     $monthsSmall = array_flip([
    //         'janvier' => 'jan',
    //         'février' => 'feb',
    //         'mars' => 'mar',
    //         'avril' => 'apr',
    //         'mai' => 'may',
    //         'juin' => 'jun',
    //         'juillet' => 'jul',
    //         'août' => 'aug',
    //         'septembre' => 'sep',
    //         'octobre' => 'oct',
    //         'novembre' => 'nov',
    //         'décembre' => 'dec',
    //     ]);

    //     if (isset($monthsBig[$text])) {
    //         return $monthsBig[$text];
    //     }
    //     if (isset($monthsSmall[$text])) {
    //         return $monthsSmall[$text];
    //     }
    //     return null;
    // }
}
