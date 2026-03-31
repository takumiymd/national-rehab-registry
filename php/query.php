<?php
require_once __DIR__ . "/database.php";

$allowedTables = [
    "Person",
    "Staff",
    "TreatmentPlan",
    "PatientMonitors",
    "SpecialistResponsible",
    "TreatmentPlanAssigned"
];

$allowedFields = [
    "SSN",
    "Name",
    "Phone_number",
    "Postal_code",
    "Address",
    "SID",
    "SUPERVISOR_ID",
    "TID",
    "Role",
    "PID",
    "Type_of_Addiction",
    "Progress_of_Recovery",
    "DurationTreatment",
    "TreatmentName",
    "Description",
    "Dosage_level"

];

function isAllowed($value, $allowedList) {
    return in_array($value, $allowedList, true);
}

function printTable($result) {
    if (!$result || $result->num_rows === 0) {
        echo "<p class='result-msg error'>No results found.</p>";
        return;
    }

    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    
    $firstRow = $result->fetch_assoc();
    echo "<tr>";
    foreach ($firstRow as $column => $value) {
        echo "<th>" . htmlspecialchars($column) . "</th>";
    }
    echo "</tr>";

    echo "<tr>";
    foreach ($firstRow as $value) {
        echo "<td>" . htmlspecialchars((string)$value) . "</td>";
    }
    echo "</tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars((string)$value) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<p class='result-msg error'>Invalid request.</p>";
    exit();
}

if (!isset($_POST["queryType"])) {
    echo "<p class='result-msg error'>Missing query type.</p>";
    exit();
}

$queryType = $_POST["queryType"];

