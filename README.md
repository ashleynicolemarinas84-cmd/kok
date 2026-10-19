# Simple Library System (PHP + MySQL)

A beginner-friendly full-stack library system made with HTML5, Bootstrap 5, CSS, vanilla JavaScript, PHP, and MySQL/MariaDB.

## Features
- Login and logout with PHP sessions and hashed passwords.
- Admin and staff roles. Only admins can delete books or manage user accounts.
- Book CRUD: add, view, search, edit, and delete.
- Borrowing records and book returns. Available copies update automatically.
- Server-side form checks, prepared SQL statements, output escaping, and responsive layout.
- Dashboard totals for books, current loans, and borrowers.

## Requirements
- PHP 7.4+ recommended (PHP 8+ preferred)
- MySQL or MariaDB
- XAMPP for local development
- Internet connection for the Bootstrap CDN

## Run locally with XAMPP
1. Extract the `kok.zip` archive.
2. Copy the `kok` folder into `C:\xampp\htdocs\`.
3. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. Open `http://localhost/phpmyadmin`.
5. Choose **Import**, select `kok/database/schema.sql`, and click **Import**.
6. Open `http://localhost/kok/setup_admin.php` once to create the starter administrator.
7. **Delete `setup_admin.php` immediately after it creates the account.**
8. Open `http://localhost/kok/` and sign in.

### Starter administrator credentials
- Email: `admin@library.local`
- Password: `Library123!`

Change the password and remove any setup script before putting the website online. Do not use these demonstration credentials for a real library.

## Database
The SQL schema is in `database/schema.sql`. It creates:
- `users`: login accounts and roles
- `books`: book details and number of available copies
- `loans`: borrower, dates, and return status

## Deploy to free PHP/MySQL hosting
1. Create a free hosting account that supports PHP and MySQL/MariaDB (for example, InfinityFree or another available provider).
2. Create a database through the hosting control panel. Hosting providers may give a database name, username, password, and hostname different from the local XAMPP values.
3. Import `database/schema.sql` into the hosted database. If the host does not allow `CREATE DATABASE`, remove the first two SQL lines (`CREATE DATABASE...` and `USE library_system;`) and import the remaining tables into the database you created.
4. Update `db.php` with the host's exact database hostname, username, password, and database name.
5. Upload the contents of the `kok` folder to the host's web root (`htdocs`, `public_html`, or the folder specified by the host).
6. Run `setup_admin.php` once through the live URL, then delete it from the server immediately.
7. Test login, add/edit/search books, borrowing, returns, and role restrictions.
8. Use your required student-name subdomain/domain prefix. Replace the example with your own name and the platform's assigned domain; do not claim a URL until the host gives it to you.

## GitHub submission
1. Create a GitHub repository for this project.
2. Upload the source files and `database/schema.sql`. Do not upload real passwords or hosting credentials.
3. Include this README and setup instructions.
4. Submit your actual live website URL and repository URL in the course submission form.

## Important notes
- `admin@library.local` is a local demonstration login, not a real email address.
- Bootstrap loads from a CDN, so the layout may look simpler without internet access.
- This is a class project starter, not a complete production library system. For public deployment, use HTTPS, change the default password, remove setup scripts, and add stronger protections such as CSRF tokens, rate limiting, and backups.
