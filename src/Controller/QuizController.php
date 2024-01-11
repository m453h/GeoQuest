<?php

namespace App\Controller;

use App\Entity\Configuration\FactType;
use App\Repository\Configuration\FactTypeRepository;
use App\Repository\Data\QuizRepository;
use Doctrine\DBAL\Exception;
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
            $session = $request->getSession();
            $session->set('quiz-type',$quest);
            $session->set('questionsList', null);
            $session->set('in-session', false);
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

    /**
     * @throws Exception
     */
    #[Route('/leaderboards', name: 'app_view_leaderboards')]
    public function getLeaderBoards(QuizRepository $quizRepository): Response
    {

        return $this->render('home/leaderboards.html.twig', [
            'controller_name' => 'HomeController',
            'leaderBoards'=>$quizRepository->getCurrentLeaderBoards(),
            'page_name' => 'Leaderboards'
        ]);
    }

}
