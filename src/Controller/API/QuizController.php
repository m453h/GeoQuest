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
    #[Route('/generate-questions', name: 'api_generate_questions', options: ['expose' => true], methods: ['GET'])]
    public function generateQuestion(GeoQuestHelper $app, Request $request): Response
    {
        $session = $request->getSession();
        $quizType = $session->get('quiz-type');
        $results = [];
        if ($quizType instanceof FactType) {
            $results = $app->generateQuestionsSetByFactId($quizType->getId());
        }
        return new JsonResponse($results);
    }

    /**
     * @throws Exception
     */
    #[Route('/evaluate-answer', name: 'api_evaluate_answer', options: ['expose' => true], methods: ['POST'])]
    public function evaluateAnswer(GeoQuestHelper $app, Request $request): Response
    {

        $data = $request->getContent();

        return new JsonResponse(
            $app->evaluateCurrentAnswer(json_decode($data, true))
        );
    }

}
