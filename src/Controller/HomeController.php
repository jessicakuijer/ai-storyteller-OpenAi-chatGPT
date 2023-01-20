<?php

namespace App\Controller;

use App\Form\StoryType;
use App\Service\OpenAiService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, OpenAiService $openAiService): Response
    {
        $form = $this->createForm(StoryType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $json = $openAiService->getStory($data['story']);
            //si case cochée, on récupère une histoire alternative
            if ($data['alternativeStory']) {
                $json = $openAiService->getStory($data['story'], $data['alternativeStory']);
            }
            //sinon, on récupère une histoire classique
            else {
                $json = $openAiService->getStory($data['story']);
            }
            
            return $this->render('home/history.html.twig', [
                'json' => $json ?? null,
            ]);
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
