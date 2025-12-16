# Onlinemarket.ng Project Documentation

## Project Overview

Onlinemarket.ng is a robust, Jiji-like classified ads platform built with Vanilla PHP and a custom MVC-style architecture. It features role-based authentication, ad management, and a responsive UI powered by Tailwind CSS.

## Folder Structure

The project follows a folder-per-page structure mapped directly to routes, with a shared core:

- `/core/` - Initialization, autoloader, session start.
- `/config/` - Database configuration.
- `/classes/` - Models (User, Ad, Category, etc.).
- `/includes/` - Helper functions.
- `/sql/` - Database schema.
- `/docs/` - Documentation.
- `/[page_folder]/` - Contains `index.php` for that specific page/route.

## Database Schema

The database `onlinemarket_ng` is normalized and includes:

- `users`: Stores buyer, seller, admin info.
- `categories`: Hierarchical categories with icons.
- `locations`: States and cities.
- `ads`: Product listings linked to users, categories, and locations.
- `ad_images`: Multiple images per ad.
- `chats` & `messages`: Real-time messaging system.

## Key Features

- **Authentication:** Secure Login/Register with password hashing (Bcrypt).
- **Roles:** Buyer, Seller, Admin.
- **Dynamic Home Page:** Fetches Categories, Trending Ads, and Latest Ads from DB.
- **Search:** Functional search by keyword, category, and location.
- **Security:** PDO Prepared Statements (SQL Injection protection), CSRF protection (basic session checks), Input Sanitization.

## Deployment Checklist

- [ ] Upload files to server (exclude `config` folder if using git/rsync).
- [ ] Create MySQL database in cPanel.
- [ ] Import `sql/schema.sql` via phpMyAdmin.
- [ ] Create `config/env.php` on server with DB credentials.
- [ ] Verify PHP 8.1+ is running with `pdo_mysql`, `mbstring`, `gd`.
- [ ] Test Login/Register.

## Configuration

The project uses a manual environment configuration file:

- **Local:** `config/env.php` (created from `db.sample.php`).
- **Production:** `config/env.php` (must be created manually or via cPanel File Manager).
- **Fallback:** `classes/Database.php` has a manual configuration block that can be uncommented if file-based config fails.

## Database Schema

The database `onlinemarket_ng` is normalized and includes:

- `users`: Stores buyer, seller, admin info.
- `categories`: Hierarchical categories with icons.
- `locations`: States and cities.
- `ads`: Product listings linked to users, categories, and locations.
- `ad_images`: Multiple images per ad (`TEXT` type for long URLs).
- `chats` & `messages`: Real-time messaging system.

## Future Scalability

- **Image Handling:** Currently stores URLs. Can be upgraded to handle local file uploads to an `/uploads` directory or S3 bucket.
- **Caching:** Implement Redis for caching category lists and trending ads.
- **API:** The `classes/` can be easily exposed via an API endpoint for mobile apps.
