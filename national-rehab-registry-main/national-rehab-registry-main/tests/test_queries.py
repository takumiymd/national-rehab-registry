#!/usr/bin/env python3
"""
Manual integration tests for National Rehab Registry.

These tests send POST requests to the live PHP backend
served by XAMPP / Apache and verify the returned HTML
contains expected values.

Before running:
1. Start XAMPP Apache + MariaDB
2. Make sure the database is imported
3. Make sure the app is available at:
   http://localhost/national-rehab-registry/php/query.php

Run:
    python3 tests/test_queries.py
"""

from __future__ import annotations

import sys
from dataclasses import dataclass
from typing import Dict, List

import requests


BASE_URL = "http://localhost/national-rehab-registry/php/query.php"
TIMEOUT = 8


@dataclass
class TestCase:
    name: str
    data: Dict[str, str]
    expected_contains: List[str]
    unexpected_contains: List[str] | None = None
    expected_status: int = 200


def run_test(case: TestCase) -> bool:
    try:
        response = requests.post(BASE_URL, data=case.data, timeout=TIMEOUT)
    except requests.RequestException as exc:
        print(f"[ERROR] {case.name}")
        print(f"        Request failed: {exc}")
        return False

    if response.status_code != case.expected_status:
        print(f"[FAIL]  {case.name}")
        print(
            f"        Expected HTTP {case.expected_status}, got {response.status_code}"
        )
        print(f"        Response preview: {response.text[:300]!r}")
        return False

    body = response.text

    for text in case.expected_contains:
        if text not in body:
            print(f"[FAIL]  {case.name}")
            print(f"        Missing expected text: {text!r}")
            print(f"        Response preview: {body[:500]!r}")
            return False

    if case.unexpected_contains:
        for text in case.unexpected_contains:
            if text in body:
                print(f"[FAIL]  {case.name}")
                print(f"        Found unexpected text: {text!r}")
                print(f"        Response preview: {body[:500]!r}")
                return False

    print(f"[PASS]  {case.name}")
    return True


