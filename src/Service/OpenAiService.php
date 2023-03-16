<?php

namespace App\Service;

use Orhanerday\OpenAi\OpenAi;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class OpenAiService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getStory(string $story, string $type = 'history'): string
    {
        $openAiKey = $this->parameterBag->get('OPENAI_API_KEY');
        $openAi = new OpenAi($openAiKey);

        $prompt = match ($type) {
            'alternative' => 'Raconte moi une histoire pour enfants avec une leçon de vie à la fin et avec les éléments suivants: ' . $story,
            'scary' => 'Raconte moi une histoire très effrayante pour enfants avec les éléments suivants: ' . $story,
            default => 'Raconte moi une histoire pour enfants avec des rebondissements incroyables et avec les éléments suivants: ' . $story,
        };

        $complete = $openAi->completion([
            'model' => 'text-davinci-003',
            'prompt' => $prompt,
            'temperature' => 0,
            'max_tokens' => 3500,
            'frequency_penalty' => 0.5,
            'presence_penalty' => 0,
        ]);

        $json = json_decode($complete, true);

        if (isset($json['choices'][0]['text'])) {
            return $json['choices'][0]['text'];
        }

        return 'Une erreur est survenue. Je n\'ai pas pu vous aider et ne peux créer cette histoire pour l\'instant.';
    }
}
