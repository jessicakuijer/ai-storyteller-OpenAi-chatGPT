<?php

namespace App\Service;

use Tectalic\OpenAi\Authentication;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Tectalic\OpenAi\Manager;
use Tectalic\OpenAi\Models\ChatCompletions\CreateRequest;
use Symfony\Component\HttpClient\Psr18Client;



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
        $httpClient = new Psr18Client();
        $openAiClient = Manager::build($httpClient, new Authentication($openAiKey));

        // On ajoute un point à la fin de l'histoire si il n'y en a pas.
        if (!preg_match('/[.!?]$/', $story)) {
            $story .= '.';
        }
        // ICI on peut ajouter des éléments à l'histoire en fonction du type de l'histoire.
        $prompt = match ($type) {
            'alternative' => 'Crée une très courte histoire de 3 paragraphes pour enfants qui intègre les éléments suivants et se termine par une leçon de vie importante : ' . $story,
            'scary' => 'Imagine une très courte histoire de 3 paragraphes effrayante adaptée aux enfants qui inclut ces éléments : ' . $story,
            default => 'Élabore une très courte histoire de 3 paragraphes captivante pour enfants qui incorpore les éléments suivants et présente des rebondissements surprenants : ' . $story,
        };

        $request = $openAiClient->chatCompletions()->create(
            new CreateRequest([
            'model' => 'gpt-4-1106-preview',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a great storyteller for kids.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
                'max_tokens' => 500,
                'frequency_penalty' => 0.3,
                'presence_penalty' => 0.5,
                'n' => 1,
                'stop' => null,
                'best_of' => 1
        ])
        )->toModel();
        
        if (
            isset($request->choices) &&
            isset($request->choices[0]) &&
            isset($request->choices[0]->message) &&
            isset($request->choices[0]->message->content)
        ) {
            $response = $request->choices[0]->message->content;
        } else {
            $response = "Une erreur est survenue dans la réponse d'OpenAI.";
        }

        return $response;
    }
}
