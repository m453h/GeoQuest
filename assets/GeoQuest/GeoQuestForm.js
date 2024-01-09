import React, {Component} from 'react';
import GeoQuestQuizProgress from "./GeoQuestQuizPogress";
import PropTypes from "prop-types";

export default class GeoQuestForm extends Component {

    constructor(props) {
        super(props);
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
        this.handleNextButtonClick = this.handleNextButtonClick.bind(this);
        this.state = {
            errorMessage: ''
        };
    }

    handleFormSubmit (event) {
        event.preventDefault();
        const {selectedAnswerId, onAnswerSubmit, questions} = this.props;
        let currentQuestion = questions[0];

        if (selectedAnswerId === null){

            this.setState({
               errorMessage: "You must select an answer"
            });
            return;
        }

        if (selectedAnswerId){
            this.setState({
                errorMessage: ""
            });
            onAnswerSubmit(selectedAnswerId, currentQuestion);
        }
    }

    handleNextButtonClick (event) {
        event.preventDefault();
        const {onNextQuestionClick} = this.props;
        onNextQuestionClick();
    }

    render() {
        const {
            selectedAnswerId,
            onAnswerSelected,
            percentage,
            questions,
            isLoaded,
            canMoveToNextQuestion,
            answerEvaluationFeedback,
        } = this.props;
        const {errorMessage} = this.state;

        if(!isLoaded) {
            return (
                <div className="container mx-auto">
                    <div
                        id="dismiss-toast"
                        className="mt-5 max-w-sm bg-blue-100 border border-blue-200 text-sm text-blue-800 border-l-4 border-l-blue-500"
                        role="alert">
                        <div className="p-4">
                            Loading...
                        </div>
                    </div>
                </div>
            );
        }

        return (
            <div className="container mx-auto">
                <form id="quiz-form">

                    <GeoQuestQuizProgress
                        percentage={percentage}
                    />

                    <p className="text-2xl font-light mb-2" id="question-text">{questions[0].prompt}</p>
                    {
                        errorMessage &&
                            <div
                                id="dismiss-toast"
                                className="mt-5 max-w-sm bg-red-100 border border-red-200 text-sm text-red-800 border-l-4 border-l-red-500"
                                role="alert">
                                <div className="p-4">
                                    { errorMessage }
                                </div>
                            </div>
                    }

                    {
                        answerEvaluationFeedback.description && (
                        <div
                            id="dismiss-toast"
                            className={`mt-5 max-w-sm border text-sm border-l-4 ${answerEvaluationFeedback.status === "wrong" ? 'bg-red-100 border-red-200 text-red-800 border-l-red-500' : answerEvaluationFeedback.status === "correct" ? 'bg-green-100 border-green-200 text-green-800 border-l-green-500' : ''}`}
                            role="alert">

                            <div className="p-4">
                                {answerEvaluationFeedback.description}
                            </div>
                        </div>
                    )

                    }


                    <div className="grid md:grid-cols-2 mt-6">
                        <div>
                            <ul className="max-w-sm flex flex-col" id="question-answers">
                                {
                                    questions[0].options &&
                                    questions[0].options.map((option) => (
                                        <li key={`answer-${option.id}`}
                                            className={`${selectedAnswerId === option.id ? 'selected' : ''}  inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg`}>
                                            <div className="flex items-center h-5">
                                                <input
                                                    id={`answer-${option.id}`}
                                                    name="answer"
                                                    type="radio"
                                                    value={option.id}
                                                    onChange={(event) => onAnswerSelected(+event.target.value)}
                                                    className="border-gray-200 rounded-full"
                                                />
                                            </div>
                                            <label htmlFor={`answer-${option.id}`}
                                                   className="ms-3 block w-full text-sm text-gray-600">
                                                {option.name}
                                            </label>
                                        </li>
                                    ))
                                }
                            </ul>
                        </div>
                        {
                            questions[0].imgURL &&

                            <div>
                                <img src={questions[0].imgURL} alt="Question Image URL" className="w-64" loading="lazy"/>
                            </div>
                        }
                    </div>

                    {
                        canMoveToNextQuestion ?

                            (
                                <button type="button"
                                        className="mt-5 py-3 px-4 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700"
                                        id="next-question"
                                        onClick={this.handleNextButtonClick}
                                >
                                    Next Question
                                </button>
                            ) :
                            (
                                <button type="submit"
                                        className="mt-5 py-3 px-4 mr-2 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700"
                                        onClick={this.handleFormSubmit}
                                        id="submit-answer">
                                    Submit Answer
                                </button>
                            )
                    }
                </form>
            </div>
        );
    }
}

GeoQuestForm.propTypes = {
    selectedAnswerId: PropTypes.number,
    onAnswerSelected: PropTypes.func.isRequired,
    percentage: PropTypes.number,
    onAnswerSubmit: PropTypes.func,
    questions: PropTypes.array,
    isLoaded: PropTypes.bool.isRequired,
    canMoveToNextQuestion: PropTypes.bool.isRequired,
    answerEvaluationFeedback: PropTypes.object.isRequired,
    onNextQuestionClick: PropTypes.func.isRequired,
}
