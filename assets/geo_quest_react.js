import React, {StrictMode} from "react";
import ReactDOM from "react-dom/client";
import GeoQuestApp from './GeoQuest/GeoQuestApp'

if (document.getElementById("quiz-content")) {
    const root = ReactDOM.createRoot(document.getElementById("quiz-content"));
    root.render(
        <StrictMode>
            <GeoQuestApp />
        </StrictMode>
    );
}
