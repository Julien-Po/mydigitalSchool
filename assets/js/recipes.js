document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('ingredients-form');
    const checkboxes = form.querySelectorAll('.ingredient-checkbox');
    const selectedIngredientsContainer = document.getElementById('selected-ingredients-container');
    const selectedIngredientsList = document.getElementById('selected-ingredients-list');
    const genreSelect = document.getElementById('genre-select');
    const ingredientsContainer = document.getElementById('ingredients-container');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            updateSelectedIngredients();
        });
    });

    genreSelect.addEventListener('change', () => {
        filterIngredientsByGenre();
    });

    function updateSelectedIngredients() {
        selectedIngredientsList.innerHTML = '';
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const label = checkbox.previousElementSibling.previousElementSibling.textContent;
                const listItem = document.createElement('h3');
                listItem.textContent = label;
                selectedIngredientsList.appendChild(listItem);
            }
        });
    }

    function filterIngredientsByGenre() {
        const selectedGenre = genreSelect.value;
        const cards = ingredientsContainer.querySelectorAll('.card');

        cards.forEach(card => {
            if (selectedGenre === 'all' || card.getAttribute('data-genre') === selectedGenre) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    filterIngredientsByGenre();
});