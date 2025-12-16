# cPanel Installation Guide

Follow these steps to deploy Onlinemarket.ng to a cPanel server and connect the database.

## 1. Upload Files

- Compress the project (exclude `.git`) and upload it via cPanel → File Manager to `public_html` or your subdomain.
- Extract the archive.

## 2. Create the Database

- Open cPanel → MySQL Database Wizard.
- Create a database, a user, and a strong password.
- Grant the user **ALL PRIVILEGES** to the database.

## 3. Configure Database Connection

This project uses `config/env.php` for configuration.

1. In cPanel File Manager, edit `config/env.php`.
   - **Note:** If this file was not deployed (because `config` folder is ignored), you must create it manually or copy `config/db.sample.php` to `config/env.php` on the server.
2. Update the array with your database credentials:
   ```php
   return [
       'APP_ENV' => 'production',
       'DB_HOST' => 'localhost',
       'DB_NAME' => 'your_cpanel_db_name', // e.g., hqwhttyp_market
       'DB_USER' => 'your_cpanel_db_user', // e.g., hqwhttyp_user
       'DB_PASS' => 'your_password',
       'DISPLAY_ERRORS' => false, // Set to true temporarily to debug 500 errors
   ];
   ```

**(Optional) Hardcoding in Class**: If you prefer, you can edit `classes/Database.php` directly on the server and uncomment the manual configuration block in the constructor.

## 4. Import Database (Manual)

1. Go to **phpMyAdmin** in cPanel.
2. Select your database.
3. Click **Import**.
4. Upload `sql/schema.sql` from your local project folder to create the table structure.
5. (Optional) To add test data (Seller, Buyer, Ads), verify the data locally and export/import it, or run SQL inserts manually.
6. Click **Go** to run the SQL.

## 5. Verify PHP Requirements

- PHP version: **8.0+** (8.1/8.2 recommended).
- Extensions: `pdo_mysql`, `mbstring`, `gd`.

## Troubleshooting 500 Errors

- **Database Connection**: If you see "Database Connection Failed", check your username/password in `env.php` or `db.php`.
- **Debug Mode**: Temporarily set `'DISPLAY_ERRORS' => true` in `config/env.php` (or edit `core/init.php` manually) to see the actual error message on screen.
- **Logs**: Check `storage/logs/app.log` in File Manager.
- **Prefixes**: Ensure you included the cPanel user prefix (e.g., `hqwhttyp_`) in your database name and user.

## Quick Checklist

- Database imported via phpMyAdmin
- Credentials updated in `config/env.php` (or `.env` / `db.php`)
- `pdo_mysql` enabled in PHP Selector
