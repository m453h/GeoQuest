import React from "react";
import ReactDOM from "react-dom/client";
import GeoQuestApp from './GeoQuest/GeoQuestApp'

const shouldShowHeart = true;

const root = ReactDOM.createRoot(document.getElementById("quiz-content"));
root.render(
    <div>
        <GeoQuestApp withHeart={shouldShowHeart} />
    </div>
);