def main() -> int:
    safe_test_cases = [
        # Projection
        TestCase(
            name="Projection returns Person names",
            data={
                "queryType": "projection",
                "fieldName": "Name",
                "tableName": "Person",
            },
            expected_contains=["Armaan", "Quang", "Takumi", "Kunal"],
        ),
        TestCase(
            name="Projection rejects invalid field",
            data={
                "queryType": "projection",
                "fieldName": "Takumi",
                "tableName": "Person",
            },
            expected_contains=["Invalid field name or table name"],
        ),
        # Selection
        TestCase(
            name="Selection returns Armaan for SSN 1001",
            data={
                "queryType": "selection",
                "selectField": "Name",
                "selectTable": "Person",
                "conditionField": "SSN",
                "conditionValue": "1001",
            },
            expected_contains=["Armaan"],
            unexpected_contains=["No results found"],
        ),
        TestCase(
            name="Selection returns Takumi for SSN 1003",
            data={
                "queryType": "selection",
                "selectField": "Name",
                "selectTable": "Person",
                "conditionField": "SSN",
                "conditionValue": "1003",
            },
            expected_contains=["Takumi"],
            unexpected_contains=["No results found"],
        ),
        TestCase(
            name="Selection returns no results for missing SSN",
            data={
                "queryType": "selection",
                "selectField": "Name",
                "selectTable": "Person",
                "conditionField": "SSN",
                "conditionValue": "9999",
            },
            expected_contains=["No results found"],
        ),
        # Join
        TestCase(
            name="Join Staff with SpecialistResponsible on SID",
            data={
                "queryType": "join",
                "table1": "Staff",
                "table2": "SpecialistResponsible",
                "joinField1": "SID",
                "joinField2": "SID",
            },
            expected_contains=["Nurse", "Doctor", "Caretaker"],
        ),
        TestCase(
            name="Join with mismatched fields returns no results",
            data={
                "queryType": "join",
                "table1": "Person",
                "table2": "Staff",
                "joinField1": "Name",
                "joinField2": "SID",
            },
            expected_contains=["No results found"],
        ),
        # Division
        TestCase(
            name="Division returns no results for current dataset",
            data={
                "queryType": "division",
                "divisionInput": "all treatment plans",
            },
            expected_contains=["Division Query Result", "No results found"],
        ),
        # COUNT
        TestCase(
            name="COUNT returns number of PatientMonitors rows",
            data={
                "queryType": "aggregation1",
                "aggFunction1": "COUNT",
                "aggField1": "PID",
                "aggTable1": "PatientMonitors",
            },
            expected_contains=["CountResult", "5"],
        ),
        TestCase(
            name="COUNT rejects invalid function",
            data={
                "queryType": "aggregation1",
                "aggFunction1": "MAX",
                "aggField1": "PID",
                "aggTable1": "PatientMonitors",
            },
            expected_contains=["Invalid input for COUNT query"],
        ),
        # MAX
        TestCase(
            name="MAX returns highest TID from TreatmentPlan",
            data={
                "queryType": "aggregation2",
                "aggFunction2": "MAX",
                "aggField2": "TID",
                "aggTable2": "TreatmentPlan",
            },
            expected_contains=["MaxResult", "4005"],
        ),
        # Group By
        TestCase(
            name="GROUP BY counts PID per SID in PatientMonitors",
            data={
                "queryType": "groupby",
                "groupFunction": "COUNT",
                "groupAggField": "PID",
                "groupTable": "PatientMonitors",
                "groupField": "SID",
            },
            expected_contains=[
                "AggregatedValue",
                "2001",
                "2002",
                "2003",
                "2004",
                "2005",
            ],
        ),
        TestCase(
            name="GROUP BY rejects invalid function",
            data={
                "queryType": "groupby",
                "groupFunction": "HELLO",
                "groupAggField": "PID",
                "groupTable": "PatientMonitors",
                "groupField": "SID",
            },
            expected_contains=["Invalid input for GROUP BY query"],
        ),
    ]

    destructive_test_cases = [
        TestCase(
            name="UPDATE changes Address for SSN 1003",
            data={
                "queryType": "update",
                "updateTable": "Person",
                "updateIDField": "SSN",
                "updateIDValue": "1003",
                "updateField": "Address",
                "newValue": "999 New Ave",
            },
            expected_contains=["Record updated successfully"],
        ),
        TestCase(
            name="Verify UPDATE changed Address for SSN 1003",
            data={
                "queryType": "selection",
                "selectField": "Address",
                "selectTable": "Person",
                "conditionField": "SSN",
                "conditionValue": "1003",
            },
            expected_contains=["999 New Ave"],
        ),
        TestCase(
            name="DELETE removes PatientMonitors row PID 8005",
            data={
                "queryType": "delete",
                "deleteTable": "PatientMonitors",
                "deleteField": "PID",
                "deleteValue": "8005",
            },
            expected_contains=["Record deleted successfully"],
        ),
        TestCase(
            name="Verify DELETE removed PID 8005",
            data={
                "queryType": "selection",
                "selectField": "PID",
                "selectTable": "PatientMonitors",
                "conditionField": "PID",
                "conditionValue": "8005",
            },
            expected_contains=["No results found"],
        ),
    ]

    test_cases = safe_test_cases
    # To include destructive tests, use:
    # test_cases = safe_test_cases + destructive_test_cases

    print(f"Running {len(test_cases)} test(s) against:")
    print(f"  {BASE_URL}\n")

    passed = 0
    failed = 0

    for case in test_cases:
        ok = run_test(case)
        if ok:
            passed += 1
        else:
            failed += 1

    print("\n--- Summary ---")
    print(f"Passed: {passed}")
    print(f"Failed: {failed}")
    print(f"Total:  {len(test_cases)}")

    if destructive_test_cases:
        print("\nNote: destructive tests are currently disabled by default.")

    return 0 if failed == 0 else 1


if __name__ == "__main__":
    sys.exit(main())
