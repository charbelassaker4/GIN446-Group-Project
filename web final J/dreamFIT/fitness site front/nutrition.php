<?php ?>

<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>DreamFIT — Nutrition Guide</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- MAIN SITE STYLE -->

  <link rel="stylesheet" href="style.css" />

  <!-- NEW NUTRITION STYLE -->

  <link rel="stylesheet" href="nutrition.css" />

  <script type="module" src="../../firebase/state_listener.js"></script>

</head>

<body>
<header class="site-header">
  <div class="logo">Dream<span>FIT</span></div>
  <nav class="main-nav">
    <a href="index.html">Home</a>
    <a href="bmi.html">BMI</a>
    <a href="bodyfat.html">Body Fat %</a>
    <a href="workout.html">Workouts</a>
    <a href="nutrition.php" class="active">Nutrition</a>
    <a href="progress.html">Progress</a>
    <a href="profile.html">Profile</a>

```
<button id="logout-btn" class="pill-btn">Logout</button>
<script type="module" src="../../firebase/signout.js"></script>
```

  </nav>
</header>

<main class="page-wide">
  <h1>Nutrition & Calories</h1>
  <p class="page-intro">
    Search for any food to see its category, calories per 100g, and nutrition info.
  </p>

  <!-- SEARCH BAR -->

  <div class="search-area">
    <input id="searchInput" type="text" placeholder="Search for food (ex: chicken)" />
    <button id="searchBtn" class="search-btn">Search</button>
  </div>

  <!-- LOADING -->

  <div id="loading" class="loading hidden">Loading...</div>

  <!-- RESULT TABLE -->

  <section class="card-section">
    <div class="table-wrapper">
      <table class="food-table">
        <thead>
          <tr>
            <th>Photo</th>
            <th>Food</th>
            <th>Category</th>
            <th>Serving</th>
            <th>Calories</th>
          </tr>
        </thead>
        <tbody id="resultsBody">
          <!-- Injected by JS -->
        </tbody>
      </table>
    </div>
  </section>
</main>

<footer class="site-footer">
  <p>Values are approximate and for educational purposes only.</p>
</footer>

<script src="nutrition.js"></script>

</body>
</html>
