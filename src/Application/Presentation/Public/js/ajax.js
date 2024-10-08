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
     * Sends a POST request to the specified URL with the given data.
     *
     * This method uses the Fetch API to send a POST request to the provided URL.
     * The `data` parameter is included as the body of the request. The response
     * from the server is expected to be in JSON format, and the method returns a promise
     * that resolves to the parsed JSON data.
     *
     * @param {string} url - The URL to which the POST request is sent.
     * @param {FormData|Object} data - The data to be sent in the body of the POST request.
     *                                  Typically, this is a FormData object or a plain JavaScript object.
     * @returns {Promise<Object>} A promise that resolves to the JSON-parsed response from the server.
     */
    static post2(url, data) {
        return fetch(url, {
            method: 'POST',
            body: data
        })
            .then(response => response.json());
    }

    /**
     * Performs a DELETE request to the specified URL.
     *
     * @param {string} url - The URL to which the DELETE request is sent.
     * @param data
     * @returns {Promise<Object|null>} - A promise that resolves with the JSON response from the server or null if the status is 204 (No Content).
     * @throws {Error} - Throws an error if the response is not ok, with the text of the response.
     */
    static delete(url, data) {
        return fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
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

    /**
     * Performs a PUT request to the specified URL with the given data.
     *
     * @param {string} url - The URL to which the PUT request is sent.
     * @param {Object} data - The data to be sent in the request body, must be in JSON format.
     * @returns {Promise<Object>} - A promise that resolves with the JSON response from the server.
     */
    static put(url, data) {
        return fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json());
    }
}