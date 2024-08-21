// categories.js
class Categories {
    constructor() {
        this.contentDiv = document.getElementById('content');
        this.categoryData = {};
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

    createCategoryNode(category) {
        const li = this.createHTMLElement('li', {'data-category-id': category.id});
        li.textContent = category.title;

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

    transformData(data) {
        const transformedData = {};
        data.forEach(category => {
            transformedData[category.id] = category;
            if (category.subcategories) {
                category.subcategories.forEach(subcategory => {
                    subcategory.parent = category;
                    transformedData[subcategory.id] = subcategory;
                });
            }
        });
        return transformedData;
    }

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