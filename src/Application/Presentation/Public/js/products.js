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

        // Attach event listeners after buttons are added to the DOM
        document.getElementById('enable-selected-btn').addEventListener('click', () => {
            this.updateSelectedProducts(true);
        });

        document.getElementById('disable-selected-btn').addEventListener('click', () => {
            this.updateSelectedProducts(false);
        });

        deleteSelectedButton.addEventListener('click', () => {
            const selectedProductIds = Array.from(document.querySelectorAll('input.product-checkbox:checked'))
                .map(checkbox => checkbox.getAttribute('data-product-id'));

            this.deleteProducts(selectedProductIds);
        });

        // Dodajemo event listener za dugme "Add new product"
        addProductButton.addEventListener('click', () => {
            this.showAddProductForm();
        });
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
            const checkbox = this.createHTMLElement('input', {
                type: 'checkbox',
                class: 'product-checkbox',
                'data-product-id': product.id
            });
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
                class: 'readonly-checkbox'
            });
            enableCheckbox.checked = product.enabled === 1;
            enableCell.appendChild(enableCheckbox);

            const actionsCell = this.createHTMLElement('td');
            const actionButtonsDiv = this.createHTMLElement('div', {class: 'action-buttons'});

            const editButton = this.createHTMLElement('button', {class: 'edit-button'}, 'Edit');
            const deleteButton = this.createHTMLElement('button', {class: 'delete-button'}, 'Delete');

            deleteButton.addEventListener('click', () => {
                this.deleteProducts([product.id]);
            });

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

    updateSelectedProducts(enable) {
        const selectedProductIds = Array.from(document.querySelectorAll('input.product-checkbox:checked'))
            .map(checkbox => checkbox.getAttribute('data-product-id'));

        console.log(selectedProductIds);

        if (selectedProductIds.length === 0) {
            alert('Please select at least one product.');
            return;
        }

        const url = enable ? '/enableProducts' : '/disableProducts';

        Ajax.post(url, {productIds: selectedProductIds})
            .then(response => {
                console.log(response);
                this.loadProducts();
            })
            .catch(error => {
                console.error('Error updating products:', error);
            });
    }

    deleteProducts(productIds) {
        if (!Array.isArray(productIds) || productIds.length === 0) {
            alert('Please select at least one product to delete.');
            return;
        }

        const confirmed = confirm('Are you sure you want to delete the selected product(s)?');
        if (!confirmed) {
            return;
        }

        Ajax.delete('/deleteProduct', {ids: productIds})
            .then(response => {
                console.log(response);
                this.loadProducts();
            })
            .catch(error => {
                console.error('Error deleting products:', error);
            });
    }

    loadAllCategories() {
        return Ajax.get('/getAllCategories')
            .then(data => {
                return data;
            })
            .catch(error => {
                console.error('Error fetching categories:', error);
                return [];
            });
    }

    showAddProductForm() {
        this.clearContent();

        const formContainer = this.createHTMLElement('div', {id: 'product-form', class: 'product-form'});

        const skuLabel = this.createHTMLElement('label', {}, 'SKU:');
        const skuInput = this.createHTMLElement('input', {type: 'text', id: 'new-product-sku'});

        const titleLabel = this.createHTMLElement('label', {}, 'Title:');
        const titleInput = this.createHTMLElement('input', {type: 'text', id: 'new-product-title'});

        const brandLabel = this.createHTMLElement('label', {}, 'Brand:');
        const brandInput = this.createHTMLElement('input', {type: 'text', id: 'new-product-brand'});

        const categoryLabel = this.createHTMLElement('label', {}, 'Category:');
        const categorySelect = this.createHTMLElement('select', {id: 'new-product-category'});

        // const rootOption = this.createHTMLElement('option', {value: '0'}, 'Root');
        // categorySelect.appendChild(rootOption);

        this.loadAllCategories().then(categories => {
            console.log("Categories loaded:", categories);
            categories.forEach(category => {
                console.log("Adding category:", category.name);
                const option = this.createHTMLElement('option', {value: category.id}, category.title);
                categorySelect.appendChild(option);
            });
        });

        const priceLabel = this.createHTMLElement('label', {}, 'Price:');
        const priceInput = this.createHTMLElement('input', {type: 'number', id: 'new-product-price', step: '0.01'});

        const shortDescLabel = this.createHTMLElement('label', {}, 'Short description:');
        const shortDescTextarea = this.createHTMLElement('textarea', {id: 'new-product-short-description'});

        const descLabel = this.createHTMLElement('label', {}, 'Description:');
        const descTextarea = this.createHTMLElement('textarea', {id: 'new-product-description'});

        const imageLabel = this.createHTMLElement('label', {}, 'Image:');
        const imageInput = this.createHTMLElement('input', {type: 'file', id: 'new-product-image'});

        const enableCheckboxLabel = this.createHTMLElement('label', {}, 'Enable in shop:');
        const enableCheckbox = this.createHTMLElement('input', {type: 'checkbox', id: 'new-product-enabled'});

        const featuredCheckboxLabel = this.createHTMLElement('label', {}, 'Featured:');
        const featuredCheckbox = this.createHTMLElement('input', {type: 'checkbox', id: 'new-product-featured'});

        const saveButton = this.createHTMLElement('button', {id: 'save-product'}, 'Save');
        const cancelButton = this.createHTMLElement('button', {id: 'cancel-product'}, 'Cancel');

        saveButton.addEventListener('click', () => this.saveNewProduct());
        cancelButton.addEventListener('click', () => this.cancelAddProductForm());

        formContainer.appendChild(skuLabel);
        formContainer.appendChild(skuInput);
        formContainer.appendChild(titleLabel);
        formContainer.appendChild(titleInput);
        formContainer.appendChild(brandLabel);
        formContainer.appendChild(brandInput);
        formContainer.appendChild(categoryLabel);
        formContainer.appendChild(categorySelect);
        formContainer.appendChild(priceLabel);
        formContainer.appendChild(priceInput);
        formContainer.appendChild(shortDescLabel);
        formContainer.appendChild(shortDescTextarea);
        formContainer.appendChild(descLabel);
        formContainer.appendChild(descTextarea);
        formContainer.appendChild(imageLabel);
        formContainer.appendChild(imageInput);
        formContainer.appendChild(enableCheckboxLabel);
        formContainer.appendChild(enableCheckbox);
        formContainer.appendChild(featuredCheckboxLabel);
        formContainer.appendChild(featuredCheckbox);
        formContainer.appendChild(saveButton);
        formContainer.appendChild(cancelButton);

        this.contentDiv.appendChild(formContainer);
    }

    saveNewProduct() {
        const formData = new FormData();

        const sku = document.getElementById('new-product-sku').value.trim();
        const title = document.getElementById('new-product-title').value.trim();
        const brand = document.getElementById('new-product-brand').value.trim();
        const category = document.getElementById('new-product-category').value;
        const price = document.getElementById('new-product-price').value.trim();
        const shortDesc = document.getElementById('new-product-short-description').value.trim();
        const description = document.getElementById('new-product-description').value.trim();
        const image = document.getElementById('new-product-image').files[0];
        const enabled = document.getElementById('new-product-enabled').checked;
        const featured = document.getElementById('new-product-featured').checked;

        if (!sku || !title || !brand || !category || !price) {
            alert('All fields except image and description must be filled out.');
            return;
        }

        formData.append('sku', sku);
        formData.append('title', title);
        formData.append('brand', brand);
        formData.append('category', category);
        formData.append('price', price);
        formData.append('short_description', shortDesc);
        formData.append('description', description);
        if (image) {
            formData.append('image', image);
        }
        formData.append('enabled', enabled ? 1 : 0);
        formData.append('featured', featured ? 1 : 0);
        console.log(formData);

        Ajax.post2('/addProduct', formData)
            .then(response => {
                console.log('Product added successfully:', response);
                this.render();
            })
            .catch(error => {
                console.error('Error adding product:', error);
            });
    }
}
