<?php

namespace App\Contracts;

use App\Http\Controllers\Controller;
use App\Models\Ai\AiApiResponse;
use App\Models\Ai\AiContent;
use App\Services\AiContentGenerateUseCases\UseCaseConfiguration;
use App\Services\AIContentGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AIContentGeneratorControllerContract extends Controller
{

    public function index(Request $request)
    {
        return inertia($this->getFilePath('Index'), [
            'data'            => [
                'title'     => __('AI Generated Contents'),
                'dropdowns' => AiContent::dropdown(),
                'urls'      => [
                    'generate_content' => route($this->getRoute('generate')),
                ],
                'routes'    => [
                    'index'   => $this->getRoute('index'),
                    'edit'    => $this->getRoute('edit'),
                    'destroy' => $this->getRoute('index'),
                ],
            ],
            'existing_record' => null,
            'filters'         => $request->only('filters'),
            'contents'        => AiContent::where('user_id', auth()->user()->id)
                ->when($request->filled('filters.search'), function ($q) use ($request) {
                    return $q->where(function ($subQuery) use ($request) {
                        return $subQuery->where('title', 'like', '%' . $request->filters['search'] . '%');
                    });
                })
                ->orderBy('id', 'DESC')
                ->paginate(config('app.pagination.per_page'))
                ->withQueryString(),
        ]);
    }

    public function create()
    {
        return inertia($this->getFilePath('Create'), [
            'data' => [
                'title'     => __('Create'),
                'dropdowns' => AiContent::dropdown(),
                'urls'      => [
                    'generate_content' => route($this->getRoute('generate')),
                    'store_content'    => route($this->getRoute('store')),
                    'index_content'    => route($this->getRoute('index')),
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->getValidationRules());

        $data['uuid']    = Str::orderedUuid();
        $data['user_id'] = auth()->user()->id;

        $content = AiContent::create($data);
        return redirect()->route($this->getRoute('edit'), $content->uuid)->withSuccess(__('app.created'));

    }

    public function edit(AiContent $aIGeneratedContent, UseCaseConfiguration $useCaseConfiguration)
    {
        return inertia($this->getFilePath('Create'), [
            'data' => [
                'title'           => __('Create'),
                'fields'          => $useCaseConfiguration->getAllFormFields(),
                'dropdowns'       => AiContent::dropdown(),
                'urls'            => [
                    'generate_content' => route($this->getRoute('generate'), $aIGeneratedContent->uuid),
                    'update_content'   => route($this->getRoute('update'), $aIGeneratedContent->uuid),
                    'index_content'    => route($this->getRoute('index')),
                ],

            ],
            'existing_record' => $aIGeneratedContent,
        ]);
    }

    public function update(Request $request, AiContent $aIGeneratedContent)
    {
        $data = $request->validate($this->getValidationRules());

        $aIGeneratedContent->title   = $data['title'];
        $aIGeneratedContent->content = $data['content'];
        $aIGeneratedContent->save();

        return redirect()->route($this->getRoute('edit'), $aIGeneratedContent->uuid)->withSuccess(__('app.updated'));

    }

    public function generateContent(Request $request, AiContent $aIGeneratedContent, UseCaseConfiguration $useCaseConfiguration, AIContentGeneratorService $contentGenerator)
    {
        $parameters  = $useCaseConfiguration->getValidationRulesByIdentifier($request->use_case_id);
        $user_inputs = $request->validate($parameters['rules'], [], $parameters['field_names']);

        $prompt_template = $useCaseConfiguration->getPromptByIdentifier($request->use_case_id);

        $response = $contentGenerator->getContent($user_inputs, $prompt_template);

        if ($response) {

            AiApiResponse::create([
                'user_id'           => auth()->user()->id,
                'prompt_tokens'     => $response['usage']->prompt_tokens,
                'completion_tokens' => $response['usage']->completion_tokens,
                'total_tokens'      => $response['usage']->total_tokens,
            ]);

            if (isset($aIGeneratedContent->title)) {
                // Update
                $aIGeneratedContent->content = $aIGeneratedContent->content . '<br>' . $response['content'];
                $aIGeneratedContent->save();
                $content = $aIGeneratedContent;
            } else {
                // Create new
                $content = AiContent::create([
                    'uuid'    => Str::orderedUuid(),
                    'user_id' => auth()->user()->id,
                    'title'   => __('Untitled'),
                    'content' => trim($response['content']),
                ]);
            }

            return redirect()->route($this->getRoute('edit'), $content->uuid);
        } else {
            return redirect()->back()->withFail(__('Could not generate the content'));
        }
    }

    public function destroy(AiContent $aIGeneratedContent)
    {
        $this->checkOwner($aIGeneratedContent);
        $aIGeneratedContent->delete();

        return redirect()->route($this->getRoute('index'))->withSuccess('app.deleted');

    }

    private function getValidationRules()
    {
        return [
            'title'   => 'required|max:192',
            'content' => 'nullable',
        ];
    }
    private function checkOwner(AiContent $aIGeneratedContent)
    {
        return ($this->isNotOwner($aIGeneratedContent)) ? abort(404) : false;
    }

    private function isNotOwner(AiContent $aIGeneratedContent)
    {
        return ($aIGeneratedContent->user_id != auth()->user()->id) ? true : false;
    }

    private function getFilePath($file)
    {
        return $this->getFolder() . '/AIContents/' . $file;
    }

    private function getRoute($slug)
    {
        return $this->getRoutePrefix() . '.aIGeneratedContents.' . $slug;
    }

    abstract protected function getFolder(): string;

    abstract protected function getRoutePrefix(): string;

    private function test()
    {
        $string_4 = '{"id":"cmpl-71MU20oAdFFKUSufYdVfuI2fwI0Ji","object":"text_completion","created":1680559110,"model":"text-davinci-003","choices":[{"text":"\n\nTopic: Benefits of Utilizing Software for Project Management \n\nOutline: \n1. Introduction: Overview of project management software and benefits they provide \n2. Automation: How software can automate repetitive tasks, streamline processes, and document progress \n3. Collaboration: How it helps to facilitate collaboration between teams and clients \n4. Saves Time: How software can help to save time by decreasing the amount of manual effort required to manage projects \n5. Transparency: How it enables both team members and clients to track progress in real-time \n6. Predictive Analytics: How software can provide performance indicators to help forecast future success or failure of projects \n7. Conclusion: Summary of how using project management software is ultimately beneficial for businesses","index":0,"logprobs":null,"finish_reason":"stop"}],"usage":{"prompt_tokens":32,"completion_tokens":160,"total_tokens":192}}';

        $data = json_decode($string_4);

        return [
            'content' => $data->choices[0]->text,
            'usage'   => $data->usage,
        ];

    }
}
