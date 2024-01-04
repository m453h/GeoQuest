import React, { Component } from "react";

export default class GeoQuestApp extends Component {
    render() {
        let heart = '';
        if (this.props.withHeart) {
            heart = <span>💚</span>;
        }
       /* const questions = [
            { id: 1, content: 'The following flag belongs to which country ?', options:['Aruba', 'Kenya', 'Uganda'] },
            { id: 2, content: 'The following currency is used in Tanzania ?', options:['TZS', 'KSH', 'UGX'] },
            { id: 3, content: 'The following flag belongs to which country ?', options:['Aruba', 'Kenya', 'Uganda'] },
        ]
        const questionElement = questions.map((question) =>{
            return (
                <div>
                    <p>{question.content}</p>
                    <ul>
                        {question.options.map((option, index) => (
                            <li key={index}>{option}</li>
                        ))}
                    </ul>
                </div>
            )
        });*/

        return (
            <div className="container mx-auto">
                <form action="#" id="quiz-form" method="POST">
                    <div className="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700 mb-12"
                         role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        <div id="progress"
                             className="flex flex-col justify-center rounded-full overflow-hidden bg-purple-600 text-xs text-white text-center whitespace-nowrap transition duration-500"></div>
                    </div>
                    <p className="text-2xl font-light mb-2" id="question-text"></p>
                    <p id="result"></p>

                    <div className="grid md:grid-cols-2 mt-6">
                        <div className="grid">
                            <ul className="max-w-sm flex flex-col" id="question-answers">

                            </ul>
                        </div>
                        <div className="grid">
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