switch ($queryType) {

    case "projection":
        $fieldName = trim($_POST["fieldName"] ?? "");
        $tableName = trim($_POST["tableName"] ?? "");

        if (!isAllowed($fieldName, $allowedFields) || !isAllowed($tableName, $allowedTables)) {
            echo "<p class='result-msg error'>Invalid field name or table name.</p>";
            break;
        }

        $sql = "SELECT $fieldName FROM $tableName";
        $result = $conn->query($sql);
        printTable($result);
        break;

    case "selection":
        $selectField = trim($_POST["selectField"] ?? "");
        $selectTable = trim($_POST["selectTable"] ?? "");
        $conditionField = trim($_POST["conditionField"] ?? "");
        $conditionValue = trim($_POST["conditionValue"] ?? "");

        if (
            !isAllowed($selectField, $allowedFields) ||
            !isAllowed($selectTable, $allowedTables) ||
            !isAllowed($conditionField, $allowedFields)
        ) {
            echo "<p class='result-msg error'>Invalid input for selection query.</p>";
            break;
        }

        $sql = "SELECT $selectField FROM $selectTable WHERE $conditionField = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<p class='result-msg error'>Failed to prepare selection query.</p>";
            break;
        }

        $stmt->bind_param("s", $conditionValue);
        $stmt->execute();
        $result = $stmt->get_result();
        printTable($result);
        $stmt->close();
        break;

    case "join":
        $table1 = trim($_POST["table1"] ?? "");
        $table2 = trim($_POST["table2"] ?? "");
        $joinField1 = trim($_POST["joinField1"] ?? "");
        $joinField2 = trim($_POST["joinField2"] ?? "");

        if (
            !isAllowed($table1, $allowedTables) ||
            !isAllowed($table2, $allowedTables) ||
            !isAllowed($joinField1, $allowedFields) ||
            !isAllowed($joinField2, $allowedFields)
        ) {
            echo "<p class='result-msg error'>Invalid input for join query.</p>";
            break;
        }

        $sql = "SELECT * FROM $table1
                JOIN $table2
                ON $table1.$joinField1 = $table2.$joinField2";

        $result = $conn->query($sql);
        printTable($result);
        break;

    case "division":

        $sql = "
            SELECT sr.SID
            FROM SpecialistResponsible sr
            GROUP BY sr.SID
            HAVING COUNT(DISTINCT sr.TID) = (SELECT COUNT(*) FROM TreatmentPlan)
        ";

        $result = $conn->query($sql);
        echo "<h2>Division Query Result</h2>";
        echo "<p class='result-msg'>Staff responsible for all treatment plans:</p>";
        printTable($result);
        break;

    case "aggregation1":
        $aggFunction1 = strtoupper(trim($_POST["aggFunction1"] ?? ""));
        $aggField1 = trim($_POST["aggField1"] ?? "");
        $aggTable1 = trim($_POST["aggTable1"] ?? "");

        if (
            $aggFunction1 !== "COUNT" ||
            !isAllowed($aggField1, $allowedFields) ||
            !isAllowed($aggTable1, $allowedTables)
        ) {
            echo "<p class='result-msg error'>Invalid input for COUNT query.</p>";
            break;
        }

        $sql = "SELECT COUNT($aggField1) AS CountResult FROM $aggTable1";
        $result = $conn->query($sql);
        printTable($result);
        break;

    case "aggregation2":
        $aggFunction2 = strtoupper(trim($_POST["aggFunction2"] ?? ""));
        $aggField2 = trim($_POST["aggField2"] ?? "");
        $aggTable2 = trim($_POST["aggTable2"] ?? "");

        if (
            $aggFunction2 !== "MAX" ||
            !isAllowed($aggField2, $allowedFields) ||
            !isAllowed($aggTable2, $allowedTables)
        ) {
            echo "<p class='result-msg error'>Invalid input for MAX query.</p>";
            break;
        }

        $sql = "SELECT MAX($aggField2) AS MaxResult FROM $aggTable2";
        $result = $conn->query($sql);
        printTable($result);
        break;

    case "groupby":
        $groupFunction = strtoupper(trim($_POST["groupFunction"] ?? ""));
        $groupAggField = trim($_POST["groupAggField"] ?? "");
        $groupTable = trim($_POST["groupTable"] ?? "");
        $groupField = trim($_POST["groupField"] ?? "");

        $allowedFunctions = ["COUNT", "MAX", "MIN", "AVG", "SUM"];

        if (
            !in_array($groupFunction, $allowedFunctions, true) ||
            !isAllowed($groupAggField, $allowedFields) ||
            !isAllowed($groupTable, $allowedTables) ||
            !isAllowed($groupField, $allowedFields)
        ) {
            echo "<p class='result-msg error'>Invalid input for GROUP BY query.</p>";
            break;
        }

        $sql = "SELECT $groupField, $groupFunction($groupAggField) AS AggregatedValue
                FROM $groupTable
                GROUP BY $groupField";

        $result = $conn->query($sql);
        printTable($result);
        break;

    case "delete":
        $deleteTable = trim($_POST["deleteTable"] ?? "");
        $deleteField = trim($_POST["deleteField"] ?? "");
        $deleteValue = trim($_POST["deleteValue"] ?? "");

        if (
            !isAllowed($deleteTable, $allowedTables) ||
            !isAllowed($deleteField, $allowedFields)
        ) {
            echo "<p class='result-msg error'>Invalid input for DELETE query.</p>";
            break;
        }

        $sql = "DELETE FROM $deleteTable WHERE $deleteField = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<p class='result-msg error'>Failed to prepare delete query.</p>";
            break;
        }

        $stmt->bind_param("s", $deleteValue);

        if ($stmt->execute()) {
            echo "<p class='result-msg success'>Record deleted successfully.</p>";
        } else {
            echo "<p>Delete failed: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        break;

    case "update":
        $updateTable = trim($_POST["updateTable"] ?? "");
        $updateIDField = trim($_POST["updateIDField"] ?? "");
        $updateIDValue = trim($_POST["updateIDValue"] ?? "");
        $updateField = trim($_POST["updateField"] ?? "");
        $newValue = trim($_POST["newValue"] ?? "");

        if (
            !isAllowed($updateTable, $allowedTables) ||
            !isAllowed($updateIDField, $allowedFields) ||
            !isAllowed($updateField, $allowedFields)
        ) {
            echo "<p class='result-msg error'>Invalid input for UPDATE query.</p>";
            break;
        }

        $sql = "UPDATE $updateTable SET $updateField = ? WHERE $updateIDField = ?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "<p class='result-msg error'>Failed to prepare update query.</p>";
            break;
        }

        $stmt->bind_param("ss", $newValue, $updateIDValue);

        if ($stmt->execute()) {
            echo "<p class='result-msg success'>Record updated successfully.</p>";
        } else {
            echo "<p>Update failed: " . htmlspecialchars($stmt->error) . "</p>";
        }

        $stmt->close();
        break;

    default:
        echo "<p class='result-msg error'>Unknown query type.</p>";
        break;
}

$conn->close();
?>
