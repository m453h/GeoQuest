import React from 'react';
import PropTypes from "prop-types";


export default function GeoQuestQuestion(props) {

    const { selectedAnswerId, onAnswerSelected, question } = props;

    return (<>
        <p className="text-2xl font-light mb-2" id="question-text">{ question.question }</p>
        <p id="result"></p>
        <div className="grid md:grid-cols-2 mt-6">
            <div className="grid">
                <ul className="max-w-sm flex flex-col" id="question-answers">
                    {
                        question.options.map((option, index) => (
                            <li key={`answer-${option.id}`} className={`${selectedAnswerId === option.id ? 'selected' : ''}  inline-flex items-center gap-x-2 py-3 px-4 text-sm font-medium bg-white border -mt-px first:rounded-t-lg first:mt-0 last:rounded-b-lg`}>                                        <div className="flex items-center h-5">
                                <input
                                    id={`answer-${option.id}`}
                                    name="answer"
                                    type="radio"
                                    value={option.id}
                                    onClick={ (event) => onAnswerSelected(option.id)}
                                    className="border-gray-200 rounded-full"
                                />
                            </div>
                                <label htmlFor={`answer-${index}`} className="ms-3 block w-full text-sm text-gray-600">
                                    {option.name}
                                </label>
                            </li>
                        ))}
                </ul>
            </div>
        </div>
    </>);
}

GeoQuestQuestion.propTypes = {
    selectedAnswerId: PropTypes.number,
    onAnswerSelected: PropTypes.func.isRequired,
    question: PropTypes.object
}
