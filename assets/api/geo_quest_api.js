function fetchJson(url, options) {
    return fetch(url, Object.assign({
        credentials: 'same-origin'
    }, options)).then(response => {
        return response.json();
    })
}

export function getQuestions() {
    return fetchJson('/api/quiz/generate-questions');
}

export function submitAnswer(answer) {
    return fetchJson('/api/quiz/evaluate-answer', {
            method: 'POST',
            body: JSON.stringify(answer),
            headers: {
                'Content-Type': 'application/json',
            }
        });
}
