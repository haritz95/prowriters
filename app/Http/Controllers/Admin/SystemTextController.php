<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Locale\LanguageLine;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Locale\LanguageTranslation;

class SystemTextController extends Controller
{

//     [We can\'t seem to find the page you\'re looking for] 
// [We\'ve got a short survey that we\'d really appreciate you filling out. It\'s so we can know what we\'re doing well, and what we need to do better.] 
// [You wallet doesn\'t have sufficient balance] 

// [You are not allowed to accept any task when you already have tasks in progress] => 
//     [You are not allowed to pay later] => 
//     [You are not allowed to use the coupon] => 
//     [You can choose the pay later option only when placing orders] => 
//     [You cannot delete the item as it is associated with one or multiple orders] => 
//     [You cannot delete the language as it is associated with one or multiple records] => 
//     [You cannot delete the service as it is associated with one or multiple orders] => 
//     [You cannot delete the service as it is associated with one or multiple posts] => 
//     [You cannot delete the service as it is associated with one or multiple records] => 
//     [You cannot delete the task] => 
//     [You cannot delete the urgency as it is associated with one or multiple orders] => 
//     [You cannot delete the user as he/she is associated with one or multiple entities] => 
//     [You cannot delete the user as he/she is associated with one or multiple records] => 
//     [You cannot delete the user as his/her wallet has more than zero balance] => 


// system_texts
// system_translations

    public function index()
    {
        return view('setup.website.translation.index');
    }

    public function datatable(Request $request, $language)
    {
        $subTable = LanguageTranslation::select(['translated_text', 'language_lines_id'])->where('locale', $language);
        $languageLine = LanguageLine::select('*')->leftJoinSub($subTable, 'a', function ($join) {
            $join->on('language_lines.id', '=', 'a.language_lines_id');
        });


        return Datatables::eloquent($languageLine)
            ->editColumn('text', function ($languageLine) {
                return view('setup.website.translation.list_row', compact('languageLine'))->render();
            })->rawColumns([
                'text',
            ])
            ->make(true);
    }

    public function create(Request $request)
    {
        $translation = new \stdClass();
        return view('setup.website.translation.create', compact('translation'));
    }

    public function store(Request $request, $language)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|unique:language_lines'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        LanguageLine::create($request->all());

        return redirect()->back()->withSuccess(__('Successfully created'));
    }

    public function updateTranslation(Request $request, $language)
    {
        LanguageTranslation::updateOrCreate([
            'language_lines_id' => $request->id,
            'locale' => $language
        ], ['translated_text' => $request->translation]);
    }


    public function exportToJson(Request $request, $language)
    {
        $file_name = resource_path('lang/' . $language . '.json');

        if (file_exists($file_name)) {
            File::delete($file_name);
        }

        File::put($file_name, "");

        $subTable = LanguageTranslation::select(['translated_text', 'language_lines_id'])->where('locale', $language);
        $languageLines = LanguageLine::select('*')->leftJoinSub($subTable, 'a', function ($join) {
            $join->on('language_lines.id', '=', 'a.language_lines_id');
        })->get();


        foreach ($languageLines as $row) {
            $records[$row->text] = $row->translated_text;
        }

        $newJsonString = json_encode($records, JSON_UNESCAPED_UNICODE);
        file_put_contents($file_name, $newJsonString);
    }
}
