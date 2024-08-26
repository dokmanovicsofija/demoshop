// categories.js
class Categories {
    constructor() {
        // Reference to the main content div where categories will be rendered
        this.contentDiv = document.getElementById('content');
        this.categoryData = {};
        this.selectedCategoryId = null;
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

    // Main method to render all categories
    render() {
        this.clearContent();
        this.createCategoryLayout();
        this.loadCategories();
    }

    // Clears the content div
    clearContent() {
        this.contentDiv.innerHTML = '';
    }

    // Creates the layout for displaying categories and their details
    createCategoryLayout() {
        const categoriesContainer = this.createHTMLElement('div', {id: 'categories-container'});

        const categoriesTree = this.createHTMLElement('div', {id: 'categories-tree'});
        const ul = this.createHTMLElement('ul');

        categoriesTree.appendChild(ul);
        categoriesContainer.appendChild(categoriesTree);

        const categoryDetails = this.createHTMLElement('div', {id: 'category-details'});
        this.appendDetailsFields(categoryDetails); // Append fields for category details

        categoriesContainer.appendChild(categoryDetails);
        this.contentDiv.appendChild(categoriesContainer);

        this.addRootCategoryButton(categoriesTree); // Add the button for adding a root category
    }

    // Adds the "Add root category" button
    addRootCategoryButton(categoriesTree) {
        const addRootCategoryButton = this.createHTMLElement('button', {
            id: 'add-root-category',
            class: 'root-category-btn'
        }, 'Add root category');

        categoriesTree.appendChild(addRootCategoryButton);

        addRootCategoryButton.addEventListener('click', () => {
            document.getElementById('category-details').style.display = 'none';
            this.showCreateCategoryForm(false);
        });
    }

    // Loads categories from the server and renders them
    loadCategories() {
        Ajax.get('/getCategories')
            .then(data => {
                console.log('Received data:', data);
                this.categoryData = this.transformData(data);
                this.renderCategoryTree(data);
                this.addEventListeners();
            })
            .catch(error => console.error('Error fetching categories:', error));
    }

    // Renders the category tree in the UI
    renderCategoryTree(categories) {
        const ul = this.contentDiv.querySelector('#categories-tree ul');
        categories.forEach(category => {
            const li = this.createCategoryNode(category);
            ul.appendChild(li);
        });
    }

    // Transforms the received data into a hierarchical structure
    transformData(data) {
        const transformedData = {};

        function transformCategory(category, parent = null) {
            category.parent = parent;
            transformedData[category.id] = category;

            if (category.subcategories) {
                category.subcategories.forEach(subcategory => {
                    transformCategory(subcategory, category);
                });
            }
        }

        data.forEach(category => {
            transformCategory(category);
        });

        return transformedData;
    }

    // Creates a category node in the tree
    createCategoryNode(category) {
        const li = this.createHTMLElement('li', {'data-category-id': category.id});

        // Ako kategorija ima podkategorije, dodaj dugme za proširenje
        if (category.subcategories && category.subcategories.length > 0) {
            const toggleButton = this.createHTMLElement('button', {class: 'toggle-button'}, '+');
            li.appendChild(toggleButton);

            // Dodaj event listener za proširenje i sklapanje podkategorija
            toggleButton.addEventListener('click', () => {
                const subUl = li.querySelector('ul');
                if (subUl.style.display === 'none') {
                    subUl.style.display = 'block';
                    toggleButton.textContent = '-';
                } else {
                    subUl.style.display = 'none';
                    toggleButton.textContent = '+';
                }
            });
        }

        // Dodaj tekst kategorije
        li.appendChild(document.createTextNode(category.title));

        if (category.subcategories && category.subcategories.length > 0) {
            const subUl = this.createHTMLElement('ul', {}, '');
            subUl.style.display = 'none'; // Podkategorije su skriveno na početku

            category.subcategories.forEach(subcategory => {
                const subLi = this.createCategoryNode(subcategory);
                subUl.appendChild(subLi);
            });

            li.appendChild(subUl);
        }

        return li;
    }

    // // Creates a category node in the tree
    // createCategoryNode(category) {
    //     const li = this.createHTMLElement('li', {'data-category-id': category.id});
    //     li.textContent = category.title;
    //
    //     if (category.subcategories && category.subcategories.length > 0) {
    //         const subUl = this.createHTMLElement('ul');
    //         category.subcategories.forEach(subcategory => {
    //             const subLi = this.createCategoryNode(subcategory);
    //             subUl.appendChild(subLi);
    //         });
    //         li.appendChild(subUl);
    //     }
    //
    //     return li;
    // }

    // Appends fields to the category details section
    appendDetailsFields(container) {
        const titleLabel = this.createHTMLElement('label', {}, 'Title:');
        const titleInput = this.createHTMLElement('input', {type: 'text', id: 'category-title', readonly: true});

        const parentLabel = this.createHTMLElement('label', {}, 'Parent category:');
        const parentInput = this.createHTMLElement('input', {type: 'text', id: 'category-parent', readonly: true});

        const codeLabel = this.createHTMLElement('label', {}, 'Code:');
        const codeInput = this.createHTMLElement('input', {type: 'text', id: 'category-code', readonly: true});

        const descriptionLabel = this.createHTMLElement('label', {}, 'Description:');
        const descriptionTextarea = this.createHTMLElement('textarea', {id: 'category-description', readonly: true});

        const editButton = this.createHTMLElement('button', {id: 'edit-category'}, 'Edit');
        const deleteButton = this.createHTMLElement('button', {id: 'delete-category'}, 'Delete');
        const addSubcategoryButton = this.createHTMLElement('button', {id: 'add-subcategory'}, 'Add Subcategory');

        this.attachDetailFieldEventListeners(editButton, deleteButton, addSubcategoryButton, container);

        container.appendChild(titleLabel);
        container.appendChild(titleInput);
        container.appendChild(parentLabel);
        container.appendChild(parentInput);
        container.appendChild(codeLabel);
        container.appendChild(codeInput);
        container.appendChild(descriptionLabel);
        container.appendChild(descriptionTextarea);
        container.appendChild(editButton);
        container.appendChild(deleteButton);
        container.appendChild(addSubcategoryButton);
    }

    // Attaches event listeners to detail field buttons
    attachDetailFieldEventListeners(editButton, deleteButton, addSubcategoryButton, container) {
        addSubcategoryButton.addEventListener('click', () => {
            if (!this.selectedCategoryId) {
                alert('Please select a category before adding a subcategory.');
                return;
            }
            container.style.display = 'none';
            this.showCreateCategoryForm(true);
        });

        editButton.addEventListener('click', () => {
            if (this.selectedCategoryId) {
                const selectedCategory = this.categoryData[this.selectedCategoryId];
                container.style.display = 'none';
                this.showEditCategoryForm(selectedCategory);
            } else {
                alert('Please select a category to edit.');
                console.error('No category selected.');
            }
        });

        deleteButton.addEventListener('click', () => {
            if (this.selectedCategoryId) {
                if (confirm('Are you sure you want to delete this category?')) {
                    this.deleteCategory(this.selectedCategoryId);
                }
            } else {
                alert('Please select a category to delete.');
                console.error('No category selected.');
            }
        });
    }

    // Displays the form for creating a new category
    showCreateCategoryForm(isSubcategory = false) {
        const existingForm = document.getElementById('create-category-form');
        if (existingForm) {
            existingForm.remove();
        }

        const formContainer = this.createHTMLElement('div', {id: 'create-category-form', class: 'category-form'});

        const titleLabel = this.createHTMLElement('label', {}, 'Title:');
        const titleInput = this.createHTMLElement('input', {type: 'text', id: 'new-category-title'});

        const parentLabel = this.createHTMLElement('label', {}, 'Parent category:');
        const parentSelect = this.createHTMLElement('select', {id: 'new-category-parent'});

        if (isSubcategory) {
            this.addParentCategoryOption(parentSelect);
        } else {
            const rootOption = this.createHTMLElement('option', {value: 'root'}, 'Root');
            parentSelect.appendChild(rootOption);
        }

        const codeLabel = this.createHTMLElement('label', {}, 'Code:');
        const codeInput = this.createHTMLElement('input', {type: 'text', id: 'new-category-code'});

        const descriptionLabel = this.createHTMLElement('label', {}, 'Description:');
        const descriptionTextarea = this.createHTMLElement('textarea', {id: 'new-category-description'});

        const okButton = this.createHTMLElement('button', {id: 'save-category'}, 'OK');
        const cancelButton = this.createHTMLElement('button', {id: 'cancel-category'}, 'Cancel');

        this.attachFormEventListeners(okButton, cancelButton, titleInput, parentSelect, codeInput, descriptionTextarea);

        formContainer.appendChild(titleLabel);
        formContainer.appendChild(titleInput);
        formContainer.appendChild(parentLabel);
        formContainer.appendChild(parentSelect);
        formContainer.appendChild(codeLabel);
        formContainer.appendChild(codeInput);
        formContainer.appendChild(descriptionLabel);
        formContainer.appendChild(descriptionTextarea);
        formContainer.appendChild(okButton);
        formContainer.appendChild(cancelButton);

        this.contentDiv.appendChild(formContainer);
    }

    // Adds the parent category option to the select box if a subcategory is being added
    addParentCategoryOption(parentSelect) {
        if (this.selectedCategoryId) {
            const selectedCategory = this.categoryData[this.selectedCategoryId];
            const selectedOption = this.createHTMLElement('option', {
                value: this.selectedCategoryId,
                selected: true
            }, selectedCategory.title);
            parentSelect.appendChild(selectedOption);
        } else {
            console.error('No category selected for subcategory');
        }
    }

    // Attaches event listeners to the form buttons
    attachFormEventListeners(okButton, cancelButton, titleInput, parentSelect, codeInput, descriptionTextarea) {
        okButton.addEventListener('click', () => {
            if (!titleInput.value.trim()) {
                alert('Title is required');
                return;
            }

            if (!codeInput.value.trim()) {
                alert('Code is required');
                return;
            }
            this.saveNewCategory({
                title: titleInput.value,
                parent: parentSelect.value === 'root' ? null : parentSelect.value,
                code: codeInput.value,
                description: descriptionTextarea.value,
            });
            this.cancelCreateCategoryForm();
            this.showCategoryDetails();
        });

        cancelButton.addEventListener('click', () => {
            this.cancelCreateCategoryForm();
            this.showCategoryDetails();
        });
    }

    // Saves a new category by sending the data to the server
    saveNewCategory(categoryData) {
        Ajax.post('/addCategory', categoryData)
            .then(response => {
                console.log('Original response:', response);
                if (response.error) {
                    alert(response.error);
                    return;
                }
                this.cancelCreateCategoryForm();
                this.render(); // Re-render the categories after saving
            })
            .catch(error => {
                console.error('Error saving category:', error);
                console.log('Original response:', error.response || error);
            });
    }

    // Displays the category details section
    showCategoryDetails() {
        const categoryDetails = document.getElementById('category-details');
        if (categoryDetails) {
            categoryDetails.style.display = 'block';
        }
    }

    // Cancels the create category form and removes it from the DOM
    cancelCreateCategoryForm() {
        const form = document.getElementById('create-category-form');
        if (form) {
            form.remove();
        }
    }

    // Displays the form for editing a category
    showEditCategoryForm(selectedCategory) {
        const existingForm = document.getElementById('edit-category-form');
        if (existingForm) {
            existingForm.remove();
        }

        const formContainer = this.createHTMLElement('div', {id: 'edit-category-form', class: 'category-form'});

        const titleLabel = this.createHTMLElement('label', {}, 'Title:');
        const titleInput = this.createHTMLElement('input', {
            type: 'text',
            id: 'edit-category-title',
            value: selectedCategory.title,
            readonly: true
        });

        const parentLabel = this.createHTMLElement('label', {}, 'Parent category:');
        const parentSelect = this.createHTMLElement('select', {id: 'edit-category-parent'});

        this.addParentOptionsForEdit(parentSelect, selectedCategory);

        const codeLabel = this.createHTMLElement('label', {}, 'Code:');
        const codeInput = this.createHTMLElement('input', {
            type: 'text',
            id: 'edit-category-code',
            value: selectedCategory.code,
            readonly: true
        });

        const descriptionLabel = this.createHTMLElement('label', {}, 'Description:');
        const descriptionTextarea = this.createHTMLElement('textarea', {
            id: 'edit-category-description',
            readonly: true
        }, selectedCategory.description);

        const saveButton = this.createHTMLElement('button', {id: 'save-category'}, 'Save');
        const cancelButton = this.createHTMLElement('button', {id: 'cancel-category'}, 'Cancel');

        saveButton.addEventListener('click', () => {
            this.saveEditedCategory({
                id: selectedCategory.id,
                parent: parentSelect.value === 'root' ? null : parentSelect.value,
            });
        });

        cancelButton.addEventListener('click', () => {
            this.cancelEditCategoryForm();
            this.showCategoryDetails();
        });

        formContainer.appendChild(titleLabel);
        formContainer.appendChild(titleInput);
        formContainer.appendChild(parentLabel);
        formContainer.appendChild(parentSelect);
        formContainer.appendChild(codeLabel);
        formContainer.appendChild(codeInput);
        formContainer.appendChild(descriptionLabel);
        formContainer.appendChild(descriptionTextarea);
        formContainer.appendChild(saveButton);
        formContainer.appendChild(cancelButton);

        this.contentDiv.appendChild(formContainer);
    }

    // Adds parent category options in the edit form
    addParentOptionsForEdit(parentSelect, selectedCategory) {
        const noParentOption = this.createHTMLElement('option', {value: 'root'}, 'Root');
        parentSelect.appendChild(noParentOption);

        Object.values(this.categoryData).forEach(category => {
            if (category.id !== selectedCategory.id) { // Avoid the current category
                const option = this.createHTMLElement('option', {value: category.id}, category.title);
                parentSelect.appendChild(option);
            }
        });

        parentSelect.value = selectedCategory.parent ? selectedCategory.parent.id : 'root';
    }

    // Saves the edited category by sending the data to the server
    saveEditedCategory(categoryData) {
        Ajax.put(`/updateCategory`, categoryData)
            .then(response => {
                console.log('Category updated successfully:', response);
                this.cancelEditCategoryForm();
                this.render(); // Re-render the categories after editing
            })
            .catch(error => {
                console.error('Error updating category:', error);
            });
    }

    // Cancels the edit category form and removes it from the DOM
    cancelEditCategoryForm() {
        const form = document.getElementById('edit-category-form');
        if (form) {
            form.remove();
        }
    }

    // Deletes a category by sending a DELETE request to the server
    deleteCategory(categoryId) {
        const data = {id: categoryId};
        Ajax.delete('/deleteCategory', data)
            .then(response => {
                console.log('Category deleted successfully:', response);
                this.render(); // Re-render the categories after deletion
            })
            .catch(error => {
                console.error('Error deleting category:', error);
                alert(error.message || 'Error deleting category');
            });
    }

    // Adds event listeners for interacting with the category tree
    addEventListeners() {
        const categoriesTree = document.getElementById('categories-tree');
        const categoryTitle = document.getElementById('category-title');
        const categoryParent = document.getElementById('category-parent');
        const categoryCode = document.getElementById('category-code');
        const categoryDescription = document.getElementById('category-description');

        categoriesTree.addEventListener('click', (event) => {
            const targetLi = event.target.closest('li');
            if (!targetLi) return;

            const categoryId = event.target.closest('li').dataset.categoryId;
            console.log('Clicked category ID:', categoryId);

            if (categoryId) {
                this.selectedCategoryId = categoryId; // Store the ID of the selected category

                const selectedCategory = this.categoryData[categoryId];
                console.log('Selected category data:', selectedCategory);

                if (selectedCategory) {
                    categoryTitle.value = selectedCategory.title;
                    categoryParent.value = selectedCategory.parent ? selectedCategory.parent.title : '';
                    categoryCode.value = selectedCategory.code;
                    categoryDescription.value = selectedCategory.description;
                } else {
                    console.error(`Category ID ${categoryId} not found in categoryData.`);
                }
            }
        });
    }
}