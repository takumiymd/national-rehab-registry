function showQuery(id, link) {
  let panels = document.querySelectorAll(".query-panel");
  for (let i = 0; i < panels.length; i++) {
    panels[i].classList.add("hidden");
  }

  let links = document.querySelectorAll(".nav-item");
  for (let i = 0; i < links.length; i++) {
    links[i].classList.remove("active");
  }

  document.getElementById(id).classList.remove("hidden");
  link.classList.add("active");

  return false;
}

function runQuery(event, resultId) {
  event.preventDefault();

  let form = event.target;
  let resultBox = document.getElementById(resultId);

  resultBox.innerHTML =
    '<p style="color: #666; font-size: 13px;">Running...</p>';

  let data = new FormData(form);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/query.php", true);

  xhr.onload = function () {
    if (xhr.status === 200) {
      resultBox.innerHTML =
        '<p class="result-label">Result</p>' + xhr.responseText;
    } else {
      resultBox.innerHTML =
        '<p class="result-msg error">Server error: ' + xhr.status + "</p>";
    }
  };

  xhr.onerror = function () {
    resultBox.innerHTML =
      '<p class="result-msg error">Could not connect. Make sure XAMPP is running.</p>';
  };

  xhr.send(data);
}

// helper to read a field value from a form, returns '...' if empty
function getVal(form, name) {
  let input = form.querySelector('[name="' + name + '"]');
  if (input && input.value.trim() != "") {
    return input.value.trim();
  }
  return "...";
}

function updateSQL1() {
  let form = document.querySelector("#q1 form");
  let field = getVal(form, "fieldName");
  let table = getVal(form, "tableName");
  document.getElementById("sql1").textContent =
    "SELECT " + field + "\nFROM " + table;
}

function updateSQL2() {
  let form = document.querySelector("#q2 form");
  let field = getVal(form, "selectField");
  let table = getVal(form, "selectTable");
  let cField = getVal(form, "conditionField");
  let cVal = getVal(form, "conditionValue");
  document.getElementById("sql2").textContent =
    "SELECT " +
    field +
    "\nFROM " +
    table +
    "\nWHERE " +
    cField +
    " = '" +
    cVal +
    "'";
}

function updateSQL3() {
  let form = document.querySelector("#q3 form");
  let t1 = getVal(form, "table1");
  let t2 = getVal(form, "table2");
  let f1 = getVal(form, "joinField1");
  let f2 = getVal(form, "joinField2");
  document.getElementById("sql3").textContent =
    "SELECT *\nFROM " +
    t1 +
    "\nJOIN " +
    t2 +
    "\nON " +
    t1 +
    "." +
    f1 +
    " = " +
    t2 +
    "." +
    f2;
}

function updateSQL5a() {
  let form = document.querySelector("#q5a form");
  let fn = getVal(form, "aggFunction1");
  let field = getVal(form, "aggField1");
  let table = getVal(form, "aggTable1");
  document.getElementById("sql5a").textContent =
    "SELECT " + fn + "(" + field + ") AS CountResult\nFROM " + table;
}

function updateSQL5b() {
  let form = document.querySelector("#q5b form");
  let fn = getVal(form, "aggFunction2");
  let field = getVal(form, "aggField2");
  let table = getVal(form, "aggTable2");
  document.getElementById("sql5b").textContent =
    "SELECT " + fn + "(" + field + ") AS MaxResult\nFROM " + table;
}

function updateSQL6() {
  let form = document.querySelector("#q6 form");
  let fn = getVal(form, "groupFunction");
  let aggField = getVal(form, "groupAggField");
  let table = getVal(form, "groupTable");
  let groupField = getVal(form, "groupField");
  document.getElementById("sql6").textContent =
    "SELECT " +
    groupField +
    ", " +
    fn +
    "(" +
    aggField +
    ") AS AggregatedValue\nFROM " +
    table +
    "\nGROUP BY " +
    groupField;
}

function updateSQL7() {
  let form = document.querySelector("#q7 form");
  let table = getVal(form, "deleteTable");
  let field = getVal(form, "deleteField");
  let val = getVal(form, "deleteValue");
  document.getElementById("sql7").textContent =
    "DELETE FROM " + table + "\nWHERE " + field + " = '" + val + "'";
}

function updateSQL8() {
  let form = document.querySelector("#q8 form");
  let table = getVal(form, "updateTable");
  let idField = getVal(form, "updateIDField");
  let idVal = getVal(form, "updateIDValue");
  let updateField = getVal(form, "updateField");
  let newVal = getVal(form, "newValue");
  document.getElementById("sql8").textContent =
    "UPDATE " +
    table +
    "\nSET " +
    updateField +
    " = '" +
    newVal +
    "'\nWHERE " +
    idField +
    " = '" +
    idVal +
    "'";
}
