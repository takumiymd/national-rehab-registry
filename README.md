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

This database helps identify:
- effective treatment strategies  
- environmental factors influencing recovery  
- patterns in successful rehabilitation outcomes  

---

## Tech Stack

- Frontend: HTML, CSS, JavaScript  
- Backend: PHP  
- Database: MySQL (via XAMPP)  
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

This was tested on phpmyadmin

- All tests passed successfully  

The test verifies:
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
2. type http://localhost/phpmyadmin
3. create a new database called "national_rehab_registry
4. import tables in createTables.sql to sql and press GO
5. import tables in insertData.sql to sql and press GO
6. type http://localhost/national-rehab-registry/home.html
