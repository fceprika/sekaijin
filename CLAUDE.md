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
- Community features: `bio`, `is_verified`, `last_login`, `is_public_profile`
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

### Git Workflow Rules
**IMPORTANT: Claude Code must follow these Git rules at all times:**

- **NEVER push directly to main branch** - Always create feature branches
- **Branch naming convention**: `feature/description-of-feature` or `fix/bug-description`
- **Always create Pull Requests** for code review before merging to main
- **Only push to main** after explicit user approval and instruction
- **Commit messages**: Use conventional commits format with appropriate emojis
- **Work in isolation**: Each feature/fix should be in its own branch

**Workflow steps:**
1. Create feature branch: `git checkout -b feature/feature-name`
2. Make changes and commit with descriptive messages
3. Push to feature branch: `git push origin feature/feature-name`
4. Create PR for review (use `gh pr create` command)
5. Wait for user approval before merging to main

### Local Development Setup
**IMPORTANT: DO NOT start the Laravel server - it is already running**

The development environment is already configured and running:
- Laravel backend is running on http://127.0.0.1:8000 
- Vite frontend assets can be started with `npm run dev` if needed

**Claude Code should NEVER run `php artisan serve` or attempt to start the Laravel server**

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

# Run End-to-End Tests with Laravel Dusk
php artisan dusk

# Run specific Dusk test
php artisan dusk --filter=test_name

