<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Locale\SystemText;
use App\Models\Locale\SystemTranslation;
use App\Services\SystemTranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SystemTranslationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $language, SystemTranslationService $systemTranslationService)
    {
        $subTable = SystemTranslation::select(['translated_text', 'system_text_id'])
            ->where('locale', $language);

        return inertia('Admin/Manage/SystemTranslations/Index', [
            'data'             => [
                'title' => __('Translation'),
            ],
            'filters'          => $request->only('filters'),
            'content_language' => $language,
            'texts'            => SystemText::select('*')
                ->leftJoinSub($subTable, 'a', function ($join) {
                    $join->on('system_texts.id', '=', 'a.system_text_id');
                })
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where('text', 'like', '%' . $request->filters['search'] . '%')
                        ->orWhere('key', 'like', '%' . $request->filters['search'] . '%');
                })
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $language)
    {
        $request->validate([
            'key'   => 'required',
            'value' => 'nullable',
        ]);

        $systemTexts = SystemText::where('key', $request->key)->get();

        if ($systemTexts->count() > 0) {
            $systemText = $systemTexts->first();
            SystemTranslation::updateOrCreate([
                'system_text_id' => $systemText->id,
                'locale'         => $language,
            ], [
                'translated_text' => trim($request->value),
            ]);

        }

    }

    public function applyTranslation($language)
    {
        if (!is_writable(lang_path())) {
            return redirect()->back()->withFail(__('Lang folder is not writeable'));
        }

        $texts = SystemTranslation::select(['translated_text', 'key'])->where('locale', $language)
            ->leftJoin('system_texts', 'system_translations.system_text_id', '=', 'system_texts.id')
            ->pluck('translated_text', 'key')->toArray();

        File::put(lang_path($language . '.json'), json_encode($texts, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        return redirect()->back()->withSuccess(__('Successfully created the translation file'));

    }

    public function importTranslationTexts($language, SystemTranslationService $systemTranslationService)
    {
        $systemTranslationService->importSystemText();
        return redirect()->back()->withSuccess(__('Successfully imported'));

    }

}
