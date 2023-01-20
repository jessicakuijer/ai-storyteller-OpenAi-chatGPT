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
            // Dans ce cas, la variable $type est initialisée à 'history' par défaut.
            //Cela permet de gérer les trois types d'histoires.
            $type = 'history';
            if ($data['alternativeStory']) {
                $type = 'alternative';
            } else if ($data['scaryStory']) {
                $type = 'scary';
            }
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