# Run Dusk tests for specific file
php artisan dusk tests/Browser/LoginTest.php
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
'city_residence', 'interest_country', 'bio', 'youtube_username', 'is_verified', 'last_login', 'role'
```

Migration files:
- `2025_06_29_160323_add_expat_fields_to_users_table.php` - Initial expat fields
- `2025_07_01_124618_make_user_fields_nullable.php` - Make personal fields optional
- `2025_07_01_154430_add_youtube_username_to_users_table.php` - YouTube integration field
- `2025_07_01_160225_add_unique_index_to_users_name_column.php` - Unique index on name for performance and data integrity
- `2025_07_02_073153_add_role_to_users_table.php` - User role system implementation
- `2025_07_03_165927_add_destination_country_to_users_table.php` - Destination country field for French residents (now renamed to interest_country)
- `2025_07_18_053222_rename_destination_country_to_interest_country_in_users_table.php` - Rename destination_country to interest_country
- `2025_07_18_054435_add_china_to_countries_table.php` - Add China and ensure Vietnam exists for production

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

### Complete Announcements/Classified Ads System (July 11-12, 2025)
- **NEW: Announcement Model & System**: Full classified ads platform with CRUD operations
  - Four announcement types: `vente` (sales), `location` (rentals), `colocation` (shared housing), `service` (services)
  - Multi-image upload support with secure storage in `/storage/announcements/`
  - Price and currency management with international support (EUR, USD, THB, JPY, GBP, CHF)
  - Expiration date system for time-limited listings
  - Advanced search and filtering by type, location, price, and text search
- **Admin Moderation System**: 3-state approval workflow
  - `pending` → `active` → `refused` with detailed refusal reasons
  - Bulk operations for efficient moderation (approve/refuse/delete multiple)
  - Admin dashboard with filtering by status, country, and search terms
- **User Dashboard**: Personal announcements management at `/mes-annonces`
  - View, edit, delete own announcements
  - Real-time status tracking (pending approval, active, expired)
  - Statistics display (views, creation date, status)
- **Responsive Multi-Step Form**: 5-step announcement creation process
  - Step 1: Type selection with visual icons
  - Step 2: Location (country/city/address)
  - Step 3: Details (title, description, price, expiration)
  - Step 4: Image uploads with preview
  - Step 5: Final review before submission
  - Mobile-optimized with progress indicators and form validation
- **Location Integration**: 
  - Global announcements at `/annonces` with country filtering
  - Country-specific announcements at `/{country}/annonces` 
  - City-based filtering and search within countries
  - Pre-filled country selection when accessing from country pages

### Critical Bug Fixes & Technical Improvements (July 12, 2025)
- **Content Corruption Fix**: Resolved critical issue where user-created articles contained encoded POST data
  - Root cause: `TrimStrings` middleware interfering with content fields
  - Solution: Excluded `content` and `description` fields from automatic trimming
  - Impact: User article creation now works correctly without data corruption
- **Article URL Encoding Bug**: Fixed malformed URLs in my-articles dashboard
  - Issue: Dynamic route name construction creating non-existent routes like `thailande.article`
  - Fix: Changed to proper route parameters `route('country.article.show', [$country, $slug])`
  - Result: "Voir" buttons now generate correct article URLs
- **TinyMCE Synchronization**: Enhanced admin form reliability
  - Added `saveTinyMCEContent()` function to force content sync before submission
  - Applied to all admin forms using TinyMCE (articles/news create/edit)
  - Prevents content loss during form submission in admin interface
- **Responsive Design Improvements**: Fixed announcements module mobile layout
  - Progress indicator spacing unified with `justify-center space-x-8`
  - Mobile navigation enhancements for announcement creation
  - Improved form validation feedback on smaller screens
- **Session Management**: Added database-driven session storage
  - New migration: `2025_07_12_043816_create_sessions_table.php`
  - Enhanced security and scalability for user sessions
  - Proper session cleanup and management

### End-to-End Testing Implementation (July 19, 2025)
- **Laravel Dusk Configuration**: Complete E2E testing setup
  - Configured separate test database (`sekaijin_dusk`) for browser tests
  - Environment configuration in `.env.dusk.local` with isolated settings
  - ChromeDriver setup for headless browser automation
  - Session management configuration for CSRF token handling
- **Authentication Test Suite**: Comprehensive login/registration testing
  - `LoginTest.php`: User login, failed login validation, user menu presence, forgot password link
  - `RegistrationTest.php`: User registration, email validation, password validation, terms acceptance
  - Proper error message validation with French localization
  - Form field targeting using explicit CSS selectors
- **Test Infrastructure**: Professional E2E testing architecture
  - `SimpleTest.php`: Basic homepage functionality verification
  - Database truncation for test isolation using `DatabaseTruncation` trait
  - Country model setup in test fixtures for proper foreign key relationships
  - Browser screenshot capture for debugging failed tests
- **Testing Commands**: Complete test suite execution
  ```bash
  php artisan dusk                    # Run all E2E tests
  php artisan dusk --filter=test_name # Run specific test
  php artisan dusk tests/Browser/LoginTest.php # Run specific test file
  ```
- **Test Coverage**: Core application functionality validated
  - ✅ User authentication (login/logout/registration)
  - ✅ Form validation and error handling
  - ✅ Homepage functionality and navigation
  - ✅ User interface responsiveness and element interaction
  - ✅ Integrated into GitHub Actions CI/CD pipeline

### Enhanced User Experience & Content Management

### Simplified Registration & Profile Management
- Simplified registration form to essential fields only (pseudo, email, country, password)
- Added comprehensive user profile management system
- Complete world country list (195 countries) organized by continent using reusable partial
- **Interest Country Field**: Optional field available for all users to select a country of interest
- **Dynamic Country Selection**: Both registration and profile pages use a select dropdown showing only available countries from the site
- **Available Countries**: Currently supports Thaïlande 🇹🇭, Japon 🇯🇵, Vietnam 🇻🇳, and Chine 🇨🇳
- **Non-restrictive**: Interest country can be same as residence country and is completely optional
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
- **Profile Visibility Control**: Users can choose between public (accessible to all) or private (members only) profiles
- Unique username validation enforced during registration
- YouTube channel integration with `@username` format validation
- Clean responsive design without sensitive information exposure
- Member verification badge system for trusted users
- Cross-linking between private profile management and public profile
- Proper 404 handling for non-existent profiles
- French date formatting and localization throughout
- **Privacy Protection**: Private profiles are only accessible to authenticated users

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
- **Authentication-based UI**: Registration blocks hidden for logged-in users with `@guest` directive
- **Admin URL Fixes**: "Voir en ligne" links use slugs instead of IDs for proper routing

### Recent Bug Fixes & Technical Improvements (July 2025)
- **Route Generation Fix**: Resolved routing errors by using `.id` instead of `.slug` for content detail pages
- **Database Query Optimization**: HomeController implements proper eager loading with `with('author')` and `with('organizer')`
- **Null Safety**: Added comprehensive null checks and safe fallbacks for missing content or countries
- **Content Filtering**: Proper country-specific content filtering at database level for accurate homepage display
- **Event Date Handling**: Upcoming events properly filtered using `where('start_date', '>=', now())` for relevance
- **Collection Safety**: Uses Laravel collections with `collect()` fallbacks when no database records exist
- **SEO Service Robustness**: Added null-safe operators (`?->`) and nullable parameter support throughout `SeoService`
- **Content Publication Filtering**: Homepage only shows published content with `->where('is_published', true)` in `ContentCacheService`
- **Admin Preview System**: Complete preview functionality with production-identical templates and fetch API integration

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

### Complete Event Management System (July 2025)
- **Role-Based Event Creation**: Only ambassadors and admins can create events via `EventPolicy` with `isAdmin()` or `isAmbassador()` checks
- **EventController**: Full resource controller with CRUD operations (`create`, `store`, `edit`, `update`, `destroy`) and proper authorization
- **Smart Slug Generation**: Automatic URL-friendly slug creation from titles with uniqueness validation and collision handling
- **Event Types Support**: Online and offline events with conditional validation (location required for offline, online_link for online)
- **StoreEventRequest**: Comprehensive form validation with French error messages and conditional field requirements
- **Event Authorization**: Only event organizer and admins can edit/delete events via policy-based authorization
- **Public Profile Integration**: User's created events (upcoming and past) displayed on public profile with organized sections
- **Event Detail Pages**: Complete event view with organizer info, participant tracking, pricing, and edit button for authorized users
- **Checkbox Handling**: Proper form submission for `is_online`, `is_published`, `is_featured` boolean fields with fallback defaults
- **Route Model Binding**: Events use slug-based URLs for SEO-friendly routing with automatic model resolution

### Avatar Display System (July 2025)
- **Universal Avatar Support**: Consistent avatar display across all content types (articles, news, events, profiles)
- **Blue Border Styling**: 2px blue border (`border-blue-500`) on all user avatars for visual consistency
- **Smart Fallbacks**: Gradient circles with user initials when no avatar exists, color-coded by content type
- **Database Optimization**: Proper eager loading of author/organizer avatars in all controllers with `select()` optimization
- **Size Variants**: Different avatar sizes for different contexts (5x5 homepage, 6x6 grids, 10x10 featured, 12x12 individual pages)
- **Performance**: Avatar fields included in all content queries to prevent N+1 problems

### Google Analytics Integration (July 2025)
- **Environment Configuration**: `GOOGLE_ANALYTICS_ID` environment variable in `.env` and `config/services.php`
- **Production-Only Tracking**: Analytics script loads only when `app()->environment('production')` to prevent development tracking
- **Layout Integration**: Google Analytics gtag.js script in `resources/views/layout.blade.php` head section
- **Async Loading**: Non-blocking script loading following Google best practices for optimal performance
- **Privacy Compliance**: No tracking in local, development, or testing environments
- **Easy Configuration**: Simple environment variable management for different deployment environments

### Event Management Architecture (July 2025)
**Controllers & Logic**:
- `EventController`: Complete resource controller with authorization middleware
- `PublicProfileController`: Enhanced with user's event listings (upcoming and past events)
- `CountryController`: Updated `showEvent()` method with organizer avatar loading

**Templates & Views**:
- `resources/views/events/create.blade.php`: Comprehensive event creation form with conditional fields
- `resources/views/events/edit.blade.php`: Event editing form with pre-filled data and delete option
- `resources/views/country/event-show.blade.php`: Individual event page with edit button for authorized users
- `resources/views/country/evenements.blade.php`: Event listing with "Create Event" button for authorized users
- `resources/views/profile/public.blade.php`: Enhanced with organized event sections (upcoming/past)

**Database & Models**:
- `Event` model with slug-based routing (`getRouteKeyName()` returns `'slug'`)
- Event scopes: `published()`, `featured()`, `upcoming()`, `forCountry()`
- Helper methods: `isFree()`, `hasAvailableSpots()`, `getFormattedPriceAttribute()`
- Proper relationships with `Country` (belongsTo) and `User` as organizer

**Routes & Authorization**:
```php
// Event management routes (auth required)
Route::get('/evenements/create', [EventController::class, 'create'])->name('events.create');
Route::post('/evenements', [EventController::class, 'store'])->name('events.store');
Route::get('/evenements/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
Route::put('/evenements/{event}', [EventController::class, 'update'])->name('events.update');
Route::delete('/evenements/{event}', [EventController::class, 'destroy'])->name('events.destroy');
```

**Key Features Implemented**:
- ✅ Role-based creation (ambassadors/admins only)
- ✅ Complete CRUD operations with proper authorization
- ✅ Slug-based URLs for SEO optimization
- ✅ Online/offline event type support
- ✅ Participant tracking and pricing
- ✅ Avatar display consistency
- ✅ Public profile integration
- ✅ Form validation with French error messages
- ✅ Responsive design patterns

## Advanced Features & Systems (July 2025)

### Complete Admin Panel System
- **AdminController**: Professional admin interface with TinyMCE WYSIWYG editor
- **Admin Dashboard**: Statistics display (articles, news, users, countries) with recent content overview
- **Content Management**: Full CRUD operations for articles and news with bulk actions (publish/unpublish/delete)
- **Image Upload**: TinyMCE integration with secure image upload to `/storage/uploads/images/`
- **Admin Routes**: Protected with `role:admin` middleware at `/admin/*`
- **Admin Views**: Complete UI in `resources/views/admin/` with dashboard, articles, news management
- **Search & Filters**: Advanced filtering by status, country, category, and search terms
- **Bulk Operations**: Multi-select actions for efficient content management
- **Preview System**: Real-time content preview functionality with production-identical templates
  - Routes: `/admin/articles/preview` and `/admin/news/preview` (POST)
  - Templates: `resources/views/admin/articles/preview.blade.php` and `resources/views/admin/news/preview.blade.php`
  - JavaScript integration with fetch API for seamless preview experience
  - New tab opening instead of popup windows for better UX

### User Location & Geolocation System
- **Enhanced User Schema**: Additional fields for location sharing
  - `is_visible_on_map` (boolean) - Privacy control for map visibility
  - `latitude`, `longitude` (decimal) - Precise coordinates
  - `city_detected` (string) - Auto-detected city from coordinates
- **Location APIs**: 
  - `/api/update-location` - Update user location with coordinates
  - `/api/remove-location` - Remove user from map
  - `/api/members-with-location` - Get members for map display
- **GeolocationService**: JavaScript class for browser geolocation handling
- **Privacy Controls**: Voluntary location sharing with opt-in/opt-out functionality
- **Map Integration**: Interactive markers showing member locations globally

### Enhanced Social Media Integration
- **Extended User Fields**: Beyond YouTube, now includes:
  - `instagram_username`, `tiktok_username`, `linkedin_username`
  - `twitter_username`, `facebook_username`, `telegram_username`
  - `avatar` - User profile picture support
- **Social Profile Display**: Complete social media links on public profiles
- **Avatar System**: Profile picture upload, display, and fallback handling
- **Social Validation**: Username format validation for each platform

### Advanced API Infrastructure
- **MapController**: Comprehensive mapping API
  - `/api/map-config` - Mapbox configuration proxy to hide access tokens
  - `/api/geocode` - Forward and reverse geocoding with caching
  - OpenStreetMap Nominatim integration for reverse geocoding
  - 1-hour caching for geocoding results
- **Security Features**: API token hiding, rate limiting, input validation
- **Error Handling**: Comprehensive error responses with proper HTTP status codes

### Service Layer Architecture
- **CommunityStatsService**: Centralized statistics with 5-minute caching
  - Global member count, country count, content metrics
  - Cache invalidation methods for real-time updates
- **ContentCacheService**: Homepage content optimization
  - 15-minute cache for homepage content grids
  - Eager loading with relationship optimization
  - Country-specific content caching strategies

### Security & Infrastructure
- **SecurityHeaders Middleware**: Comprehensive Content Security Policy
  - Development vs Production CSP rules
  - Support for external services (Mapbox, TinyMCE, Google Analytics)
  - XSS protection, frame options, referrer policy
- **MaintenanceMode Middleware**: Custom maintenance system
  - Admin bypass functionality for testing
  - Route-specific exclusions
  - Status checking endpoint at `/maintenance-status`
- **Enhanced Authentication**: Username availability checking at `/api/check-username/{username}`

### Console Commands & Utilities
- **CleanHtmlEntities**: Content cleanup utility for removing HTML entities
- **GenerateNewsSlugs**: SEO-friendly URL slug generation for existing content
- **UpdateUsersLocation**: Bulk location data management
- **MaintenanceCommand**: Toggle maintenance mode via Artisan command

### Email System Integration
- **WelcomeEmail**: Professional HTML email templates
  - Responsive design with inline CSS
  - Community statistics integration
  - Call-to-action buttons and user onboarding tips
- **Zoho Mail Configuration**: Complete email service setup documentation
- **Email Templates**: Located in `resources/views/emails/` with branded styling

### Advanced Database Schema (Latest Migrations)
```php
// Latest user fields (July 2025)
'is_visible_on_map', 'latitude', 'longitude', 'city_detected',
'instagram_username', 'tiktok_username', 'linkedin_username', 
'twitter_username', 'facebook_username', 'telegram_username',
'avatar', 'name_slug'

// News and Articles SEO
'slug' fields with unique indexes for SEO-friendly URLs

// Performance Indexes
'users_map_location_idx' for map queries
'news_slug_unique' and 'articles_slug_unique' for SEO
```

### Legal & Compliance Framework
- **Complete Legal Pages**: Privacy policy, terms of service, legal mentions
- **GDPR Compliance**: Data handling and privacy documentation
- **Custom Error Pages**: 403, 404, 500, 503 with French localization
- **Maintenance Page**: Branded maintenance mode with auto-refresh

### Advanced View Architecture
- **Admin Interface**: Professional dashboard with statistics and content management
- **Member Invitation System**: Special pages for non-members accessing restricted content
- **Footer System**: Modern footer with legal links and social media
- **Responsive Design**: Mobile-first approach with hamburger menu and slide-out navigation
- **Email Templates**: Professional HTML email system with responsive design

### Performance & Optimization
- **View Composers**: `CountryComposer` with 1-hour caching eliminates N+1 queries
- **Database Indexes**: Strategic indexes for map queries, SEO URLs, and content filtering
- **Cache Strategies**: Multi-layer caching for statistics, content, and API responses
- **Asset Optimization**: Vite for modern asset compilation and hot module replacement
- **Database Optimization**: Proper foreign key relationships and query optimization

### Latest Migration Files (July 2025)
```bash
# Map and location features
2025_07_08_131746_add_map_columns_to_users_table.php
2025_07_08_131909_add_social_media_columns_to_users_table.php

# SEO and performance
2025_07_08_133131_add_slug_to_news_and_articles_tables.php
2025_07_07_175524_add_slug_to_news_table.php
2025_07_07_175946_add_unique_index_to_news_slug.php

# User experience
2025_07_06_184113_add_avatar_to_users_table.php
2025_07_06_192644_add_name_slug_to_users_table.php
2025_07_06_192702_populate_name_slug_field.php

# Performance indexes
2025_07_07_120617_add_performance_indexes_to_events_table.php
2025_07_04_094851_add_location_indexes_to_users_table.php
```

### Environment Variables & Configuration
```bash
# Required environment variables
MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
GOOGLE_ANALYTICS_ID=your_google_analytics_id_here

# Email configuration (Zoho Mail)
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sekaijin.com
MAIL_FROM_NAME="Sekaijin"
```

### Testing & Development
- **Comprehensive Seeders**: 
  - `MapDataSeeder` - Realistic location data for testing
  - `TestUsersSeeder` - Diverse user profiles for development
  - `DummyDataSeeder` - Content generation for testing
- **Console Commands**: Development utilities for data management and cleanup
- **Error Handling**: Comprehensive error pages and logging system

## Comprehensive Testing Infrastructure (July 2025)

### Test Suite Overview
- **Complete test coverage**: 222 tests with 699 assertions covering all major components
- **Testing framework**: PHPUnit with Laravel's testing utilities
- **Database**: Isolated test database (`sekaijin_test`) with `RefreshDatabase` trait
- **Test organization**: Feature tests for end-to-end functionality, helper traits for common operations

### Test Environment Configuration
```bash
# Test environment (.env.testing)
APP_ENV=testing
APP_DEBUG=true
DB_CONNECTION=mysql
DB_DATABASE=sekaijin_test
CACHE_DRIVER=array
SESSION_DRIVER=array
QUEUE_CONNECTION=sync
```

### Test Helper Traits
- **`AssertionsHelper`**: Custom assertions for HTTP responses and authentication
- **`AuthenticatesUsers`**: User authentication helpers for different role types
- **`CreatesContent`**: Factory methods for creating test content (articles, news, events)
- **`CreatesCountries`**: Country creation helpers for test setup

### Test Coverage by Component

**Authentication & User Management**:
- `AuthenticationTest.php` - Complete registration/login flow testing
- `UserProfileTest.php` - Profile management, avatar uploads, location updates
- `PublicProfileTest.php` - Public profile display and privacy controls
- `RolePermissionTest.php` - Role-based access control testing

**Content Management**:
- `ContentTest.php` - Article and news CRUD operations
- `AdminPanelTest.php` - Admin interface and bulk operations
- `EventTest.php` - Event creation and management
- `AnnouncementTest.php` - Classified ads system testing

**API & Features**:
- `ApiEndpointsTest.php` - API responses and data validation
- `FavoriteTest.php` - Favorites system with polymorphic relationships
- `MapIntegrationTest.php` - Interactive map functionality

### Test Commands
```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test --filter AdminPanelTest

# Run tests with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel

# Using composer scripts
composer test
composer test-coverage
```

### Test Data Management
- **Database factories**: Realistic test data generation for all models
- **Seeders for testing**: Specialized seeders for test environments
- **Image testing**: Fake storage for file upload testing
- **Country setup**: Automated test country creation (Thailand, Japan, Vietnam, China)

### GitHub Actions CI/CD Pipeline (July 2025)

### CI/CD Workflow Overview
- **Two-tier approach**: Comprehensive CI pipeline + simplified PR checks
- **Matrix testing**: PHP 8.1/8.2 × Node.js 18/20 combinations
- **Quality gates**: Code style, static analysis, and security checks
- **Automated processes**: Testing, deployment, and PR management

### Main CI Pipeline (`.github/workflows/ci.yml`)
```yaml
# Complete testing workflow
- Database setup (MySQL service container)
- PHP/Node.js matrix testing
- Laravel application setup
- Migration and seeding
- PHPUnit test execution
- Code coverage reporting (Codecov)
- Laravel Pint code style checking
- PHPStan static analysis
- Security audit (npm audit)
```

### Pull Request Pipeline (`.github/workflows/pull-request.yml`)
```yaml
# Fast validation checks
- Quick setup and dependency installation
- Unit tests with early failure detection
- Code style validation
- Security vulnerability scanning
- PR summary generation
```

### Code Quality Tools

**Laravel Pint Configuration** (`pint.json`):
```json
{
    "preset": "laravel",
    "rules": {
        "no_unused_imports": true,
        "ordered_imports": true,
        "single_quote": true,
        "trailing_comma_in_multiline": true
    }
}
```

**PHPStan Configuration** (`phpstan.neon`):
```neon
parameters:
    level: 5
    paths:
        - app
        - tests
    excludePaths:
        - app/Console/Kernel.php
        - app/Exceptions/Handler.php
```

### Composer Scripts Integration
```json
{
    "scripts": {
        "test": ["@php artisan test"],
        "test-coverage": ["@php artisan test --coverage"],
        "pint": ["./vendor/bin/pint"],
        "pint-test": ["./vendor/bin/pint --test"],
        "stan": ["./vendor/bin/phpstan analyse"],
        "quality": ["@pint-test", "@stan", "@test"]
    }
}
```

### Testing Best Practices Implemented

**Database Testing**:
- `RefreshDatabase` trait for test isolation
- Dedicated test database to prevent data conflicts
- Factory-based test data generation
- Proper cleanup between tests

**Authentication Testing**:
- Role-based test user creation
- Password validation with complex requirements
- Session management testing
- CSRF protection verification

**API Testing**:
- JSON response validation
- HTTP status code verification
- Rate limiting and error handling
- Authentication middleware testing

**File Upload Testing**:
- Fake storage for avatar uploads
- Image validation and processing
- File size and type restrictions
- Secure file handling

### Test Execution Results
- **Total tests**: 222 tests
- **Total assertions**: 699 assertions
- **Execution time**: ~45 seconds
- **Memory usage**: ~128MB
- **Coverage**: High coverage across all major components

### Continuous Integration Features
- **Automated testing**: All tests run on every push and PR
- **Code quality checks**: Style and static analysis enforcement
- **Security scanning**: Dependency vulnerability detection
- **Performance monitoring**: Test execution time tracking
- **Deployment gates**: Tests must pass before deployment

### Test Maintenance
- **Regular updates**: Test suites updated with new features
- **Regression testing**: Comprehensive coverage prevents regressions
- **Documentation**: Clear test naming and documentation
- **Monitoring**: CI/CD pipeline health monitoring