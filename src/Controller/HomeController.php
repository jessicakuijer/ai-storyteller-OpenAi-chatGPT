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
            // gestion de l'histoire alternative ou classique
            $type = $data['alternativeStory'] ? 'alternative' : 'history';
            $json = $openAiService->getStory($data['story'], $type);
            return $this->render('home/history.html.twig', [
                'json' => $json ?? null,
            ]);
        }
        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
