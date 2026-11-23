# Student Internship Tracker

A PHP + MySQL student internship tracking web application.

## Features
- Interactive, responsive homepage with an application-stage preview
- Student registration and login
- Password hashing and session-based authentication
- Dashboard with application statistics
- Add, view, edit and delete internship records
- Track interview dates and notes, offer dates and response deadlines, compensation, and internship start dates
- Filter applications by interview schedule and offers
- Filter applications by status
- Responsive CSS layout
- Prepared SQL statements and CSRF tokens on POST forms

## Requirements
- XAMPP (Apache + PHP + MySQL)
- PHP 7.4+ recommended (PHP 8+ preferred)
- MySQL / MariaDB
- A modern browser

## Setup using XAMPP
1. Install and open XAMPP.
2. Start **Apache** and **MySQL**.
3. Copy the `student_internship_tracker` folder into `C:\xampp\htdocs\`.
4. Open `http://localhost/phpmyadmin`.
5. Choose **Import**, select `database.sql` from this project, and click **Import/Go**.
   - Alternatively, open the SQL tab and paste the contents of `database.sql`.
6. Check `config.php`. The default XAMPP MySQL username is `root` and password is blank. Change these if your setup differs.
7. Open `http://localhost/student_internship_tracker/`.
8. Register a student account and start adding internships.

For an existing database created from an older version of `database.sql`, import `upgrade_application_tracking.sql` once to add interview and offer tracking fields.

## Main files
- `register.php`, `login.php`: account access
- `dashboard.php`: student dashboard and summary
- `add_internship.php`: create a record
- `internships.php`: list, filter and delete records
- `edit_internship.php`: update a record
- `database.sql`: database and tables
- `assets/style.css`: design
- `assets/app.js`: browser-side confirmation interaction

## Notes
- This is a student project starter, not a production deployment. Before public deployment, add HTTPS, rate limiting, stronger account recovery, server-side validation tailored to your institution, and production database credentials.
- The pages use XHTML-style document markup and PHP-generated dynamic content. PHP is required to run the site; opening the files directly from the file manager will not work.
