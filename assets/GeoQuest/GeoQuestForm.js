import React from 'react';
import GeoQuestQuizProgress from "./GeoQuestQuizPogress";
import GeoQuestQuestion from "./GeoQuestQuestion";
import PropTypes from "prop-types";

export default function GeoQuestForm(props) {

    const { selectedAnswerId, onAnswerSelected, percentage, question, onAnswerSubmit} = props;

    return (
        <div className="container mx-auto">
            <form id="quiz-form" onSubmit={onAnswerSubmit}>

                <GeoQuestQuizProgress
                    percentage={percentage}
                />

                <GeoQuestQuestion
                    selectedAnswerId={selectedAnswerId}
                    onAnswerSelected={onAnswerSelected}
                    question={question}
                />

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

GeoQuestForm.propTypes = {
    selectedAnswerId: PropTypes.number,
    onAnswerSelected: PropTypes.func.isRequired,
    percentage: PropTypes.number,
    onAnswerSubmit: PropTypes.func,
    question: PropTypes.object
}
