const searchInput = document.getElementById("searchInput");
const searchBtn = document.getElementById("searchBtn");
const resultsBody = document.getElementById("resultsBody");
const loading = document.getElementById("loading");

// Cache helper functions
function getCachedFood(query) {
const cache = JSON.parse(localStorage.getItem("foodCache") || "{}");
if (cache[query] && (Date.now() - cache[query].timestamp < 6 * 60 * 60 * 1000)) {
return cache[query].data;
}
return null;
}

function setCachedFood(query, data) {
const cache = JSON.parse(localStorage.getItem("foodCache") || "{}");
cache[query] = { data, timestamp: Date.now() };
localStorage.setItem("foodCache", JSON.stringify(cache));
}

// Replace this with your actual API endpoint and API key if needed
async function fetchFoodData(query) {
const cached = getCachedFood(query);
if (cached) return cached;

const url = `https://world.openfoodfacts.org/cgi/search.pl?search_terms=${encodeURIComponent(query)}&search_simple=1&action=process&json=1`;

const response = await fetch(url);
const json = await response.json();

// Map API data to expected format
const data = (json.products || []).map(item => ({
name: item.product_name || "Unknown",
category: (item.categories || "N/A").split(",")[0],
photo: item.image_small_url || "",
serving: item.serving_size || "100g",
calories: item.nutriments?.energy_kcal || "N/A"
}));

setCachedFood(query, data);
return data;
}

function renderResults(data) {
resultsBody.innerHTML = "";
if (!data || data.length === 0) {
resultsBody.innerHTML = "<tr><td colspan='5'>No results found</td></tr>";
return;
}

data.forEach(item => {
const tr = document.createElement("tr");
tr.innerHTML = `      <td>${item.photo ?`<img src="${item.photo}" alt="${item.name}" width="50">`: "N/A"}</td>       <td>${item.name}</td>       <td>${item.category}</td>       <td>${item.serving}</td>       <td>${item.calories}</td>
   `;
resultsBody.appendChild(tr);
});
}

searchBtn.addEventListener("click", async () => {
const query = searchInput.value.trim();
if (!query) return;

loading.classList.remove("hidden");
try {
const data = await fetchFoodData(query);
renderResults(data);
} catch (err) {
console.error(err);
resultsBody.innerHTML = "<tr><td colspan='5'>Error loading data</td></tr>";
} finally {
loading.classList.add("hidden");
}
});
