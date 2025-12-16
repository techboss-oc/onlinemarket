# cPanel Installation Guide

Follow these steps to deploy Onlinemarket.ng to a cPanel server.

## 1. File Upload

1. Compress the entire project folder (excluding `.git` if present) into a `.zip` file.
2. Log in to cPanel -> **File Manager**.
3. Navigate to `public_html` (or your subdomain folder).
4. Upload and Extract the `.zip` file.

## 2. Database Setup

1. Go to **MySQL Database Wizard** in cPanel.
2. Create a new database (e.g., `youruser_market`).
3. Create a new user and password.
4. Assign the user to the database with **ALL PRIVILEGES**.

## 3. Import Database

1. Go to **phpMyAdmin** in cPanel.
2. Select your new database.
3. Click **Import**.
4. Upload `sql/schema.sql` from the project folder.
5. (Optional) To seed test data, run the SQL queries found in `seed_ads.php` manually or upload a dump of your local DB.

## 4. Configuration

1. Edit `config/db.php` in File Manager.
2. Update the credentials:
   ```php
   private $host = 'localhost';
   private $db_name = 'youruser_market';
   private $username = 'youruser_dbuser';
   private $password = 'your_strong_password';
   ```
3. Edit `core/init.php` if necessary to update `SITE_URL` (optional, but good practice).

## 5. Permissions

- Ensure folders are `755` and files are `644`.
- If you have an `uploads` folder later, ensure it is writable.

## 6. PHP Version

- Ensure your server is running **PHP 8.0+** (Recommended 8.1 or 8.2).
- Extensions required: `pdo_mysql`, `gd`, `mbstring`.

## 7. Security

- Delete `install_db.php`, `seed_ads.php`, and `fix_db.php` after installation.
