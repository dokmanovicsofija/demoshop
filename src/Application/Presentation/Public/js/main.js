// Wait for the DOM content to be fully loaded before running the script
document.addEventListener('DOMContentLoaded', () => {
    // Get the main content div where different views will be rendered
    const contentDiv = document.getElementById('content');
    // Initialize the router, passing in the contentDiv for rendering views
    const router = new Router(contentDiv);

    // Add a route for the admin dashboard
    router.addRoute('/admin/dashboard', () => {
        // Create and render the dashboard view when the route is accessed
        const dashboard = new Dashboard();
        dashboard.render();
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
