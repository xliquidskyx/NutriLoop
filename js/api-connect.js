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
        saveMealToDatabase(product);
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


async function saveMealToDatabase(product) {
    const kalorie = product.nf_calories || 0;
    const bialko = product.nf_protein || 0;
    const tluszcz = product.nf_total_fat || 0;
    const weglowodany = product.nf_total_carbohydrate;
    const nazwa = product.food_name;
    const posilek = getMealTypeFromMealPlan(activeMealPlan.id);

    try {
        const response = await fetch('../php/plan_zywieniowy.php', {
            method:'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                posilek: posilek,
                kalorie: kalorie,
                bialko: bialko,
                tluszcz: tluszcz,
                weglowodany: weglowodany,
                nazwa: nazwa,
            }),
        });
        const data = await response.json();

        if (!data.success) {
            console.error('Błąd zapisu:', data.message);
        } else {
            fetchCalorieSummary();
        }

    } catch(e) {
        console.log('bład dodawania do bazy: ' + e.message)
    }
}

function getMealTypeFromMealPlan(mealPlanId) {
    switch (mealPlanId) {
        case 'meal-plan-1': return 'śniadanie';
        case 'meal-plan-2': return 'obiad';
        case 'meal-plan-3': return 'kolacja';
        default: return 'unknown';
    }
}


async function fetchCalorieSummary() {
    try {
        const response = await fetch('../php/plan_zywieniowy.php?action=fetch_totals', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('sniadanie-total').textContent = 'Suma: ' + data.totals.sniadanie + 'kcal';
            document.getElementById('obiad-total').textContent = 'Suma: ' + data.totals.obiad + 'kcal';
            document.getElementById('kolacja-total').textContent = 'Suma: ' + data.totals.kolacja + 'kcal';
        } else {
            console.error('Błąd pobierania podsumowania:', data.message);
        }
    } catch (error) {
        console.error('Błąd podczas pobierania podsumowania kalorii:', error);
    }
}

async function fetchProducts() {
    try {
        const response = await fetch('../php/plan_zywieniowy.php?action=fetch_products', {
            method: 'GET',
        });

        const data = await response.json();
        if (data.success) {
            renderProducts(data.products);
        } else {
            console.error('Błąd pobierania produktów:', data.message);
        }
    } catch (error) {
        console.error('Błąd podczas pobierania produktów:', error);
    }
}

function renderProducts(products) {
    // Oczyść listy
    document.getElementById('meal-plan-1').innerHTML = '';
    document.getElementById('meal-plan-2').innerHTML = '';
    document.getElementById('meal-plan-3').innerHTML = '';

    products.forEach(product => {
        const listItem = document.createElement('li');
        listItem.className = 'list-group-item';
        listItem.innerHTML = `
            ${product.nazwa} 
            <span class="badge text-bg-primary rounded-pill">${product.kalorie} kcal</span>
            <span class="badge text-bg-primary rounded-pill">${product.tluszcz} T</span>
            <span class="badge text-bg-primary rounded-pill">${product.weglowodany} W</span>
            <span class="badge text-bg-primary rounded-pill">${product.bialko} B</span>
        `;

        // Dodaj produkt do odpowiedniej sekcji
        const mealPlanId = getMealPlanId(product.posilek);
        document.getElementById(mealPlanId).appendChild(listItem);
    });
}

// Mapowanie typu posiłku na ID sekcji
function getMealPlanId(mealType) {
    switch (mealType) {
        case 'sniadanie': return 'meal-plan-1';
        case 'obiad': return 'meal-plan-2';
        case 'kolacja': return 'meal-plan-3';
        default: return '';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    fetchCalorieSummary();
    fetchProducts(); // Pobierz i wyświetl produkty po załadowaniu strony
}); 