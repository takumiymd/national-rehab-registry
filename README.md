# National Rehab Registry

## Group Members

Quang, Takumi, Kunal, Armaan

---

## Project Description

The National Rehab Registry is a database-driven system designed to track the wellbeing of patients undergoing addiction rehabilitation.

The system focuses on monitoring patient progress and supporting data-driven decision-making in rehabilitation centers.

---

## Objectives

- Track patient recovery progress
- Monitor addiction type and treatment status
- Manage treatment plans and dosage

### Research and Analysis

This database is for the following:
- Health professionals
- Researchers
- Psychologists
- Academics

This database helps identify:

- effective treatment strategies
- environmental factors influencing recovery
- patterns in successful rehabilitation outcomes

---

## Tech Stack

- Frontend: HTML, CSS, JavaScript, Bootstrap
- Backend: PHP
- Database: MySQL (XAMPP)
- Query Language: SQL

---

## Data Access

The database is designed to be private and secure, accessible only to authorized healthcare professionals and researchers.

---

## Key Features

- Projection, Selection, Join, Division queries
- Aggregation (COUNT, MAX)
- Group By operations
- Update and Delete functionality with validation
- Input validation to prevent invalid queries

---

## Testing

Tested using phpmyadmin

The test was checked for:
- Query correctness
- Error handling

---

## Notes

- Division queries are implemented using GROUP BY and HAVING
- Some UI inputs (e.g., description fields) are informational only
- Input validation ensures system stability and prevents invalid SQL execution
- Database name must be "national_rehab_registry"
- Folder name must be "national_rehab_registry"

---

## How to Run

1. Unzip project folder and move it to C:\xampp\htdocs
2. Open XAMPP and start Apache and MySQL
3. Go to http://localhost/phpmyadmin
4. Create a database called "national_rehab_registry"
5. Import these files in order:
- sql/createTables
- sql/insertData.sql

6. type this into your browser
- http://localhost/national-rehab-registry/home.html

## SAMPLE INPUTS
- Listed in the field hints