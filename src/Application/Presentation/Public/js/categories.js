// categories.js
class Categories {
    constructor() {
        this.contentDiv = document.getElementById('content');
        this.categoryData = {};
        this.selectedCategoryId = null;
    }

    createHTMLElement(tag, attributes = {}, textContent = '') {
        const element = document.createElement(tag);
        Object.keys(attributes).forEach(key => element.setAttribute(key, attributes[key]));
        if (textContent) {
            element.textContent = textContent;
        }
        return element;
    }

    render() {
        this.contentDiv.innerHTML = '';

        const categoriesContainer = this.createHTMLElement('div', {id: 'categories-container'});

        const categoriesTree = this.createHTMLElement('div', {id: 'categories-tree'});
        const ul = this.createHTMLElement('ul');

        categoriesTree.appendChild(ul);
        categoriesContainer.appendChild(categoriesTree);

        const categoryDetails = this.createHTMLElement('div', {id: 'category-details'});
        this.appendDetailsFields(categoryDetails);

        categoriesContainer.appendChild(categoryDetails);
        this.contentDiv.appendChild(categoriesContainer);

        const addRootCategoryButton = this.createHTMLElement('button', {
            id: 'add-root-category',
            class: 'root-category-btn'
        }, 'Add root category');
        categoriesTree.appendChild(addRootCategoryButton);

        addRootCategoryButton.addEventListener('click', () => {
            categoryDetails.style.display = 'none'; // Hide category details
            this.showCreateCategoryForm(false);
        });

        Ajax.get('/getCategories')
            .then(data => {
                console.log('Received data:', data);

                this.categoryData = this.transformData(data);
                data.forEach(category => {
                    const li = this.createCategoryNode(category);
                    ul.appendChild(li);
                });
                this.addEventListeners();
            })
            .catch(error => console.error('Error fetching categories:', error));
    }

    showCreateCategoryForm($isSubcategory = false) {
        const existingForm = document.getElementById('create-category-form');
        if (existingForm) {
            existingForm.remove();
        }

        const formContainer = this.createHTMLElement('div', {id: 'create-category-form', class: 'category-form'});

        const titleLabel = this.createHTMLElement('label', {}, 'Title:');
        const titleInput = this.createHTMLElement('input', {type: 'text', id: 'new-category-title'});

        const parentLabel = this.createHTMLElement('label', {}, 'Parent category:');
        const parentSelect = this.createHTMLElement('select', {id: 'new-category-parent'});

        if ($isSubcategory) {
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
        } else {
            // Ako se dodaje root kategorija, prikaži samo opciju za root
            const rootOption = this.createHTMLElement('option', {value: 'root'}, 'Root');
            parentSelect.appendChild(rootOption);
        }

        const codeLabel = this.createHTMLElement('label', {}, 'Code:');
        const codeInput = this.createHTMLElement('input', {type: 'text', id: 'new-category-code'});

        const descriptionLabel = this.createHTMLElement('label', {}, 'Description:');
        const descriptionTextarea = this.createHTMLElement('textarea', {id: 'new-category-description'});

        const okButton = this.createHTMLElement('button', {id: 'save-category'}, 'OK');
        const cancelButton = this.createHTMLElement('button', {id: 'cancel-category'}, 'Cancel');

        okButton.addEventListener('click', () => {
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

    saveNewCategory(categoryData) {
        Ajax.post('/addCategory', categoryData)
            .then(response => {
                console.log('Original response:', response);
                if (response.error) {
                    alert(response.error);
                    return;
                }
                this.cancelCreateCategoryForm();
                this.render();
            })
            .catch(error => {
                console.error('Error saving category:', error);
                console.log('Original response:', error.response || error);
            });
    }

    showCategoryDetails() {
        const categoryDetails = document.getElementById('category-details');
        if (categoryDetails) {
            categoryDetails.style.display = 'block';
        }
    }

    cancelCreateCategoryForm() {
        const form = document.getElementById('create-category-form');
        if (form) {
            form.remove();
        }
    }

    createCategoryNode(category) {
        const li = this.createHTMLElement('li', {'data-category-id': category.id});
        li.textContent = category.title;

        console.log(`Rendering category: ${category.title} with ${category.subcategories ? category.subcategories.length : 0} subcategories`);

        if (category.subcategories && category.subcategories.length > 0) {
            const subUl = this.createHTMLElement('ul');
            category.subcategories.forEach(subcategory => {
                const subLi = this.createCategoryNode(subcategory);
                subUl.appendChild(subLi);
            });
            li.appendChild(subUl);
        }

        return li;
    }

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
                console.error('No category selected.');            }
        });

        deleteButton.addEventListener('click', () => {
            if (this.selectedCategoryId) {
                const selectedCategory = this.categoryData[this.selectedCategoryId];

                // container.style.display = 'none';

                if (confirm('Are you sure you want to delete this category?')) {
                    this.deleteCategory(this.selectedCategoryId);
                }
            } else {
                alert('Please select a category to delete.');
                console.error('No category selected.');            }
        });

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

    deleteCategory(categoryId) {
        const data = { id: categoryId };
        Ajax.delete('/deleteCategory', data)
            .then(response => {
                console.log('Category deleted successfully:', response);
                this.render();
            })
            .catch(error => {
                console.error('Error deleting category:', error);
                alert(error.message || 'Error deleting category');
            });
    }

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

        const noParentOption = this.createHTMLElement('option', {value: 'root'}, 'Root');
        parentSelect.appendChild(noParentOption);

        // Dodaj sve dostupne kategorije u dropdown osim trenutne
        Object.values(this.categoryData).forEach(category => {
            if (category.id !== selectedCategory.id) { // Izbegnite trenutnu kategoriju
                const option = this.createHTMLElement('option', {value: category.id}, category.title);
                parentSelect.appendChild(option);
            }
        });

        // Postavite trenutno izabranog roditelja u dropdown
        parentSelect.value = selectedCategory.parent ? selectedCategory.parent.id : 'root';

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

    saveEditedCategory(categoryData) {
        Ajax.put(`/updateCategory`, categoryData)
            .then(response => {
                console.log('Category updated successfully:', response);
                this.cancelEditCategoryForm();
                this.render();
            })
            .catch(error => {
                console.error('Error updating category:', error);
            });
    }

    cancelEditCategoryForm() {
        const form = document.getElementById('edit-category-form');
        if (form) {
            form.remove();
        }
    }

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

    // transformData(data) {
    //     const transformedData = {};
    //     data.forEach(category => {
    //         transformedData[category.id] = category;
    //         if (category.subcategories) {
    //             category.subcategories.forEach(subcategory => {
    //                 subcategory.parent = category;
    //                 transformedData[subcategory.id] = subcategory;
    //             });
    //         }
    //     });
    //
    //     return transformedData;
    // }

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
                this.selectedCategoryId = categoryId; // Sačuvaj ID selektovane kategorije

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