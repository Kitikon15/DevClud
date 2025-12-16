# DevClub Member System

A web-based member management system for DevClub built with PHP, MySQL, and Bootstrap.

## Features
- User authentication (login/logout)
- Member management (add, edit, delete)
- Responsive design with Bootstrap
- Database integration with MySQL

## Requirements
- PHP 7.0 or higher
- MySQL database
- Web server (Apache/Nginx)

## Installation
1. Clone the repository
2. Set up a MySQL database
3. Update database credentials in `db.php`
4. Run the application on a web server

## Usage
1. Create an admin user using `create_admin.php`
2. Log in with your credentials
3. Manage club members through the dashboard

## Files
- `index.php` - Main dashboard showing all members
- `form.php` - Form for adding/editing members
- `save.php` - Handles saving member data
- `delete.php` - Handles deleting members
- `login.php` - User login page
- `logout.php` - Logout functionality
- `auth.php` - Authentication functions
- `db.php` - Database configuration
- `init_db.php` - Database initialization script
- `create_admin.php` - Admin user creation

## License
MIT License