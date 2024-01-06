import React, { Component } from "react";
import GeoQuestForm from "./GeoQuestForm";
import {getQuestion} from "../api/geo_quest_api";

export default class GeoQuestApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedAnswerId: null,
            question:{},
            isLoaded: false
        }
        this.handleAnswerSelect = this.handleAnswerSelect.bind(this);
        this.handleAnswerSubmit = this.handleAnswerSubmit.bind(this);
    }

    componentDidMount() {
        getQuestion()
            .then((data) =>{
                this.setState( {
                    question: data,
                    isLoaded: true
                })
            });
    }

    handleAnswerSelect(answerId) {
        this.setState({selectedAnswerId: answerId});
    }

    handleAnswerSubmit(value){
        console.log(value);
    }

    render() {
        return(
            <GeoQuestForm
                {...this.props}
                {...this.state}
                onAnswerSelected = {this.handleAnswerSelect}
                onAnswerSubmit = {this.handleAnswerSubmit}
                percentage={10}
            />
        );
    }
}
