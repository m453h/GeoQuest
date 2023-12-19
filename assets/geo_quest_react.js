import React from "react";
import { render } from 'react-dom';
import GeoQuestApp from './GeoQuest/GeoQuestApp'

const shouldShowHeart = true;
render(
    <div>
        <GeoQuestApp withHeart={shouldShowHeart} />
    </div>,
    document.getElementById('quiz-content')
);
