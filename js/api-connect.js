const appId = 'c99c95c4';
const apiKey = '0f7faa64d1621ef51dcc90725dc45fc5';

const addProductBtn = document.querySelectorAll('.add-product-btn');
const searchInput = document.getElementById('search-input');
const searchBtn = document.getElementById('search-btn');
const searchResults = document.getElementById('search-results');
let activeMealPlan;

//Otwiera pop-up po kliknięciu przycisku 'dodaj produkt'
addProductBtn.forEach((button, index) => {
    button.addEventListener('click', () => {
        activeMealPlan = document.getElementById(`meal-plan-${index + 1}`)
        const productModal = new bootstrap.Modal(document.getElementById('productModal'));
        productModal.show();
    })
});


//Wyszukuje produktow po wpisaniu zapytania
searchBtn.addEventListener('click', async () => {
    const query = searchInput.value.trim();
    if(!query) {
        alert('Wpisz nazwę produktu!');
        return;
    }

    searchResults.innerHTML = '<li class="list-group-item">Szukam...</li>';

    try {

        const response = await fetch(`https://trackapi.nutritionix.com/v2/search/instant?query=${encodeURIComponent(query)}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'x-app-id': appId,
                'x-app-key': apiKey,
            },
        });

        if(!response.ok){
            throw new Error("Błąd pobierania danych");
        }

        const data = await response.json();
        displayResults(data.common);
    } catch(e) {
        searchResults.innerHTML = `<li class="list-group-item text-danger">${error.message}</li>`;
    }
});

function displayResults(products) {
    searchResults.innerHTML = '';

    if (products.length === 0) {
        searchResults.innerHTML = '<li class="list-group-item">Brak wyników</li';
        return;
    }

    products.forEach((product) => {
        //dla każdego zwróconego produktu dodajemy element na liście
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item search-product';
        listItem.innerHTML = `
          <span>${product.food_name}</span>
          <button class="btn btn-sm btn-primary product-btn">Dodaj</button>
        `;

        listItem.querySelector('button').addEventListener('click', () => {
            getFoodDetails(product.food_name);
        });

        searchResults.appendChild(listItem);
    });
}

async function getFoodDetails(foodName) {
    try {
        const response = await fetch(`https://trackapi.nutritionix.com/v2/natural/nutrients`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'x-app-id': appId,
                'x-app-key': apiKey,
            },
            body: JSON.stringify({query: foodName}),
        });

        if(!response.ok) {
            throw new Error('Błąd podczas pobierania szczegółów produktu');
        }

        const data = await response.json();
        const product = data.foods[0];
        addProductToMealPlan(product);
    } catch(e) {
        alert("Nie udało się dodać produktu");
    }
}

function addProductToMealPlan(product) {
    const listItem = document.createElement('li');
    listItem.className = 'list-group-item ';
    listItem.innerHTML = `
                        ${product.food_name} 
                        <span class="badge text-bg-primary rounded-pill">${product.nf_calories || '?'}kcal</span>
                        <span class="badge text-bg-primary rounded-pill">${product.nf_total_fat || '?'}T</span>
                        <span class="badge text-bg-primary rounded-pill">${product.nf_total_carbohydrate || '?'}W</span>
                        <span class="badge text-bg-primary rounded-pill">${product.nf_protein || '?'}B</span>`;
    if(activeMealPlan) {
        activeMealPlan.appendChild(listItem);
    }
}
