/**
 * Class providing methods for sending AJAX requests using the Fetch API.
 */
class Ajax {
    /**
     * Performs a GET request to the specified URL.
     *
     * @param {string} url - The URL to which the GET request is sent.
     * @returns {Promise<Object>} - A promise that resolves with the JSON response from the server.
     */
    static get(url) {
        return fetch(url)
            .then(response => response.json());
    }

    /**
     * Performs a POST request to the specified URL with the given data.
     *
     * @param {string} url - The URL to which the POST request is sent.
     * @param {Object} data - The data to be sent in the request body, must be in JSON format.
     * @returns {Promise<Object>} - A promise that resolves with the JSON response from the server.
     */
    static post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json());
    }

    /**
     * Performs a DELETE request to the specified URL.
     *
     * @param {string} url - The URL to which the DELETE request is sent.
     * @returns {Promise<Object|null>} - A promise that resolves with the JSON response from the server or null if the status is 204 (No Content).
     * @throws {Error} - Throws an error if the response is not ok, with the text of the response.
     */
    static delete(url) {
        return fetch(url, {
            method: 'DELETE'
        })
            .then(response => {
                if (response.ok) {
                    if (response.status === 204) {
                        return null;
                    }
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error(text || 'Network response was not ok');
                    });
                }
            });
    }
}