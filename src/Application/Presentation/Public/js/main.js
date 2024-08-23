document.addEventListener('DOMContentLoaded', () => {
    // Get the main content div where different views will be rendered
    const contentDiv = document.getElementById('content');
    // Initialize the router, passing in the contentDiv for rendering views
    const router = new Router(contentDiv);

    function loadCSS(filename) {
        const link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = filename;
        link.classList.add('dynamic-css');
        document.head.appendChild(link);
    }

    function removeCSS() {
        const existingCSS = document.querySelectorAll('.dynamic-css');
        existingCSS.forEach(link => link.remove());
    }

    // Add a route for the admin dashboard
    router.addRoute('/admin/dashboard', () => {
        removeCSS();
        // Create and render the dashboard view when the route is accessed
        const dashboard = new Dashboard();
        dashboard.render();
    });

    // Add a route for the categories
    router.addRoute('/admin/categories', () => {
        removeCSS();
        loadCSS('/src/Application/Presentation/Public/css/categories.css');
        const categories = new Categories();
        categories.render();
    });

    // Add a route for the products
    router.addRoute('/admin/products', () => {
        removeCSS();
        loadCSS('/src/Application/Presentation/Public/css/products.css');
        const products = new Products();
        products.render();
    });

    // Automatically navigate to the dashboard route when the page loads
    router.goTo('/admin/dashboard');

    // Select all menu items in the side menu for interaction
    const menuItems = document.querySelectorAll('.sideMenu ul li');
    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const sectionId = this.getAttribute('data-section');
            router.goTo(`/${sectionId}`);
        });
    });
});
