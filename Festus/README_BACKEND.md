# RAIC PHP/MySQL Backend

This repository includes a frontend design and a new PHP/MySQL backend scaffold.

## Setup

1. Create the database:
   - Import `init_db.sql` into a local MySQL server.
   - Example:
     ```sql
     SOURCE init_db.sql;
     ```

2. Update `config.php` with your MySQL credentials.

3. Ensure PHP is installed and the site runs from a local PHP server or web server with PHP support.
   - Example:
     ```bash
     php -S localhost:8000
     ```

4. If uploads are used, verify that the server can write to `uploads/requests` and `uploads/datasets`.

## Available backend endpoints

- `register_submit.php` – handles account registration
- `login_submit.php` – handles user login
- `logout.php` – signs out the user
- `forgot_password_submit.php` – creates a password reset token
- `reset_password.php` – accepts password reset tokens
- `verify.php` – verifies new accounts
- `contact_submit.php` – stores contact form messages
- `submit_request.php` – stores information requests and attachment uploads
- `track_request.php` – displays request status by tracking number
- `upload_dataset.php` – stores publisher dataset metadata and uploads

## Notes

- This backend uses prepared statements and password hashing.
- Email delivery is simulated through flash messages.
- You can update the `BASE_URL` constant in `config.php` if the app is served from a subfolder.
