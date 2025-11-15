# Weather Dashboard - CodeIgniter 4

This is a weather dashboard system using CodeIgniter 4 with user authentication, OpenWeatherMap API integration, and city management features.

## What It Does

- Login/signup system with admin and user roles
- Shows weather data for multiple cities (temperature, humidity, wind, etc.)
- Users can save favorite cities (max 3)
- Admin can add/edit/delete cities and manage users
- Weather data is cached for 5 minutes to reduce API calls
- Form validation on all inputs

## Requirements

- PHP 8.1+
- XAMPP or similar (Apache + MySQL)
- Composer
- OpenWeatherMap API key (free)

## Setup Instructions

### 1. Extract Project

Put the project folder in your htdocs:
```
C:\xampp\htdocs\ci4_api
```

### 2. Install Dependencies

Run this in the project folder:
```bash
composer install
```


### 3. Setup Database

Create database `ci4_crud` and import the `ci4_crud.sql` file.

### 4. Run the App

Start XAMPP (Apache + MySQL) and go to:
```
http://localhost/ci4_api/
```

## Default Login

Admin account:
- Username: `Gwen`
- Password: `12345678`

You can create regular user accounts through the signup page.

## Project Structure

Key files created/modified:

**Controllers:**
- `app/Controllers/Home.php` - Dashboard and weather display
- `app/Controllers/LoginController.php` - Login/signup
- `app/Controllers/AdminController.php` - Admin CRUD

**Models:**
- `app/Models/UserModel.php`
- `app/Models/CityModel.php`

**Views:**
- `app/Views/login.php`, `signup.php`, `dashboard.php`
- `app/Views/admin/` - All admin CRUD views

**Custom Code:**
- `app/Libraries/WeatherService.php` - API integration
- `app/Filters/AuthFilter.php` - Session authentication

**Config (modified):**
- `app/Config/Routes.php` - Added custom routes
- `app/Config/Database.php` - Database setup
- `app/Config/Filters.php` - Auth filter registration
- `app/Config/Autoload.php` - Added custom namespaces

## How to Use

1. Login with admin account (Gwen / 12345678)
2. Dashboard shows weather for all cities
3. Click "Add to Favorites" to save cities (max 2)
4. Admin can manage cities and users from the menu

## Notes

- Weather data updates every 5 minutes (cached)
- Free API allows 1,000 calls/day
- Passwords are hashed with bcrypt
- Session expires after inactivity