<?php

namespace App\Service;

use App\Entity\Data\Quiz;
use App\Entity\Data\QuizQuestion;
use App\Repository\Configuration\FactTypeRepository;
use App\Repository\Data\CountryFactRepository;
use App\Repository\Data\QuizQuestionRepository;
use App\Repository\Data\QuizRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Uid\Factory\UuidFactory;

class GeoQuestHelper
{
    private CountryFactRepository $countryFactRepository;
    private UuidFactory $uuidFactory;
    private TokenStorageInterface $storageInterface;
    private FactTypeRepository $factTypeRepository;
    private EntityManagerInterface $em;
    private QuizRepository $quizRepository;
    private QuizQuestionRepository $quizQuestionRepository;

    public function __construct(CountryFactRepository $countryFactRepository,
                                UuidFactory $uuidFactory,
                                TokenStorageInterface $storageInterface,
                                EntityManagerInterface $em,
                                FactTypeRepository $factTypeRepository,
                                QuizRepository $quizRepository,
                                QuizQuestionRepository $quizQuestionRepository
    ){

        $this->countryFactRepository = $countryFactRepository;
        $this->uuidFactory = $uuidFactory;
        $this->storageInterface = $storageInterface;
        $this->factTypeRepository = $factTypeRepository;
        $this->em = $em;
        $this->quizRepository = $quizRepository;
        $this->quizQuestionRepository = $quizQuestionRepository;
    }

    /**
     * @throws Exception
     */
    public function generateQuestionsSetByFactId($factId): array
    {
        $results = $this->countryFactRepository->getQuestionsForFactId($factId);
        $uuid = $this->uuidFactory->create();

        $userQuiz = new Quiz();
        $userQuiz->setQuizOwner($this->storageInterface->getToken()->getUser());
        $userQuiz->setIdentifier($uuid);
        $userQuiz->setTimeStarted(new \DateTime());
        $userQuiz->setQuizType($this->factTypeRepository->findOneBy(['id'=>$factId]));
        $this->em->persist($userQuiz);
        $this->em->flush();

        $questions = [];
        $i = 0;
        foreach ($results as $result) {
            $options = $this->countryFactRepository
                ->getMultipleChoiceOptions($result['answer_id']);
            $options[] = ['id'=>$result['answer_id'], 'name'=>$result['answer_label']];
            shuffle($options);
            $questions[$i]['options'] = $options;
            $questions[$i]['contentType'] = $result['content_type'];
            $questions[$i]['id'] = $result['id'];
            $questions[$i]['factType'] = $result['fact_type'];
            $questions[$i]['quizIdentifier'] = $uuid;
            if ( $result['content_type'] == 'Image (URL)') {
                $questions[$i]['imgURL'] = $result['content'];
            }
            $questions[$i]['prompt'] = $this->getQuestionPrompt($result['prompt'],
                $result['content']);

            $question = new QuizQuestion();
            $question->setCountryFact($this->countryFactRepository->findOneBy(['id'=>$result['id']]));
            $question->setOptions($questions[$i]['options']);
            if(isset($questions[$i]['imgURL']))
                $question->setImageURL($questions[$i]['imgURL']);
            $question->setPrompt($questions[$i]['prompt']);
            $question->setQuiz($userQuiz);
            $this->em->persist($question);
            $this->em->flush();

            $i++;
        }
        return $questions;
    }

    public function getQuestionPrompt($prompt, $content): string
    {
        return sprintf($prompt, $content);
    }


    /**
     * @throws Exception
     */
    public function evaluateCurrentAnswer($userAnswer): array
    {
        $answer = $this->countryFactRepository
            ->getCountryIdByFactId($userAnswer['id']);

        $countryFact = $this->countryFactRepository->findOneBy(['id' => $userAnswer['id']]);
        $quiz = $this->quizRepository->findOneBy(['identifier'=>$userAnswer['quizIdentifier']]);
        $question = $this->quizQuestionRepository->findOneBy(['quiz'=>$quiz,'countryFact'=>$countryFact]);

        if($answer['id'] === $userAnswer['answerId']) {
            $response = ['status'=>'correct', 'description'=>'Congratulations you got this one right !'];
            $question->setIsCorrect(true);
        } else {
            $response = ['status'=>'wrong',
                'description'=> sprintf('Sorry you got this one wrong, the correct answer is %s', $answer['text'])
            ];
            $question->setIsCorrect(false);
        }

        $question->setTimeCompleted(new \DateTime());
        $this->em->persist($question);
        $this->em->flush();


        $response['totalScore'] = $quiz->computeTotalScore();
        $response['remainingQuestions'] = $quiz->getRemainingQuestions();
        return $response;
    }
}