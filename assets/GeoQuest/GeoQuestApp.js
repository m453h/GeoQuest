import React, { Component } from "react";
import GeoQuestForm from "./GeoQuestForm";
import {getQuestions, submitAnswer} from "../api/geo_quest_api";

export default class GeoQuestApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedAnswerId: null,
            questions: [],
            isLoaded: false,
            canMoveToNextQuestion: false,
            numberOfQuestionsDone: 0,
            answerEvaluationFeedback: {},
            totalQuestions: 0,
            remainingQuestions: 0,
            totalScore: 0,
            viewResults: false,
        }
        this.handleAnswerSelect = this.handleAnswerSelect.bind(this);
        this.handleAnswerSubmit = this.handleAnswerSubmit.bind(this);
        this.handleNextQuestionClick = this.handleNextQuestionClick.bind(this);
        this.handleTotalScoreButtonClick = this.handleTotalScoreButtonClick.bind(this);
    }

    componentDidMount() {
        getQuestions()
            .then((data) =>{
                this.setState( {
                    questions: data,
                    isLoaded: true,
                    totalQuestions: data.length,
                    remainingQuestions: data.length
                })
            });
    }

    handleAnswerSelect(answerId) {
        this.setState({selectedAnswerId: answerId});
    }

    handleAnswerSubmit(answerId, currentQuestion){

        const answer = {
            ...currentQuestion,
            answerId: answerId,
        };

        delete answer['options'];
        delete answer['contentType'];
        delete answer['prompt'];

        submitAnswer(answer).then( data =>{
            this.setState((prevState)=>({
                answerEvaluationFeedback: data,
                canMoveToNextQuestion: true,
                numberOfQuestionsDone: prevState.numberOfQuestionsDone + 1,
                remainingQuestions: data.remainingQuestions,
                totalScore: data.totalScore
            }));
        });
    }

    handleNextQuestionClick() {
        this.setState((prevState)=>({
            answerEvaluationFeedback: {},
            canMoveToNextQuestion: false,
            selectedAnswerId: null,
            questions: prevState.questions.slice(1)
        }));
    }

    handleTotalScoreButtonClick() {
        this.setState((prevState)=>({
            viewResults: true,
        }));
    }

    calculateProgress() {
        return ((this.state.numberOfQuestionsDone / this.state.totalQuestions) * 100);
    }

    render() {
        let percentage = this.calculateProgress();

        return(
            <GeoQuestForm
                {...this.props}
                {...this.state}
                onAnswerSelected = {this.handleAnswerSelect}
                onAnswerSubmit = {this.handleAnswerSubmit}
                onNextQuestionClick = {this.handleNextQuestionClick}
                onTotalScoreButtonClick = {this.handleTotalScoreButtonClick}
                percentage={percentage}
            />
        );
    }
}
