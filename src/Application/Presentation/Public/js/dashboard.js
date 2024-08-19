document.addEventListener('DOMContentLoaded', () => {
    const contentDiv = document.getElementById('content');
    const menuItems = document.querySelectorAll('.sideMenu ul li');

    function createHTMLElement(tag, attributes = {}, textContent = '') {
        const element = document.createElement(tag);
        Object.keys(attributes).forEach(key => element.setAttribute(key, attributes[key]));
        if (textContent) {
            element.textContent = textContent;
        }
        return element;
    }

    function renderDashboard() {
        contentDiv.innerHTML = '';

        const gridContainer = createHTMLElement('div', {class: 'dashboard-grid'});

        const stats = [
            {label: 'Products count:', value: '120', id: 'productCount'},
            {label: 'Categories count:', value: '15', id: 'categoryCount'},
            {label: 'Home page opening count:', value: '50', id: 'homepageCount'},
            {label: 'Most often viewed product:', value: 'prod 1', id: 'mostViewedProduct'},
            {label: 'Number of prod1 views:', value: '32', id: 'productViews'}
        ];

        stats.forEach(stat => {
            const statWrapper = createHTMLElement('div', {class: 'stat-item'});
            const statLabel = createHTMLElement('label', {for: stat.id}, stat.label);
            const statInput = createHTMLElement('input', {
                type: 'text',
                id: stat.id,
                value: stat.value,
                readonly: true
            });

            statWrapper.appendChild(statLabel);
            statWrapper.appendChild(statInput);
            gridContainer.appendChild(statWrapper);
        });

        contentDiv.appendChild(gridContainer);
    }

    function renderProducts() {
        contentDiv.innerHTML = '';
        const heading = createHTMLElement('h2', {}, 'Products Section');
        const message = createHTMLElement('p', {}, 'No data available.');
        contentDiv.appendChild(heading);
        contentDiv.appendChild(message);
    }

    function renderCategories() {
        contentDiv.innerHTML = '';
        const heading = createHTMLElement('h2', {}, 'Product Categories Section');
        const message = createHTMLElement('p', {}, 'No data available.');
        contentDiv.appendChild(heading);
        contentDiv.appendChild(message);
    }

    renderDashboard();

    menuItems.forEach(item => {
        item.addEventListener('click', function () {
            menuItems.forEach(i => i.classList.remove('active'));
            this.classList.add('active');

            const sectionId = this.getAttribute('data-section');

            switch (sectionId) {
                case 'dashboard':
                    renderDashboard();
                    break;
                case 'products':
                    renderProducts();
                    break;
                case 'categories':
                    renderCategories();
                    break;
                default:
                    renderDashboard();
                    break;
            }
        });
    });
});