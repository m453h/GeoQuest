import React, {Component} from 'react';
import GeoQuestQuizProgress from "./GeoQuestQuizPogress";
import PropTypes from "prop-types";

export default class GeoQuestForm extends Component {

    constructor(props) {
        super(props);
        this.handleFormSubmit = this.handleFormSubmit.bind(this);
        this.state = {
            answerInputError: ''
        };
    }

    handleFormSubmit (event) {
        event.preventDefault();
        const {selectedAnswerId, onAnswerSubmit} = this.props;

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
            return;
        }

        onAnswerSubmit(selectedAnswerId);
    }

    render() {
        const {selectedAnswerId, onAnswerSelected, percentage, question, isLoaded} = this.props;
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
                <form id="quiz-form" onSubmit={this.handleFormSubmit}>

                    <GeoQuestQuizProgress
                        percentage={percentage}
                    />

                    <p className="text-2xl font-light mb-2" id="question-text">{question.question}</p>
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

                    <p id="result"></p>

                    <div className="grid md:grid-cols-2 mt-6">
                        <div className="grid">
                            <ul className="max-w-sm flex flex-col" id="question-answers">
                                {
                                    question.options &&
                                    question.options.map((option) => (
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
                    </div>

                    <button type="submit"
                            className="mt-5 py-3 px-4 mr-2 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700"
                            id="submit-answer">
                        Submit Answer
                    </button>

                    <button type="button"
                            className="mt-5 py-3 px-4 text-sm font-semibold rounded-lg bg-purple-600 text-white hover:bg-purple-700"
                            id="next-question">
                        Next Question
                    </button>
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
    question: PropTypes.object,
    isLoaded: PropTypes.bool.isRequired
}
