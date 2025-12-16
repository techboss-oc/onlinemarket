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

- [ ] Upload files to server.
- [ ] Create MySQL database.
- [ ] Import `sql/schema.sql`.
- [ ] Update `config/db.php`.
- [ ] Verify PHP 8.1+ is running.
- [ ] Test Login/Register.

## Future Scalability

- **Image Handling:** Currently stores URLs. Can be upgraded to handle local file uploads to an `/uploads` directory or S3 bucket.
- **Caching:** Implement Redis for caching category lists and trending ads.
- **API:** The `classes/` can be easily exposed via an API endpoint for mobile apps.
