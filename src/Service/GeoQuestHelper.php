<?php

namespace App\Service;

use App\Entity\Configuration\FactType;
use App\Repository\Data\CountryFactRepository;
use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\RequestStack;

class GeoQuestHelper
{
    private CountryFactRepository $countryFactRepository;
    private RequestStack $requestStack;

    public function __construct(CountryFactRepository $countryFactRepository, RequestStack $requestStack){

        $this->countryFactRepository = $countryFactRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * @throws Exception
     */
    public function generateQuestionsSetByFactId($factId)
    {
        $results = $this->countryFactRepository
            ->getQuestionForFactId($factId);
        $this->requestStack->getSession()->set('in-session', true);
        $i = 0;

        foreach ($results as $result) {
            $options = $this->countryFactRepository
                ->getMultipleChoiceOptions($result['answer_id']);
            $options[] = ['id'=>$result['answer_id'], 'name'=>$result['answer_label']];
            shuffle($options);
            $results[$i]['options'] = $options;
            $i++;
        }

        $this->requestStack->getSession()->set('questionsList', $results);
    }

    public function getNextQuestionInList(FactType $factType): array
    {
         $questions = $this->requestStack->getSession()->get('questionsList');
         $nextQuestion = array_pop($questions);
         $this->requestStack->getSession()->set('currentQuestion', $nextQuestion);
         $this->requestStack->getSession()->set('questionsList', $questions);

         if (isset($nextQuestion['content']))
            $nextQuestion['question'] = sprintf($factType->getQuestionPrompt(), $nextQuestion['content']);
         else
             $nextQuestion = [];

         return $nextQuestion;
    }

    public function evaluateCurrentAnswer($userAnswer): array
    {
        $currentQuestion = $this->requestStack->getSession()->get('currentQuestion');
        $totalAnsweredQuestions = 10 - count($this->requestStack->getSession()->get('questionsList'));
        $progress = ($totalAnsweredQuestions / 10) * 100;
        $progress .= '%';

        if ($currentQuestion['answer_id'] == $userAnswer) {
            $feedback = 'Correct';
            $remark = '';
        } else {
                $feedback = 'Wrong';
                $remark = sprintf('The correct answer is %s', $currentQuestion['answer_label']);
        }
        return [
            'feedback' => $feedback,
            'remark' => $remark,
            'progress' => $progress
        ];
    }
}