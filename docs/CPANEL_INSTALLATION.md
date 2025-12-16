# cPanel Installation Guide

Follow these steps to deploy Onlinemarket.ng to a cPanel server and connect the database.

## 1. Upload Files

- Compress the project (exclude `.git`) and upload it via cPanel → File Manager to `public_html` or your subdomain.
- Extract the archive.

## 2. Create the Database

- Open cPanel → MySQL Database Wizard.
- Create a database, a user, and a strong password.
- Grant the user **ALL PRIVILEGES** to the database.

## 3. Configure Environment (`config/env.php`)

- Edit `config/env.php` directly on the server (the deployment excludes `config`).
- Set the following values:
  ```php
  return [
      'APP_ENV' => 'production',
      'DB_HOST' => 'localhost',
      'DB_NAME' => 'cpanel_db_name',
      'DB_USER' => 'cpanel_db_user',
      'DB_PASS' => 'cpanel_db_password',
      'DB_PORT' => 3306,
      'DISPLAY_ERRORS' => false,
  ];
  ```

## 4. Verify PHP Requirements

- PHP version: **8.0+** (8.1/8.2 recommended).
- Extensions: `pdo_mysql`, `mbstring`, `gd`.

## 5. Initialize the Schema

- Visit `https://your-domain/cpanel_install.php` to execute `sql/schema.sql` on the server.
- You should see `OK: N statements executed` upon success.

## 6. Deployment Automation (optional)

- `.cpanel.yml` is present and uses `rsync` to deploy while excluding `config/`.
- Ensure `DEPLOYPATH` is set correctly for your account.

## Troubleshooting 500 Errors

- Temporarily set `'DISPLAY_ERRORS' => true` in `config/env.php`, refresh, note the error, then revert to `false`.
- Check logs at `storage/logs/app.log` for database or runtime errors.
- Confirm the database name and user match exactly (cPanel prefixes are common).
- Ensure `pdo_mysql` is enabled in cPanel → Select PHP Version.
- Make sure `cpanel_install.php` ran successfully and tables exist.
- If the homepage redirects but fails, verify `home_page_-_onlinemarket.ng/index.php` includes `../core/init.php` correctly and that the `classes` directory is accessible.

## Quick Checklist

- `config/env.php` updated on server
- `pdo_mysql` enabled
- Schema installed via `cpanel_install.php`
- Correct `DEPLOYPATH` in `.cpanel.yml`
