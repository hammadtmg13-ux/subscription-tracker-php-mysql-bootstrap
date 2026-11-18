# SubTrack - Simple Subscription Tracker

A simple subscription tracker built with HTML, CSS, JavaScript, Bootstrap, MySQL and PHP. Keep every subscription in one place, see what they cost each month, and never miss a renewal date.

## Features

- Add subscriptions with name, category, cost, billing cycle and renewal date
- See monthly and yearly totals at a glance
- Find the next renewal and how many days are left
- Search subscriptions by name
- Filter by category and billing cycle
- Pause and resume subscriptions
- Edit and delete subscriptions
- Clean responsive layout with Bootstrap 5

## Tech Stack

- HTML, CSS, JavaScript
- Bootstrap 5
- PHP with PDO for safe database queries
- MySQL

## Setup

1. Import `database.sql` into MySQL to create the `subtrack_db` database and the `subscriptions` table with sample subscriptions.
2. Update the database credentials in `config.php` if needed.
3. Place the project folder in your web server directory (for example `htdocs` in XAMPP).
4. Open `index.php` in your browser.

## Files

- `index.php` - Dashboard with subscriptions grid, search, filters and stats
- `add_subscription.php` - Add a new subscription
- `edit_subscription.php` - Edit an existing subscription
- `delete_subscription.php` - Delete a subscription
- `toggle_active.php` - Pause or resume a subscription
- `config.php` - Database connection
- `database.sql` - Database and table setup with sample subscriptions
- `assets/css/style.css` - Custom styles
- `assets/js/app.js` - Search, filter and form helpers
