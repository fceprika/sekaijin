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
- Role system: `role` field with 4 levels (free, premium, ambassador, admin)

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
'city_residence', 'bio', 'youtube_username', 'is_verified', 'last_login', 'role'
```

Migration files:
- `2025_06_29_160323_add_expat_fields_to_users_table.php` - Initial expat fields
- `2025_07_01_124618_make_user_fields_nullable.php` - Make personal fields optional
- `2025_07_01_154430_add_youtube_username_to_users_table.php` - YouTube integration field
- `2025_07_01_160225_add_unique_index_to_users_name_column.php` - Unique index on name for performance and data integrity
- `2025_07_02_073153_add_role_to_users_table.php` - User role system implementation

### Environment Configuration

Add these variables to your `.env` file:

```bash
# Mapbox Configuration
MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
```

The Mapbox token is configured in `config/services.php` and used in the interactive map on the homepage.

## Key Files for Modifications

- **Routes**: `routes/web.php` - includes page routes, auth routes, protected profile routes, public profile routes, and country-based content routes
- **Auth Logic**: `app/Http/Controllers/AuthController.php` - simplified registration/login with unique username validation
- **Profile Management**: `app/Http/Controllers/ProfileController.php` - user profile CRUD operations with YouTube validation
- **User Model**: `app/Models/User.php` - extended with expat fields, role system, and proper casting
- **Country Management**: `app/Http/Controllers/CountryController.php` - handles country-based content (actualités, blog, événements)
- **Content Models**: `app/Models/News.php`, `app/Models/Article.php`, `app/Models/Event.php` - full content management with relationships
- **Country Model**: `app/Models/Country.php` - country management with slug-based routing
- **Role Middleware**: `app/Http/Middleware/RoleMiddleware.php` - role-based access control
- **Country Middleware**: `app/Http/Middleware/EnsureValidCountry.php` - country context validation
- **Main Layout**: `resources/views/layout.blade.php` - responsive nav with auth state, profile link, and country context
- **Registration Form**: `resources/views/auth/register.blade.php` - simplified with complete country list
- **Profile Management**: `resources/views/profile/show.blade.php` - comprehensive profile editing with public profile link
- **Countries Component**: `resources/views/partials/countries.blade.php` - reusable country selection
- **Public Profiles**: `app/Http/Controllers/PublicProfileController.php` - public profile display with role badges
- **Public Profile View**: `resources/views/profile/public.blade.php` - public profile page with YouTube integration and role system
- **Country Views**: `resources/views/country/` - complete country section views (index, actualités, blog, événements, individual content pages)
- **Content Detail Views**: `resources/views/country/article-show.blade.php`, `news-show.blade.php`, `event-show.blade.php` - individual content pages
- **API Controllers**: `app/Http/Controllers/Api/ExpatController.php` - API endpoints for map data
- **Map Integration**: `public/js/country-coordinates.js` - country coordinates mapping
- **Content Seeder**: `database/seeders/ContentSeeder.php` - sample content generation for development
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

### User Role System (July 2025)
- **Four-tier Role System**: `free` (default), `premium`, `ambassador`, `admin` with distinct privileges
- **Role-based Access Control**: `RoleMiddleware` for protecting routes by role (`Route::middleware('role:admin')`)
- **Blade Directives**: Custom directives for role checking (`@admin`, `@premium`, `@ambassador`, `@role('admin')`)
- **User Model Methods**: Helper methods `isAdmin()`, `isPremium()`, `isAmbassador()`, `isFree()`, `isRole($role)`
- **Visual Role Badges**: Color-coded badges on public profiles with role-specific icons and styling
- **Role Display Names**: French localized role names ("Membre", "Membre Premium", "Ambassadeur Sekaijin", "Administrateur")

### UI/UX Improvements (July 2025)
- **Clickable Username Navigation**: User's pseudo in navigation menu now links to their public profile with hover effects
- **Hero Section Call-to-Actions**: Homepage hero buttons properly linked ("Rejoindre" → `/inscription`, "En savoir plus" → `/about`)
- **Favicon Integration**: Site favicon properly organized in `/public/images/` directory for better asset management
- **Clean Console Output**: Removed debug console messages for cleaner production experience

### Country-Based Site Architecture (July 2025)
- **Dynamic Country Routing**: Site structured around countries with prefixed routes (`/thailande/*`, `/japon/*`)
- **Country Model**: Database-driven country management with `name_fr`, `slug`, `emoji`, `description` fields
- **EnsureValidCountry Middleware**: Validates country slugs and shares country context with views automatically
- **Adaptive Navigation**: Country dropdown in header with contextual country-specific navigation menus
- **Country Badge Display**: Visual country indicator next to logo when browsing country-specific sections
- **Scalable Architecture**: Easy addition of new countries through database seeding without code changes

### Content Management System (July 2025)
- **News System**: Database-driven actualités with categories (`administrative`, `vie-pratique`, `culture`, `economie`)
- **Blog Articles**: Full article management with categories (`témoignage`, `guide-pratique`, `travail`, `lifestyle`, `cuisine`)
- **Event Management**: Comprehensive event system with registration, pricing, online/offline support
- **Content Relationships**: All content properly linked to countries and authors with role-based publishing permissions
- **Featured Content**: Highlighting system for featured news, articles, and events on country homepages
- **View Tracking**: Automatic view counting for articles and news with engagement metrics

### Individual Content Pages (July 2025)
- **Article Detail Pages**: Full article views with author info, reading time, likes, related articles
- **News Detail Pages**: Complete news pages with publication details, categories, and related news
- **Event Detail Pages**: Comprehensive event information with registration, pricing, participant tracking
- **Cross-linking**: Seamless navigation between content types with breadcrumb navigation
- **Social Features**: Like buttons, view counters, and sharing functionality across all content types
- **Responsive Design**: Mobile-optimized layouts for all content detail pages

### Database Schema (July 2025)
**Content Tables**:
```php
// News table for actualités
'title', 'excerpt', 'content', 'category', 'country_id', 'author_id', 
'is_featured', 'is_published', 'published_at', 'views'

// Articles table for blog
'title', 'slug', 'excerpt', 'content', 'category', 'country_id', 'author_id',
'is_featured', 'is_published', 'published_at', 'views', 'likes', 'reading_time'

// Events table for événements  
'title', 'slug', 'description', 'full_description', 'category', 'country_id', 'organizer_id',
'start_date', 'end_date', 'location', 'address', 'is_online', 'online_link',
'price', 'max_participants', 'current_participants', 'is_published', 'is_featured'
```

**Migration Files**:
- `2025_07_02_090549_create_news_table.php` - News/actualités management
- `2025_07_02_090641_create_articles_table.php` - Blog article system  
- `2025_07_02_090725_create_events_table.php` - Event management system
- `ContentSeeder.php` - Sample content for Thailand and Japan with realistic French expat data

### Performance Considerations
- Country selection uses reusable `@include('partials.countries')` to reduce code duplication
- Consider implementing JavaScript-based country selector for better UX at scale
- Country list could be cached or loaded via AJAX for improved performance
- Phone validation regex: `/^[\+]?[0-9\s\-\(\)]+$/` supports international formats
- Map data loaded asynchronously to avoid blocking page render
- Marker clustering could be implemented for better performance with large datasets
- **Database Optimization**: Unique index on `users.name` ensures fast public profile lookups and enforces data integrity at the database level
- **Content Pagination**: All content listings use Laravel pagination for optimal performance
- **Eager Loading**: Content relationships (author, country) loaded efficiently to prevent N+1 queries