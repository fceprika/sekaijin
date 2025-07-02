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
- Required fields: `name` (pseudo), `email`, `country_residence`
- Optional personal info: `first_name`, `last_name`, `birth_date`, `phone` (nullable)
- Location data: `country_residence` (required), `city_residence` (optional)
- Community features: `bio`, `is_verified`, `last_login`

**Authentication Flow**:
- Custom AuthController handles simplified registration/login
- French-language forms at `/inscription` and `/connexion` routes
- Simplified registration requiring only pseudo, email, country of residence, and password
- Complete country list (195 countries) organized by continent

**View Architecture**:
- Base template: `resources/views/layout.blade.php` with responsive navigation and profile link
- Main pages: home, about, services, contact (all French-localized)
- Auth views: `resources/views/auth/` directory with custom styling
- Profile management: `resources/views/profile/show.blade.php` for user profile editing
- Reusable components: `resources/views/partials/countries.blade.php` for country selection
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
- Complete country selection (195 countries) organized by continent in registration and profile
- Comprehensive user profile management system at `/profil` for authenticated users
- User profiles designed around expat needs (residence country/city tracking, bio, personal info)
- Secure profile editing with password change functionality
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

## Configuration

### Database Configuration

The application uses a MySQL database named `sekaijin`. The User model includes these custom fields for the expat community:

```php
// Required fields for registration and profile
'name', 'email', 'country_residence', 'password'

// Optional fields (nullable) for enhanced profile
'first_name', 'last_name', 'birth_date', 'phone',
'city_residence', 'bio', 'youtube_username', 'is_verified', 'last_login'
```

Migration files:
- `2025_06_29_160323_add_expat_fields_to_users_table.php` - Initial expat fields
- `2025_07_01_124618_make_user_fields_nullable.php` - Make personal fields optional
- `2025_07_01_154430_add_youtube_username_to_users_table.php` - YouTube integration field
- `2025_07_01_160225_add_unique_index_to_users_name_column.php` - Unique index on name for performance and data integrity

### Environment Configuration

Add these variables to your `.env` file:

```bash
# Mapbox Configuration
MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
```

The Mapbox token is configured in `config/services.php` and used in the interactive map on the homepage.

## Key Files for Modifications

- **Routes**: `routes/web.php` - includes page routes, auth routes, protected profile routes, and public profile routes
- **Auth Logic**: `app/Http/Controllers/AuthController.php` - simplified registration/login with unique username validation
- **Profile Management**: `app/Http/Controllers/ProfileController.php` - user profile CRUD operations with YouTube validation
- **User Model**: `app/Models/User.php` - extended with expat fields and proper casting
- **Main Layout**: `resources/views/layout.blade.php` - responsive nav with auth state and profile link
- **Registration Form**: `resources/views/auth/register.blade.php` - simplified with complete country list
- **Profile Management**: `resources/views/profile/show.blade.php` - comprehensive profile editing with public profile link
- **Countries Component**: `resources/views/partials/countries.blade.php` - reusable country selection
- **Public Profiles**: `app/Http/Controllers/PublicProfileController.php` - public profile display
- **Public Profile View**: `resources/views/profile/public.blade.php` - public profile page with YouTube integration
- **API Controllers**: `app/Http/Controllers/Api/ExpatController.php` - API endpoints for map data
- **Map Integration**: `public/js/country-coordinates.js` - country coordinates mapping
- **Frontend Assets**: `resources/css/app.css`, `resources/js/app.js` 
- **Vite Config**: `vite.config.js` - configured for Laravel integration with HMR

## Recent Updates (July 2025)

### Simplified Registration & Profile Management
- Simplified registration form to essential fields only (pseudo, email, country, password)
- Added comprehensive user profile management system
- Complete world country list (195 countries) organized by continent using reusable partial
- Secure password change functionality with enhanced validation (current_password rule)
- Made personal information fields optional for flexible user experience
- Added phone number regex validation for international formats
- Improved database migration safety with NULL value cleanup in rollbacks

### Interactive Map Integration
- Added Mapbox GL JS interactive map on homepage showing global expat distribution
- API endpoint `/api/expats-by-country` returns JSON data of users grouped by country
- Custom markers with size proportional to member count per country
- Responsive design with different map heights for mobile/tablet/desktop
- French localization for tooltips and map interface
- Country coordinates mapping for 195+ countries worldwide
- Real-time data loading via AJAX with error handling
- Mapbox API key configured via `.env` file (`MAPBOX_ACCESS_TOKEN`)

### Public Profile System with Social Integration
- Public profile pages accessible via `/membre/{pseudo}` URLs
- Unique username validation enforced during registration
- YouTube channel integration with `@username` format validation
- Clean responsive design without sensitive information exposure
- Member verification badge system for trusted users
- Cross-linking between private profile management and public profile
- Proper 404 handling for non-existent profiles
- French date formatting and localization throughout

### UI/UX Improvements (July 2025)
- **Clickable Username Navigation**: User's pseudo in navigation menu now links to their public profile with hover effects
- **Hero Section Call-to-Actions**: Homepage hero buttons properly linked ("Rejoindre" → `/inscription`, "En savoir plus" → `/about`)
- **Favicon Integration**: Site favicon properly organized in `/public/images/` directory for better asset management
- **Clean Console Output**: Removed debug console messages for cleaner production experience

### Performance Considerations
- Country selection uses reusable `@include('partials.countries')` to reduce code duplication
- Consider implementing JavaScript-based country selector for better UX at scale
- Country list could be cached or loaded via AJAX for improved performance
- Phone validation regex: `/^[\+]?[0-9\s\-\(\)]+$/` supports international formats
- Map data loaded asynchronously to avoid blocking page render
- Marker clustering could be implemented for better performance with large datasets
- **Database Optimization**: Unique index on `users.name` ensures fast public profile lookups and enforces data integrity at the database level