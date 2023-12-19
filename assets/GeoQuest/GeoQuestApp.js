import React, { Component } from "react";

export default class GeoQuestApp extends Component {
    render() {
        let heart = '';
        if (this.props.withHeart) {
            heart = <span>💚</span>;
        }
        const questions = [
            { id: 1, content: 'The following flag belongs to which country ?', options:['Aruba', 'Kenya', 'Uganda'] },
            { id: 2, content: 'The following currency is used in Tanzania ?', options:['TZS', 'KSH', 'UGX'] },
            { id: 3, content: 'The following flag belongs to which country ?', options:['Aruba', 'Kenya', 'Uganda'] },
        ]
        const questionElement = questions.map((question) =>{
            return (
                <div>
                    <p>{question.content}</p>
                    <p>{question.options}</p>
                </div>
            )
        });


        return (
            <div className="container mx-auto">
                <h1 className="font-medium">Welcome to GeoQuest !!! {heart} </h1>
                { questionElement }
            </div>
        );
    }
}
