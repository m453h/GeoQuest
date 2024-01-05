import React, {Component} from 'react';
import PropTypes from "prop-types";
import GeoQuestQuestion from "./GeoQuestQuestion";


export default  class GeoQuestQuizProgress extends Component {

    render() {

        const { percentage } = this.props;

        const progressBarStyle = {
            width: `${percentage}%`,
        }

        return (
            <div className="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700 mb-12"
                 role="progressbar"
                 aria-valuenow="25"
                 aria-valuemin="0"
                 aria-valuemax="100">
            <div id="progress"
                 className="flex flex-col justify-center rounded-full overflow-hidden bg-purple-600 text-xs text-white text-center whitespace-nowrap transition duration-500"
                 style={ progressBarStyle }
            >
            </div>
        </div>);
    }
}

GeoQuestQuestion.propTypes = {
    percentage: PropTypes.number,
}
