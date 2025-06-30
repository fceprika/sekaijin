# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Sekaijin** (世界人 - "citoyen du monde" en japonais) is a Laravel-based community platform for French expatriates worldwide. The application provides a space for French expats to connect, share experiences, and access resources specific to their needs abroad.

## Core Architecture

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade templates with Tailwind CSS v3 and jQuery
- **Database**: MySQL with custom expat-focused user fields
- **Build Tools**: Vite for asset compilation
- **Authentication**: Custom Laravel authentication system

### Key Components

**Database Schema Extensions**:
The User model has been extended beyond standard Laravel auth to include expat-specific fields:
- Personal info: `first_name`, `last_name`, `birth_date`, `phone`
- Location data: `country_residence`, `city_residence`
- Community features: `bio`, `is_verified`, `last_login`

**Authentication Flow**:
- Custom AuthController handles registration/login with expat-specific validation
- French-language forms at `/inscription` and `/connexion` routes
- Enhanced registration requiring country of residence and personal details

**View Architecture**:
- Base template: `resources/views/layout.blade.php` with responsive navigation
- Main pages: home, about, services, contact (all French-localized)
- Auth views: `resources/views/auth/` directory with custom styling
- Consistent Tailwind CSS theming with blue/purple gradients

## Development Workflow

### Local Development Setup
```bash
# Start both servers in separate terminals:

# Terminal 1 - Laravel backend
php artisan serve
# Runs on http://127.0.0.1:8000

# Terminal 2 - Vite frontend assets  
npm run dev
# Runs on http://localhost:5173 (auto-assigns port if 5173 busy)
```

### Database Operations
```bash
# Run migrations (includes custom expat fields)
php artisan migrate

# Create new migration
php artisan make:migration migration_name

# Database name: sekaijin
# Connection: MySQL on localhost:3306, user: root, no password
```

### Asset Compilation
```bash
# Development (with hot reload)
npm run dev

# Production build
npm run build
```

### Testing
```bash
# Run PHPUnit tests
php artisan test
# or
./vendor/bin/phpunit

# Run specific test
php artisan test --filter TestClassName
```

### Code Quality
```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# Run with specific configuration
./vendor/bin/pint --config=pint.json
```

## Application-Specific Context

### French Localization
- All user-facing content is in French
- Route names use French terms (`/inscription`, `/connexion`, `/deconnexion`)
- Error messages and validation text localized for French expat audience
- Navigation: Accueil, À propos, Services, Contact

### Expat-Focused Features
- Country selection dropdown in registration (pre-populated with common expat destinations)
- User profiles designed around expat needs (residence country/city tracking)
- Community stats display (25K+ members, 150 countries, etc.)

### Authentication Security
- Password minimum 8 characters with confirmation
- Email uniqueness validation
- CSRF protection on all forms
- Terms acceptance required for registration
- Automatic login after successful registration

### Frontend Integration
- jQuery globally available via `window.$` and `window.jQuery`
- Tailwind CSS v3 with custom configuration
- Vite handles asset bundling and hot module replacement
- Custom CSS in `resources/css/app.css` loads Tailwind directives

## Database Configuration

The application uses a MySQL database named `sekaijin`. The User model includes these custom fields for the expat community:

```php
// Extended user fields for expat-specific data
'first_name', 'last_name', 'birth_date', 'phone',
'country_residence', 'city_residence', 'bio', 
'is_verified', 'last_login'
```

Migration file: `2025_06_29_160323_add_expat_fields_to_users_table.php`

## Key Files for Modifications

- **Routes**: `routes/web.php` - includes both page routes and custom auth routes
- **Auth Logic**: `app/Http/Controllers/AuthController.php` - custom registration/login
- **User Model**: `app/Models/User.php` - extended with expat fields and proper casting
- **Main Layout**: `resources/views/layout.blade.php` - responsive nav with auth state
- **Frontend Assets**: `resources/css/app.css`, `resources/js/app.js` 
- **Vite Config**: `vite.config.js` - configured for Laravel integration with HMR