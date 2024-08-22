<?php

namespace App\Http\Controllers;

use App\Services\FilesSeederService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;

class SystemController extends Controller
{
    private $required_php_version = '8.2';

    const MULTIPLE_LANGUAGE = 'multiple_language';
    const SINGLE_LANGUAGE   = 'single_language';

    public function verify($code)
    {

        $personalToken = "IJtW338fGeSJG6xCiO9hd8smEMSLUOsd";
        $userAgent     = "Purchase code verification";

        // Surrounding whitespace can cause a 404 error, so trim it first
        $code = trim($code);

        // Make sure the code looks valid before sending it to Envato
        if (!preg_match("/^([a-f0-9]{8})-(([a-f0-9]{4})-){3}([a-f0-9]{12})$/i", $code)) {
            // die("Invalid code");
            return false;
        }

// Build the request
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => "https://api.envato.com/v3/market/author/sale?code={$code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 20,

            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$personalToken}",
                "User-Agent: {$userAgent}",
            ),
        ));

        // Send the request with warnings supressed
        $response = @curl_exec($ch);

        // Handle connection errors (such as an API outage)
        // You should show users an appropriate message asking to try again later
        if (curl_errno($ch) > 0) {
            die("Error connecting to API: " . curl_error($ch));
        }

        // If we reach this point in the code, we have a proper response!
        // Let's get the response code to check if the purchase code was found
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // HTTP 404 indicates that the purchase code doesn't exist
        if ($responseCode === 404) {
            //die("The purchase code was invalid");
            return false;
        }

        // Anything other than HTTP 200 indicates a request or API error
        // In this case, you should again ask the user to try again later
        if ($responseCode !== 200) {
            die("Failed to validate code due to an error: HTTP {$responseCode}");
        }

        // Parse the response into an object with warnings supressed
        $body = @json_decode($response);

        // Check for errors while decoding the response (PHP 5.3+)
        if ($body === false && json_last_error() !== JSON_ERROR_NONE) {
            die("Error parsing response");
        }
        return $body;

    }

    public function verifyPurchaseCode($code = '')
    {
        $response = $this->verify($code);

        if (isset($response->buyer) && $response->buyer) {
            return true;
        } else {
            return false;
        }
    }

    private function getRequiredExtensions(): array
    {
        return [
            'ctype'     => true,
            'curl'      => true,
            'dom'       => true,
            'fileinfo'  => true,
            'filter'    => true,
            'hash'      => true,
            'mbstring'  => true,
            'openssl'   => true,
            'pcre'      => true,
            'pdo'       => true,
            'session'   => true,
            'tokenizer' => true,
            'xml'       => true,
            'bcmath'    => true,
            'json'      => true,
            'gd'        => true,
            'exif'      => true,
        ];
    }
    private function checkFolderPermissions(): array
    {
        $folders = [
            'storage/framework/' => storage_path() . '/framework',
            'storage/logs/'      => storage_path() . '/logs',
            'bootstrap/cache/'   => base_path() . '/bootstrap/cache/',
        ];

        $i = 0;
        foreach ($folders as $folder => $full_path) {
            $data[$i]['is_set'] = (is_dir($full_path) && is_writable($full_path)) ? TRUE : FALSE;

            $data[$i]['folder'] = $folder;
            $i++;
        }

        $data = array_merge($data, (new FilesSeederService())->checkFolderPermission());

        return $data;
    }

    private function checkRequirements(): array
    {

        // die(phpversion("filter"));
        //die(extension_loaded("session"));
        $reqList = $this->getRequiredExtensions();

        $requirements['php'] = (version_compare(PHP_VERSION, $this->required_php_version) >= 0);

        foreach ($reqList as $extension => $row) {
            if ($extension == 'pdo') {
                $requirements[$extension] = defined('PDO::ATTR_DRIVER_NAME');
            } else {
                $requirements[$extension] = extension_loaded($extension);
            }
        }

        // $requirements['mod_rewrite'] = null;

        // if (function_exists('apache_get_modules')) {
        //     $requirements['mod_rewrite'] = in_array('mod_rewrite', apache_get_modules());
        // }

        return $requirements;

    }

    private function findMissingRequirements($requirement_statuses, $folder_permissions, $is_symlink_enabled)
    {
        $err                 = 0;
        $required_extensions = $this->getRequiredExtensions();
        // Finding Errors
        foreach ($required_extensions as $extension_name => $value) {
            if (!$requirement_statuses[$extension_name]) {
                $err++;
            }
        }
        foreach ($folder_permissions as $row) {
            if (!($row['is_set'] == 1)) {
                $err++;
            }
        }

        if (!($is_symlink_enabled == TRUE)) {
            $err++;
        }

        if (!$requirement_statuses['php']) {
            $err++;
        }

        return $err;
    }

    private function createDirectoryAndSymlink()
    {
        $is_symlink_enabled = FALSE;

        try {
            umask(0);

            // Delete if it already exists
            File::deleteDirectory(public_path() . '/storage');            

            if (!Storage::exists('public')) {
                //Storage::makeDirectory('public');
                mkdir(storage_path('app/public'), 0755, true);
            }

            // Create symlink
            \Artisan::call("storage:link");

           
            if (File::exists(public_path('hot'))) {
                File::delete(public_path('hot'));
            }
            

            if (is_dir(public_path() . '/storage')) {
                $is_symlink_enabled = TRUE;
            }

            // mkdir(storage_path('app/public/photos'), 0755, true);

        } catch (\Exception $e) {
            $is_symlink_enabled = FALSE;
        }
        return $is_symlink_enabled;
    }

    public function index()
    {

        if (env('ENABLE_APP_SETUP_CONFIG') == TRUE) {
            return redirect()->route('login');
        }

        try {
            $data['is_symlink_enabled']   = $this->createDirectoryAndSymlink();
            (new FilesSeederService())->generate();
        } catch (\Exception $e) {
            die("Could not copy files due to file permission issue on your server.");
        }

        $data['required_php_version'] = $this->required_php_version;
        

        $data['requirement_statuses'] = $this->checkRequirements();
        $data['folder_permissions']   = $this->checkFolderPermissions();

        $data['number_of_errors'] = $this->findMissingRequirements($data['requirement_statuses'], $data['folder_permissions'], $data['is_symlink_enabled']);

        return view('installer.requirements', compact('data'))->with('rec', "");
    }

    public function database_information(Request $request)
    {
        $data = [
            'language_types' => [
                // self::MULTIPLE_LANGUAGE => 'Multiple Language',
                self::SINGLE_LANGUAGE => 'Single Language',
            ],
        ];
        return view('installer.db', compact('data'));
    }

    public function run_page()
    {
        return view('installer.run');
    }

    public function setup_database(Request $request)
    {

        ini_set('max_execution_time', 0); //300 seconds = 5 minutes
        ini_set('memory_limit', '-1');

        $success = false;

        try {

            $this->initial_env_setup();

            Artisan::call("cache:clear");
            \Artisan::call("config:clear");
            \Artisan::call("view:clear");
            \Artisan::call('db:wipe');
            \Artisan::call('migrate');
            \Artisan::call('db:seed');
            // \Artisan::call("db:seed --class=UsersSeeder");

            $this->finalize_env_setup();

            // \Artisan::call("route:cache");
            // \Artisan::call("config:cache");

            $data['status'] = 1;
            $data['title']  = 'Successfully Installed';
            $data['icon']   = 'fa-check-circle';
            $data['msg']    = 'Use the following credential to login';

            chmod(storage_path('app/public/photos/1'), 0755);

            $success = true;
        } catch (\Exception $e) {

            $filename = 'installation_error_log.txt';
            $url      = route('download_error_log', $filename);

            $data['status'] = 2;
            $data['title']  = 'Installation Failed!';
            $data['icon']   = 'fa-exclamation-triangle';
            $data['msg']    = "A problem occurred during installation and was interrupted. You can download the error log here
            <a target='_blank' href='" . $url . "'>Error Log<a>";

            $success = false;
            $this->initial_env_setup();

            $log_message = $e->getMessage() . " \n \n Line: " . $e->getLine() . " " . $e->getFile();
            \Storage::put($filename, $log_message);
        }

        $request->session()->flash('installation_status', $data);
        return response()->json(['status' => $success]);
    }

    public function download($path)
    {
        return Storage::download($path);
    }

    public function installation_result()
    {
        $data = session('installation_status');

        if (!empty($data)) {
            return view('installer.result', compact('data'));
        } else {
            return redirect()->route('installer_page');
        }
    }

    public function installation_failed()
    {
        $data['status'] = 2;
        $data['title']  = 'Installation Failed!';
        $data['icon']   = 'fa-exclamation-triangle';
        $data['msg']    = "A problem occurred during installation and was interrupted";
        return view('installer.result', compact('data'));
    }

    public function db_connected(Request $request)
    {

        $referer = request()->headers->get('referer');

        if ($referer == route('run_installation_step_2')) {
            $data = [];

            return view('installer.db_connect_success', compact('data'));
        } else {
            return redirect()->route('installer_page');
        }
    }

    public function setup_database_connection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_base_url' => 'required',
            'db_host'       => 'required',
            'db_name'       => 'required',
            'db_user_name'  => 'required',
            'purchase_code' => 'required',

        ]);

        if (!$request->session()->get('purchase_code')) {
            $validator->after(function ($validator) use ($request) {
                if ($request->purchase_code) {
                    if (!$this->verifyPurchaseCode($request->purchase_code)) {
                        $validator->errors()->add(
                            'purchase_code', 'Not a valid purchase code'
                        );
                    }
                }
            });
        }

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $app_url  = $request->site_base_url;
        $host     = $request->input('db_host');
        $username = $request->input('db_user_name');
        $password = $request->input('db_user_password');
        $dbname   = $request->input('db_name');

        $request->session()->put('purchase_code', $request->purchase_code);

        $single_language = false;

        if ($request->input('language_type') == self::SINGLE_LANGUAGE) {
            $single_language = 'TRUE';
        }

        // Check if database connection is alright
        if ($conn = $this->check_db_connection($host, $dbname, $username, $password)) {
            if ($this->set_env($host, $dbname, $username, $password, $app_url, $single_language)) {
                return redirect()->route('db_connected');
            } else {
                $request->session()->flash('error_msg', 'There was a problem saving your database information and installation was interrupted');
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error_msg', 'Invalid Database Credential Provided');
            return redirect()->back();
        }
    }

    private function set_env($host, $dbname, $username, $password, $app_url, $single_language)
    {
        // If database connection is alright, update the ENV file.
        DotenvEditor::setKeys([
            [
                'key'   => 'APP_NAME',
                'value' => 'Microelephant',

            ],
            [
                'key'   => 'APP_ENV',
                'value' => 'development',

            ],
            [
                'key'   => 'APP_DEBUG',
                'value' => 'TRUE',

            ],
            [
                'key'   => 'ENABLE_APP_SETUP_CONFIG',
                'value' => 'FALSE',

            ],
            [
                'key'   => 'APP_URL',
                'value' => $app_url,

            ],
            [
                'key'   => 'VITE_APP_URL',
                'value' => $app_url,

            ],
            [
                'key'   => 'DB_HOST',
                'value' => $host,

            ],
            [
                'key'   => 'DB_DATABASE',
                'value' => $dbname,

            ],
            [
                'key'   => 'DB_USERNAME',
                'value' => $username,

            ],
            [
                'key'   => 'DB_PASSWORD',
                'value' => $password,

            ],
            [
                'key'   => 'SINGLE_LANGUAGE',
                'value' => $single_language,

            ],

        ]);

        DotenvEditor::save();

        return TRUE;
    }

    private function initial_env_setup()
    {
        // If database connection is alright, update the ENV file.
        DotenvEditor::setKeys([
            [
                'key'   => 'APP_DEBUG',
                'value' => 'TRUE',

            ],
            [
                'key'   => 'APP_ENV',
                'value' => 'development',

            ],
            [
                'key'   => 'ENABLE_APP_SETUP_CONFIG',
                'value' => 'FALSE',

            ],

        ]);

        DotenvEditor::save();

        return TRUE;
    }

    private function finalize_env_setup()
    {
        // If database connection is alright, update the ENV file.
        DotenvEditor::setKeys([
            [
                'key'   => 'APP_ENV',
                'value' => 'production',

            ],
            [
                'key'   => 'APP_DEBUG',
                'value' => 'FALSE',

            ],
            [
                'key'   => 'ENABLE_APP_SETUP_CONFIG',
                'value' => 'TRUE',

            ],

        ]);

        DotenvEditor::save();

        return TRUE;
    }

    private function check_db_connection($servername, $dbname, $username, $password)
    {
        try {

            $conn = new \PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            // set the PDO error mode to exception
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return TRUE;
        } catch (\PDOException $e) {
            return FALSE;
        }
    }
}
