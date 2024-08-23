class Products {
    constructor() {
        // Reference to the main content div where products will be rendered
        this.contentDiv = document.getElementById('content');
    }

    // Method for creating an HTML element with specified attributes and optional text content
    createHTMLElement(tag, attributes = {}, textContent = '') {
        const element = document.createElement(tag);
        Object.keys(attributes).forEach(key => element.setAttribute(key, attributes[key]));
        if (textContent) {
            element.textContent = textContent;
        }
        return element;
    }

    // Main method to render all products
    render() {
        this.clearContent();
        this.createProductsLayout();
        this.loadProducts();
    }

    // Clears the content div
    clearContent() {
        this.contentDiv.innerHTML = '';
    }

    // Creates the layout for displaying products
    createProductsLayout() {
        const productsContainer = this.createHTMLElement('div', {id: 'products-container'});

        const header = this.createHTMLElement('h2', {}, 'Products');
        productsContainer.appendChild(header);

        const buttonContainer = this.createHTMLElement('div', {class: 'button-container'});
        const addProductButton = this.createHTMLElement('button', {id: 'add-product-btn'}, 'Add new product');
        const deleteSelectedButton = this.createHTMLElement('button', {id: 'delete-selected-btn'}, 'Delete selected');
        const enableSelectedButton = this.createHTMLElement('button', {id: 'enable-selected-btn'}, 'Enable selected');
        const disableSelectedButton = this.createHTMLElement('button', {id: 'disable-selected-btn'}, 'Disable selected');
        const filterButton = this.createHTMLElement('button', {id: 'filter-btn'}, 'Filter');

        buttonContainer.appendChild(addProductButton);
        buttonContainer.appendChild(deleteSelectedButton);
        buttonContainer.appendChild(enableSelectedButton);
        buttonContainer.appendChild(disableSelectedButton);
        buttonContainer.appendChild(filterButton);

        productsContainer.appendChild(buttonContainer);

        const table = this.createHTMLElement('table', {id: 'products-table', class: 'products-table'});
        const thead = this.createHTMLElement('thead');
        const tbody = this.createHTMLElement('tbody');

        const headers = ['Select', 'Title', 'SKU', 'Brand', 'Category', 'Short description', 'Price', 'Enable', 'Actions'];
        const headerRow = this.createHTMLElement('tr');

        headers.forEach(headerText => {
            const th = this.createHTMLElement('th', {}, headerText);
            headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);
        table.appendChild(tbody);
        productsContainer.appendChild(table);

        const pagination = this.createHTMLElement('div', {class: 'pagination'});
        pagination.innerHTML = '<span>&lt;&lt;</span> <span class="current-page">1</span> / <span class="total-pages">7</span> <span>&gt;&gt;</span>';
        productsContainer.appendChild(pagination);

        this.contentDiv.appendChild(productsContainer);
    }

    // Loads products from the server and renders them
    loadProducts() {
        Ajax.get('/getProducts')
            .then(data => {
                this.renderProducts(data);
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    // Renders the products in the table
    renderProducts(products) {
        const tbody = document.querySelector('#products-table tbody');
        tbody.innerHTML = ''; // Clear the table body

        products.forEach(product => {
            const row = this.createHTMLElement('tr');

            const checkboxCell = this.createHTMLElement('td');
            const checkbox = this.createHTMLElement('input', {type: 'checkbox'});
            checkboxCell.appendChild(checkbox);

            const titleCell = this.createHTMLElement('td', {}, product.title);
            const skuCell = this.createHTMLElement('td', {}, product.sku);
            const brandCell = this.createHTMLElement('td', {}, product.brand);
            const categoryCell = this.createHTMLElement('td', {}, product.category_name);
            const descriptionCell = this.createHTMLElement('td', {}, product.short_description);
            const priceCell = this.createHTMLElement('td', {}, product.price);
            const enableCell = this.createHTMLElement('td');
            const enableCheckbox = this.createHTMLElement('input', {
                type: 'checkbox',
                checked: product.isEnabled,
                class: 'readonly-checkbox'
            });
            enableCell.appendChild(enableCheckbox);

            const actionsCell = this.createHTMLElement('td');
            const actionButtonsDiv = this.createHTMLElement('div', {class: 'action-buttons'});

            const editButton = this.createHTMLElement('button', {class: 'edit-button'}, 'Edit');
            const deleteButton = this.createHTMLElement('button', {class: 'delete-button'}, 'Delete');

            actionButtonsDiv.appendChild(editButton);
            actionButtonsDiv.appendChild(deleteButton);
            actionsCell.appendChild(actionButtonsDiv);

            row.appendChild(checkboxCell);
            row.appendChild(titleCell);
            row.appendChild(skuCell);
            row.appendChild(brandCell);
            row.appendChild(categoryCell);
            row.appendChild(descriptionCell);
            row.appendChild(priceCell);
            row.appendChild(enableCell);
            row.appendChild(actionsCell);

            tbody.appendChild(row);
        });
    }

    // attachButtonListeners() {
    //     document.getElementById('enable-selected-btn').addEventListener('click', () => this.updateSelectedProducts(true));
    //     document.getElementById('disable-selected-btn').addEventListener('click', () => this.updateSelectedProducts(false));
    // }
    //
    // updateSelectedProducts(enable) {
    //     const selectedProductIds = Array.from(document.querySelectorAll('.products-table tbody input[type="checkbox"]:checked'))
    //         .map(checkbox => checkbox.closest('tr').dataset.productId);
    //
    //     if (selectedProductIds.length === 0) {
    //         alert('Please select at least one product.');
    //         return;
    //     }
    //
    //     const url = enable ? '/enableProducts' : '/disableProducts';
    //
    //     Ajax.post(url, { productIds: selectedProductIds })
    //         .then(response => {
    //             console.log(response);
    //             this.loadProducts(); // Ponovo učitavanje proizvoda nakon ažuriranja
    //         })
    //         .catch(error => {
    //             console.error('Error updating products:', error);
    //         });
    // }
}
