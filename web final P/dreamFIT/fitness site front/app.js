// app.js — shared JS for DreamFIT front-end

// ===== BMI CALCULATOR =====
(function () {
  const form = document.getElementById("bmi-form");
  if (!form) return; // not on bmi page

  const weightEl = document.getElementById("bmi-weight");
  const heightEl = document.getElementById("bmi-height");
  const goalEl = document.getElementById("bmi-goal");
  const resultBox = document.getElementById("bmi-result");
  const valueEl = document.getElementById("bmi-value");
  const categoryEl = document.getElementById("bmi-category");
  const tipEl = document.getElementById("bmi-tip");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const w = parseFloat(weightEl.value);
    const h = parseFloat(heightEl.value);

    if (!w || !h) return;

    const hMeters = h / 100;
    const bmi = w / (hMeters * hMeters);
    const rounded = Math.round(bmi * 10) / 10;

    let category;
    if (bmi < 18.5) category = "Underweight";
    else if (bmi < 25) category = "Normal weight";
    else if (bmi < 30) category = "Overweight";
    else category = "Obesity";

    valueEl.textContent = `BMI: ${rounded}`;
    categoryEl.textContent = `Category: ${category}`;
    const goal = goalEl.value;

    let tip = "";
    if (goal === "cut") {
      tip = "For cutting, combine light calorie deficit with resistance training and enough protein.";
    } else if (goal === "bulk") {
      tip = "For bulking, eat in a small surplus and focus on progressive overload in your lifts.";
    } else {
      tip = "To maintain, keep your calories around maintenance and stay active.";
    }

    tipEl.textContent = tip;
    resultBox.classList.remove("hidden");
  });
})();

// ===== WORKOUT GOAL FILTER =====
(function () {
  const chips = document.querySelectorAll("[data-goal-filter]");
  if (!chips.length) return;

  const cards = document.querySelectorAll(".split-card");

  chips.forEach((chip) => {
    chip.addEventListener("click", () => {
      chips.forEach((c) => c.classList.remove("active"));
      chip.classList.add("active");
      const goal = chip.dataset.goalFilter;

      cards.forEach((card) => {
        if (goal === "all") {
          card.style.display = "";
        } else {
          const goals = (card.dataset.goal || "").split(" ");
          card.style.display = goals.includes(goal) ? "" : "none";
        }
      });
    });
  });
})();

// ===== NUTRITION CATEGORY FILTER =====
(function () {
  const chips = document.querySelectorAll("[data-food-filter]");
  if (!chips.length) return;

  const rows = document.querySelectorAll("[data-food-cat]");

  chips.forEach((chip) => {
    chip.addEventListener("click", () => {
      chips.forEach((c) => c.classList.remove("active"));
      chip.classList.add("active");
      const cat = chip.dataset.foodFilter;

      rows.forEach((row) => {
        if (cat === "all") {
          row.style.display = "";
        } else {
          const rowCats = (row.dataset.foodCat || "").split(" ");
          row.style.display = rowCats.includes(cat) ? "" : "none";
        }
      });
    });
  });
})();

// ===== PROGRESS TRACKER (localStorage) =====
(function () {
  const form = document.getElementById("progress-form");
  if (!form) return;

  const STORAGE_KEY = "dreamfit-progress-v1";

  const dateEl = document.getElementById("pg-date");
  const weightEl = document.getElementById("pg-weight");
  const noteEl = document.getElementById("pg-note");
  const bodyEl = document.getElementById("progress-body");
  const summaryBox = document.getElementById("progress-summary");
  const countEl = document.getElementById("pg-count");
  const changeEl = document.getElementById("pg-change");

  function loadEntries() {
    const raw = localStorage.getItem(STORAGE_KEY);
    return raw ? JSON.parse(raw) : [];
  }

  function saveEntries(entries) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(entries));
  }

  function render() {
    const entries = loadEntries();
    bodyEl.innerHTML = "";

    if (!entries.length) {
      summaryBox.classList.add("hidden");
      return;
    }

    entries.forEach((e, idx) => {
      const tr = document.createElement("tr");

      const tdDate = document.createElement("td");
      tdDate.textContent = e.date;

      const tdWeight = document.createElement("td");
      tdWeight.textContent = e.weight.toFixed(1);

      const tdNote = document.createElement("td");
      tdNote.textContent = e.note || "";

      const tdRemove = document.createElement("td");
      const btn = document.createElement("button");
      btn.textContent = "Delete";
      btn.addEventListener("click", () => {
        const fresh = loadEntries();
        fresh.splice(idx, 1);
        saveEntries(fresh);
        render();
      });
      tdRemove.appendChild(btn);

      tr.appendChild(tdDate);
      tr.appendChild(tdWeight);
      tr.appendChild(tdNote);
      tr.appendChild(tdRemove);

      bodyEl.appendChild(tr);
    });

    // Summary
    const weights = entries.map((e) => e.weight);
    const first = weights[0];
    const last = weights[weights.length - 1];
    const diff = Math.round((last - first) * 10) / 10;

    countEl.textContent = `Total entries: ${entries.length}`;
    if (diff === 0) {
      changeEl.textContent = "Weight change: 0.0 kg (no change yet).";
    } else if (diff > 0) {
      changeEl.textContent = `Weight change: +${diff} kg since first entry.`;
    } else {
      changeEl.textContent = `Weight change: ${diff} kg since first entry.`;
    }
    summaryBox.classList.remove("hidden");
  }

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const date = dateEl.value;
    const weight = parseFloat(weightEl.value);
    const note = noteEl.value.trim();

    if (!date || !weight) return;

    const entries = loadEntries();
    entries.push({ date, weight, note });
    entries.sort((a, b) => a.date.localeCompare(b.date)); // keep chronological
    saveEntries(entries);
    form.reset();
    render();
  });

  // set default date to today
  if (!dateEl.value) {
    const today = new Date().toISOString().split("T")[0];
    dateEl.value = today;
  }

  render();
})();
