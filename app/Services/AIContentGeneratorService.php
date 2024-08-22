<?php
namespace App\Services;

use GuzzleHttp\Client;

class AIContentGeneratorService
{

    public function getContent(array $form_inputs, $prompt_template, $config = [])
    {        
        $open_ai_key = get_open_ai_api();

        $client      = new Client(['verify' => false]);
        $endpoint    = 'https://api.openai.com/v1/completions';

        $prompt = [
            'model'             => 'text-davinci-003',
            'prompt'            => $this->getPrompt($prompt_template['prompt'], $form_inputs),
            //'prompt' => 'Find grammatical mistake in "He have my car"',
            // 'prompt'            => 'Write two creative ads for the following product between 20 to 25 words in french:\n\nProduct: HP Laptop ProBook 4530s is laptop computer',
            'temperature'       => 0.9,
            'max_tokens'        => 400,
            'frequency_penalty' => 0.5,
            'presence_penalty'  => 0,
        ];
        
        try {
            $raw_response = $client->post($endpoint, [
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $open_ai_key,
                ],
                'body'    => json_encode($prompt),
            ]);

            $response = $raw_response->getBody()->getContents();
           
            if ($response) {

                $data = json_decode($response);

                if ($data->choices[0]->text) {
                    return [
                        'content' => trim($data->choices[0]->text),
                        'usage'   => $data->usage,
                    ];
                }
            }
        } catch (\Exception$e) {
           
        }

        return null;

    }

    private function getPrompt($prompt, $inputs)
    {       
        foreach ($inputs as $key => $name) {
            $prompt = str_replace(':' . $key, trim($name), $prompt);
        }
        return $prompt;
    }
}
