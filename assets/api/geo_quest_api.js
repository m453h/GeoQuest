function fetchJson(url, options) {
    return fetch(url, Object.assign({
        credentials: 'same-origin'
    }, options)).then(response => {
        return response.json();
    })
}

export function getQuestion() {
    return fetchJson('/api/quiz/generate-question');
}

export function submitAnswer() {
    return fetchJson('/api/quiz/evaluate-answer');
}
