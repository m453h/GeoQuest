<?php

namespace App\Controller\API;

use App\Entity\Configuration\FactType;
use App\Service\GeoQuestHelper;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/quiz')]
class QuizController extends AbstractController
{
    /**
     * @throws Exception
     */
    #[Route('/generate-question', name: 'api_generate_question', options: ['expose' => true], methods: ['GET'])]
    public function generateQuestion(GeoQuestHelper $app, Request $request): Response
    {
        $session = $request->getSession();
        $quizType = $session->get('quiz-type');

        if ($quizType instanceof FactType && !$session->get('in-session')) {
            $app->generateQuestionsSetByFactId($quizType->getId());
        }

        return new JsonResponse($app->getNextQuestionInList($quizType));
    }

    #[Route('/evaluate-answer', name: 'api_evaluate_answer', options: ['expose' => true], methods: ['POST'])]
    public function evaluateAnswer(GeoQuestHelper $app, Request $request): Response
    {
        return new JsonResponse(
            $app->evaluateCurrentAnswer($request->get('answer'))
        );
    }

}
