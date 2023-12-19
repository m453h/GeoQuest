<?php

namespace App\Controller\Quiz;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz_home')]
    public function index(): Response
    {
        return $this->render('quiz/index.html.twig', [
            'controller_name' => 'HomeController',
            'page_name' => 'Home'
        ]);
    }
}
