<?php

namespace App\Controller;

use App\Entity\Configuration\FactType;
use App\Repository\Configuration\FactTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/app/quiz')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'app_quiz_home')]
    public function index(FactTypeRepository $factTypeRepository, Request $request): Response
    {

        $quest = $request->get('quest');
        $quizzes = null;

        if ($quest != null) {
            $quest = $factTypeRepository->findOneBy(['apiField'=>$quest]);
        } else {
            $quizzes = $factTypeRepository->findAll();
        }

        return $this->render('home/quiz.html.twig', [
            'controller_name' => 'HomeController',
            'quizzes'=>$quizzes,
            'quest'=>$quest,
            'page_name' => 'Home'
        ]);
    }

}
