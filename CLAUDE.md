# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Sekaijin** (世界人 - "citoyen du monde" en japonais) is a Laravel-based community platform for French expatriates worldwide.

## Core Architecture

### Technology Stack
- **Backend**: Laravel 10.x (PHP 8.1+)
- **Frontend**: Blade templates with Tailwind CSS v3 and jQuery
- **Database**: MySQL with custom expat-focused user fields
- **Build Tools**: Vite for asset compilation
- **Authentication**: Custom Laravel authentication system
- **Security**: Cloudflare Turnstile integration with automatic form protection

### Key Database Schema
The User model includes expat-specific fields:
- Required: `name` (pseudo), `email`
- Optional: `first_name`, `last_name`, `birth_date`, `phone`, `country_residence`, `city_residence`, `interest_country`, `bio`
- Location: `latitude`, `longitude`, `city_detected`, `is_visible_on_map`
- Social: `youtube_username`, `instagram_username`, `tiktok_username`, `linkedin_username`, `twitter_username`, `facebook_username`, `telegram_username`
- Community: `is_verified`, `last_login`, `is_public_profile`, `avatar`
- Role system: `role` field (free, premium, ambassador, admin)

## Development Workflow

### Git Workflow Rules
**IMPORTANT: Claude Code must follow these Git rules at all times:**

- **NEVER push directly to main branch** - Always create feature branches
- **Branch naming**: `feature/description-of-feature` or `fix/bug-description`
- **Always create Pull Requests** for code review before merging to main
- **Only push to main** after explicit user approval
- **Commit messages**: Use conventional commits format with appropriate emojis

**Workflow steps:**
1. Create feature branch: `git checkout -b feature/feature-name`
2. Make changes and commit with descriptive messages
3. Push to feature branch: `git push origin feature/feature-name`
4. Create PR for review (use `gh pr create` command)
5. Wait for user approval before merging to main

### Local Development
**IMPORTANT: DO NOT start the Laravel server - it is already running**
- Laravel backend runs on http://127.0.0.1:8000 
- Use `npm run dev` for Vite frontend assets if needed

### Essential Commands
```bash
# Database
php artisan migrate
php artisan make:migration migration_name

# Assets
npm run dev          # Development with hot reload
npm run build        # Production build

# Testing
php artisan test                              # PHPUnit tests
php artisan dusk                              # E2E tests
./vendor/bin/pint                            # Code formatting
./vendor/bin/pint --config=pint.json        # With config

# Quality
composer test        # Run tests
composer pint        # Format code
composer stan        # Static analysis
```

## Application Features

### French Localization & Expat Focus
- All content in French with routes like `/inscription`, `/connexion`, `/deconnexion`
- Complete country selection (195 countries) organized by continent
- User profiles designed for expat needs (residence/interest countries, bio, social links)
- Community stats display and interactive map integration

### Authentication & Security
- Simplified registration: pseudo, email, country, password (min 8 chars)
- **Email verification**: Required for avatar upload and public profile features
- CSRF protection, email uniqueness validation, terms acceptance
- Cloudflare Turnstile integration with form protection
- Role-based access control with 4 levels

### Content Management System
- **News System**: Actualités with categories (administrative, vie-pratique, culture, economie)
- **Blog Articles**: Full article management with categories (témoignage, guide-pratique, travail, lifestyle, cuisine)
- **Event Management**: Online/offline events with registration, pricing, participant tracking
- **Announcements/Classified Ads**: 4 types (vente, location, colocation, service) with multi-image support

### Advanced Features
- **Country-Based Architecture**: Dynamic routing (`/thailande/*`, `/japon/*`) with 4 supported countries
- **Interactive Map**: Mapbox GL JS with global expat distribution
- **Public Profiles**: `/membre/{pseudo}` URLs with privacy controls
- **Admin Panel**: Complete content management with TinyMCE WYSIWYG editor
- **Mobile Navigation**: Slide-out sidebar menu with profile integration

## Key Files for Development

### Controllers
- `HomeController.php` - Homepage with real database content (2x2 grid layout)
- `AuthController.php` - Registration/login with username validation
- `ProfileController.php` - User profile CRUD operations
- `CountryController.php` - Country-based content management
- `AdminController.php` - Admin interface with bulk operations
- `AnnouncementController.php` - Classified ads system

### Models & Database
- `User.php` - Extended with expat fields and role system
- `Country.php`, `News.php`, `Article.php`, `Event.php`, `Announcement.php` - Content models
- Migration files for user extensions, content tables, and performance indexes

### Views & Frontend
- `layout.blade.php` - Responsive navigation with country links and profile integration
- `home.blade.php` - 2x2 grid layout with hero section and interactive map
- `auth/` directory - Custom styled registration/login forms
- `profile/` directory - Profile management and public profiles
- `country/` directory - Country-specific content views
- `admin/` directory - Admin interface with dashboard and content management

### Security & Middleware
- `RoleMiddleware.php` - Role-based access control
- `EnsureValidCountry.php` - Country context validation
- `SecurityHeaders.php` - Content Security Policy
- `MaintenanceMode.php` - Custom maintenance system

## Configuration

### Environment Variables
```bash
# Required
MAPBOX_ACCESS_TOKEN=your_mapbox_access_token_here
GOOGLE_ANALYTICS_ID=your_google_analytics_id_here

# Email (Zoho Mail) - Required for email verification
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=your_email@domain.com
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sekaijin.com
MAIL_FROM_NAME="Sekaijin"
```

### Database
- Name: `sekaijin` (production), `sekaijin_test` (testing), `sekaijin_dusk` (E2E)
- Connection: MySQL on localhost:3306, user: root, no password

## Testing Infrastructure

### Test Suite
- **222 tests with 699 assertions** covering all major components
- **PHPUnit** with Laravel testing utilities
- **Laravel Dusk** for E2E browser testing
- Isolated test databases with `RefreshDatabase` trait

### Test Organization
- `tests/Feature/` - End-to-end functionality tests
- `tests/Unit/` - Unit tests for individual components
- `tests/Browser/` - Dusk E2E tests
- Helper traits: `AssertionsHelper`, `AuthenticatesUsers`, `CreatesContent`, `CreatesCountries`

### CI/CD Pipeline
- **GitHub Actions** with two-tier approach (comprehensive CI + PR checks)
- **Matrix testing**: PHP 8.1/8.2 × Node.js 18/20
- **Quality gates**: Laravel Pint, PHPStan, security audits
- **Code coverage** reporting via Codecov

## Performance Optimizations

- **View Composers**: `CountryComposer` with 1-hour caching
- **Service Layer**: `CommunityStatsService` (5-min cache), `ContentCacheService` (15-min cache)
- **Database Indexes**: Strategic indexes for map queries, SEO URLs, content filtering
- **Eager Loading**: Optimized queries with `with()` clauses and `select()` optimization
- **Asset Optimization**: Vite for modern compilation and HMR

## Recent Critical Updates

### Announcements/Classified Ads System (July 2025)
Complete classified ads platform with 4 types, multi-image upload, admin moderation system, and responsive multi-step forms.

### Security Enhancements (July 2025)
Cloudflare Turnstile integration with `TurnstileSecurityManager` preventing form submission bypass vulnerabilities.

### Testing Implementation (July 2025)
Laravel Dusk E2E testing with comprehensive authentication test suite and CI/CD integration.

### Content Management (July 2025)
Admin panel with TinyMCE editor, bulk operations, preview system, and content corruption fixes.

### Email Verification System (July 2025)
Complete email verification implementation with custom templates, security restrictions, and comprehensive error handling.