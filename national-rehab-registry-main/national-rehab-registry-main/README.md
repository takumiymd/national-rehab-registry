# National Rehab Registry

## Group Members
Quang, Takumi, Kunal, Armaan

---

## Project Description

The National Rehab Registry is a database-driven system designed to track the wellbeing of patients undergoing addiction rehabilitation.

The system focuses on monitoring patient progress and supporting data-driven decision-making in rehabilitation centers.

---

## Objectives

This system is designed to support several key applications:

### Patient Management
- Track individual patient recovery progress  
- Monitor addiction type and treatment status  
- Manage medication dosage and treatment plans  

### Research and Analysis
- Provide structured data for:
  - Health professionals  
  - Researchers  
  - Psychologists  
  - Academics  

This data helps identify:
- effective treatment strategies  
- environmental factors influencing recovery  
- patterns in successful rehabilitation outcomes  

---

## Tech Stack

- Frontend: HTML, CSS, JavaScript  
- Backend: PHP  
- Database: MySQL (MariaDB via XAMPP)  
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

Automated backend tests were implemented using Python.

- 13 test cases executed  
- All tests passed successfully  

The test suite verifies:
- query correctness  
- database interaction  
- error handling  

---

## Notes

- Division queries are implemented using GROUP BY and HAVING  
- Some UI inputs (e.g., description fields) are informational only  
- Input validation ensures system stability and prevents invalid SQL execution  

---

## How to Run

1. Start XAMPP (Apache and MySQL)
2. Import the database:

```bash
/opt/lampp/bin/mysql -u root -e "DROP DATABASE IF EXISTS national_rehab_registry; CREATE DATABASE national_rehab_registry;"
/opt/lampp/bin/mysql -u root national_rehab_registry < sql/createTables.sql
/opt/lampp/bin/mysql -u root national_rehab_registry < sql/insertData.sql
```
3. Move the project into XAMPP:
```bash
cp -r national-rehab-registry /opt/lampp/htdocs/
```
4. Open in browser:
```bash
http://localhost/national-rehab-registry
```
