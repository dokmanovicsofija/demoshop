class Router {
    constructor(contentDiv) {
        this.routes = {};
        this.contentDiv = contentDiv;
        this.init();
    }

    /**
     * Registers a route with a specified path and handler function.
     *
     * @param {string} path - The URL path to register.
     * @param {Function} handler - The function to execute when the path is navigated to.
     */
    addRoute(path, handler) {
        this.routes[path] = handler;
    }

    /**
     * Handles the navigation to a given path and updates the content.
     *
     * @param {string} path - The path to navigate to.
     */
    goTo(path) {
        history.pushState(null, '', path);
        this.handlePath(path);
    }

    /**
     * Finds and executes the handler associated with the given path.
     *
     * @param {string} path - The path to handle.
     */
    handlePath(path) {
        const handler = this.routes[path];
        if (handler) {
            handler(this.contentDiv);
        } else {
            this.displayError(`Handler not found for path: ${path}`);
        }
    }

    /**
     * Initializes the router by setting up event listeners for link clicks
     * and handling the initial page load.
     */
    init() {
        window.addEventListener('popstate', () => {
            this.handlePath(window.location.pathname);
        });

        document.body.addEventListener('click', (event) => {
            if (event.target.tagName === 'A' && event.target.getAttribute('href').startsWith('/')) {
                event.preventDefault();
                const path = event.target.getAttribute('href');
                this.goTo(path);
            }
        });

        this.handlePath(window.location.pathname);
    }

    /**
     * Displays an error message if the handler for the given path is not found.
     *
     * @param {string} message - The error message to display.
     */
    displayError(message) {
        console.error(message);
        this.contentDiv.innerHTML = `<p style="color:red;">${message}</p>`;
    }
}