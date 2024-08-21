class Dashboard {
    constructor() {
        // Reference to the main content div where the dashboard will be rendered
        this.contentDiv = document.getElementById('content');
    }

    // Creates and returns an HTML element with specified attributes and optional text content
    createHTMLElement(tag, attributes = {}, textContent = '') {
        const element = document.createElement(tag);
        Object.keys(attributes).forEach(key => element.setAttribute(key, attributes[key]));
        if (textContent) {
            element.textContent = textContent;
        }
        return element;
    }

    // Renders the dashboard by fetching statistics and displaying them in a grid layout
    render() {
        this.contentDiv.innerHTML = '';

        Ajax.get('/getDashboardStats')
            .then(data => {
                console.log(data);
                const gridContainer = this.createHTMLElement('div', {class: 'dashboard-grid'});

                const stats = [
                    {label: 'Products count:', value: data.productCount, id: 'productCount'},
                    {label: 'Categories count:', value: data.categoryCount, id: 'categoryCount'},
                    {label: 'Home page opening count:', value: data.homePageViewCount, id: 'homePageViewCount'},
                    {label: 'Most often viewed product:', value: data.mostViewedProduct, id: 'mostViewedProduct'},
                    {label: 'Number of prod1 views:', value: data.mostViewedProductCount, id: 'mostViewedProductCount'}
                ];

                stats.forEach(stat => {
                    const statWrapper = this.createHTMLElement('div', {class: 'stat-item'});
                    const statLabel = this.createHTMLElement('label', {for: stat.id}, stat.label);
                    const statInput = this.createHTMLElement('input', {
                        type: 'text',
                        id: stat.id,
                        value: stat.value,
                        readonly: true
                    });

                    statWrapper.appendChild(statLabel);
                    statWrapper.appendChild(statInput);
                    gridContainer.appendChild(statWrapper);
                });

                this.contentDiv.appendChild(gridContainer);
            })
            .catch(error => console.error('Error loading dashboard stats:', error));
    }
}