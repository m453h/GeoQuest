import React, { Component } from "react";
import GeoQuestForm from "./GeoQuestForm";

export default class GeoQuestApp extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selectedAnswerId: null,
            question: {
                "id":1553,
                "content":"Reykjavik",
                "content_type":"Text",
                "answer_id":95,
                "answer_label":"Iceland",
                "fact_type":"Capitals",
                "options":[
                    {"id":128,"name":"Cameroon"},
                    {"id":7,"name":"Pakistan"},
                    {"id":90,"name":"Chile"},
                    {"id":95,"name":"Iceland"},
                    {"id":241,"name":"Singapore"}
                ]
                ,"question":"Reykjavik is the capital of which country ?"
            }
        }
        this.handleAnswerSelect = this.handleAnswerSelect.bind(this);
    }

    handleAnswerSelect(answerId) {
        this.setState({selectedAnswerId: answerId});
    }

    handleAnswerSubmit(event){
        event.preventDefault();
    }

    render() {

        return(
            <GeoQuestForm
                {...this.props}
                {...this.state}
                onAnswerSelected = {this.handleAnswerSelect}
                onAnswerSubmit = {this.handleAnswerSubmit}
            />
        );
    }
}
