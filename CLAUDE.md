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
- Base template: `resources/views/layout.blade.php` with responsive navigation, direct country links, and profile integration
- Homepage: `resources/views/home.blade.php` with dynamic 2x2 grid layout displaying real content from Thailand and Japan
- HomeController: `app/Http/Controllers/HomeController.php` provides real database data instead of dummy content
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
- Navigation: Direct country links (Thailand, Japan), À propos, Contact, with dynamic country-specific menus when browsing country sections

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
'city_residence', 'destination_country', 'bio', 'youtube_username', 'is_verified', 'last_login', 'role'
```

Migration files:
- `2025_06_29_160323_add_expat_fields_to_users_table.php` - Initial expat fields
- `2025_07_01_124618_make_user_fields_nullable.php` - Make personal fields optional
- `2025_07_01_154430_add_youtube_username_to_users_table.php` - YouTube integration field
- `2025_07_01_160225_add_unique_index_to_users_name_column.php` - Unique index on name for performance and data integrity
- `2025_07_02_073153_add_role_to_users_table.php` - User role system implementation
- `2025_07_03_165927_add_destination_country_to_users_table.php` - Destination country field for French residents

### Environment Configuration

Add these variables to your `.env` file:

```bash
# Mapbox Configuration
MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
```

The Mapbox token is configured in `config/services.php` and used in the interactive map on the homepage.

## Key Files for Modifications

- **Routes**: `routes/web.php` - includes page routes, auth routes, protected profile routes, public profile routes, and country-based content routes
- **Homepage Controller**: `app/Http/Controllers/HomeController.php` - provides real database content for homepage 2x2 grid layout (Thailand/Japan sections)
- **Auth Logic**: `app/Http/Controllers/AuthController.php` - simplified registration/login with unique username validation
- **Profile Management**: `app/Http/Controllers/ProfileController.php` - user profile CRUD operations with YouTube validation
- **User Model**: `app/Models/User.php` - extended with expat fields, role system, and proper casting
- **Country Management**: `app/Http/Controllers/CountryController.php` - handles country-based content (actualités, blog, événements)
- **Content Models**: `app/Models/News.php`, `app/Models/Article.php`, `app/Models/Event.php` - full content management with relationships
- **Country Model**: `app/Models/Country.php` - country management with slug-based routing
- **Role Middleware**: `app/Http/Middleware/RoleMiddleware.php` - role-based access control
- **Country Middleware**: `app/Http/Middleware/EnsureValidCountry.php` - country context validation
- **Form Requests**: `app/Http/Requests/Store*Request.php` - comprehensive validation for content creation
- **Authorization Policies**: `app/Policies/*Policy.php` - granular permission control for all content types
- **View Composers**: `app/Http/View/Composers/CountryComposer.php` - performance optimization with caching
- **Main Layout**: `resources/views/layout.blade.php` - responsive nav with direct country links, auth state, profile integration, and dynamic country context badges
- **Homepage View**: `resources/views/home.blade.php` - 2x2 grid layout with hero section, interactive map, and real content from Thailand/Japan
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
- **Destination Country for French Residents**: Conditional field that appears when France is selected as residence country
- **Smart Country Exclusion**: France automatically excluded from destination options to prevent confusion
- **Conditional Validation**: `required_if:country_residence,France` ensures proper data collection for French users
- Secure password change functionality with enhanced validation (current_password rule)
- Made personal information fields optional for flexible user experience
- Added phone number regex validation for international formats
- Improved database migration safety with NULL value cleanup in rollbacks

### Interactive Map Integration
- Added Mapbox GL JS interactive map on homepage showing global expat distribution
- **Strategic Placement**: Map positioned between hero section and content grids for better user flow
- API endpoint `/api/expats-by-country` returns JSON data of users grouped by country
- Custom markers with size proportional to member count per country
- **Optimized Marker Behavior**: CSS-based hover effects prevent positioning bugs, stable transforms with `will-change: transform`
- Responsive design with different map heights for mobile/tablet/desktop (250px/400px/500px)
- French localization for tooltips and map interface
- Country coordinates mapping for 195+ countries worldwide
- Real-time data loading via AJAX with error handling and fallback states
- Enhanced visual design with rounded container, shadow effects, and legend
- Mapbox API key configured via `.env` file (`MAPBOX_ACCESS_TOKEN`)
- **Performance Optimizations**: Replaced JavaScript hover events with pure CSS for smooth animations

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
- **Enhanced Homepage Interactivity**: All content blocks now clickable with proper hover states and visual feedback
- **Visual Hierarchy**: Improved typography with larger fonts, better spacing, and clearer content organization
- **Category Color Coding**: Dynamic badge colors for content categories (travel=blue, lifestyle=yellow, culture=purple, gastronomy=orange, etc.)
- **Favicon Integration**: Site favicon properly organized in `/public/images/` directory for better asset management
- **Clean Console Output**: Removed debug console messages for cleaner production experience

### Recent Bug Fixes & Technical Improvements (July 2025)
- **Route Generation Fix**: Resolved routing errors by using `.id` instead of `.slug` for content detail pages
- **Database Query Optimization**: HomeController implements proper eager loading with `with('author')` and `with('organizer')`
- **Null Safety**: Added comprehensive null checks and safe fallbacks for missing content or countries
- **Content Filtering**: Proper country-specific content filtering at database level for accurate homepage display
- **Event Date Handling**: Upcoming events properly filtered using `where('start_date', '>=', now())` for relevance
- **Collection Safety**: Uses Laravel collections with `collect()` fallbacks when no database records exist

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
- `2025_07_02_094335_add_country_id_to_users_table.php` - Proper foreign key relationships
- `ContentSeeder.php` - Sample content for Thailand and Japan with realistic French expat data

### Security & Validation (July 2025)
- **Form Request Classes**: Comprehensive validation for all content types (`StoreNewsRequest`, `StoreArticleRequest`, `StoreEventRequest`)
- **Authorization Policies**: Role-based access control with detailed policies (`NewsPolicy`, `ArticlePolicy`, `EventPolicy`)
- **Role-Based Permissions**: Granular permissions (admins/ambassadors for news, authors for their content)
- **Input Validation**: Strict validation rules with French error messages for all user inputs
- **Error Handling**: Comprehensive try-catch blocks with structured logging and user-friendly error messages
- **Database Relationships**: Proper foreign key constraints with `country_id` replacing fragile string-based relationships

### Homepage Redesign & Real Data Integration (July 2025)
- **2x2 Grid Layout**: Homepage restructured with hero section followed by interactive map, then 2x2 content grids for Thailand and Japan
- **HomeController Implementation**: `app/Http/Controllers/HomeController.php` replaces dummy content with real database queries
- **Real Content Display**: Dynamic loading of latest news (2 items), blog articles (2 items), and upcoming events (1 item) per country
- **Interactive Content**: All content blocks are clickable and properly routed to individual content pages
- **Enhanced UX**: Added hover effects, larger fonts, color-coded categories, and proper visual hierarchy
- **Navigation Streamlining**: Removed "Accueil" and "Services" buttons, replaced with direct country links for better site navigation
- **Route Optimization**: Fixed routing errors by using `.id` instead of `.slug` for content page generation
- **Content Categorization**: Dynamic category badges with proper color coding (travel=blue, lifestyle=yellow, culture=purple, etc.)
- **Empty State Handling**: Graceful fallbacks when no content exists for a country section

### Performance Optimizations (July 2025)
- **View Composers**: `CountryComposer` with 1-hour caching eliminates N+1 queries in navigation
- **Eager Loading**: Optimized queries with `select()` clauses to fetch only necessary columns, HomeController uses `with('author')` for efficient relationships
- **Query Optimization**: Proper use of Query Builder methods vs Collection methods, country-specific content filtering at database level
- **Content Pagination**: Laravel pagination for all content listings with efficient database queries
- **Cached Data**: Country lists cached globally to avoid repeated database calls
- **Real-time Data**: Homepage content loaded dynamically from database with proper null checks and safe fallbacks
- **Database Optimization**: Unique index on `users.name` ensures fast public profile lookups and enforces data integrity at the database level

### Mobile UX & Profile Enhancements (July 2025)
- **Mobile Navigation Menu**: Implemented BDfugue-style slide-out sidebar menu for mobile devices
- **Hamburger Menu System**: Right-side sliding navigation (320px width) with smooth animations and overlay backdrop
- **Mobile Profile Integration**: User avatar and location display in mobile menu for authenticated users
- **Organized Mobile Navigation**: Clear section headers for countries and content types with emojis and active state indicators
- **Mobile UX Features**: Body scroll lock, auto-close on link clicks, proper visual hierarchy for mobile screens
- **Bio Formatting Improvements**: Enhanced profile bio display with `whitespace-pre-line` CSS for proper line break preservation
- **Profile Textarea Enhancement**: Increased bio textarea height (6 rows) with example placeholder showing proper formatting
- **Cross-Platform Bio Consistency**: Unified bio formatting between private profile editing and public profile display