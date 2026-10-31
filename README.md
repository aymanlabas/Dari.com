# L9it — The Modern Moroccan Classified Marketplace

**Tagline:** L9it li kat9elleb 3lih

L9it is a premium Moroccan classified marketplace built with modern technologies, inspired by platforms like Avito but with a completely original UI, branding, and architecture.

## Project Architecture

| Layer | Technology |
|-------|-----------|
| **Frontend** | Next.js 14 + TypeScript + Tailwind CSS + TanStack Query |
| **Backend** | PHP 8.3 + Symfony 6.4 + Doctrine ORM + JWT Auth |
| **Database** | MySQL 8.0 |
| **Deployment** | Docker Compose |

## Features

### User System
- Registration with email/phone verification
- JWT-based authentication
- Role-based access (USER, ADMIN)
- Password hashing (bcrypt)
- Profile management

### Classifieds
- Multi-step advertisement creation wizard
- Image upload with validation (JPG, PNG, WEBP, max 5MB)
- Category and subcategory system
- Morocco-specific location system (Region > City > Neighborhood)
- SEO-friendly URLs with slugs
- Search with filters (keyword, category, city, price range, condition, sort)

### Interaction
- Favorites system
- Messaging between buyers and sellers
- Reporting system (Arnaque, Faux produit, Contenu interdit, etc.)
- Notifications

### Admin Panel
- Dashboard with statistics
- User management
- Listing moderation (approve, reject, delete)
- Category management
- Location management
- Report management

### Design
- Modern, premium, minimal UI
- Moroccan-inspired without being cliché
- Fully responsive (320px to 1440px)
- RTL-ready architecture for Arabic
- French interface

## Quick Start

### Using Docker (Recommended)
```bash
# Development
docker compose -f docker/docker-compose.dev.yml up -d

# Production
docker compose -f docker/docker-compose.yml up -d
```

### Manual Setup

**Prerequisites:** Node.js 18+, PHP 8.3+, MySQL 8.0, Composer 2.0

1. **Frontend:**
```bash
cd frontend
npm install
npm run dev
```

2. **Backend:**
```bash
cd backend
composer install
cp .env.example .env
php bin/console doctrine:schema:create
php bin/console app:seed:data
php -S localhost:8000 -t public
```

## Environment Variables

Copy `.env.example` to `.env` and fill in the values:

| Variable | Description | Default |
|----------|-------------|---------|
| `DB_HOST` | Database host | `localhost` |
| `DB_PORT` | Database port | `3306` |
| `DB_DATABASE` | Database name | `l9it` |
| `DB_USERNAME` | Database username | `l9it` |
| `DB_PASSWORD` | Database password | `l9it` |
| `JWT_SECRET_KEY` | JWT signing key | `change-me` |
| `APP_ENV` | Environment | `dev` |
| `NEXT_PUBLIC_API_URL` | Backend API URL | `http://localhost:8000` |

## API Endpoints

### Authentication
- `POST /api/auth/register` — Register a new user
- `POST /api/auth/login` — Login and get JWT token
- `POST /api/auth/logout` — Logout

### Listings
- `GET /api/listings` — List all published listings with filters
- `GET /api/listings/{slug}` — Get single listing
- `POST /api/listings` — Create new listing (requires auth)
- `PUT /api/listings/{id}` — Update listing
- `DELETE /api/listings/{id}` — Delete listing

### Categories
- `GET /api/categories` — List all categories

### Locations
- `GET /api/locations` — List regions, cities, and neighborhoods

### Favorites
- `POST /api/favorites` — Add listing to favorites
- `DELETE /api/favorites/{listingId}` — Remove from favorites

### Messages
- `GET /api/conversations` — Get conversations
- `POST /api/messages` — Send a message

### Reports
- `POST /api/reports` — Report a listing

### Admin
- `GET /api/admin/stats` — Dashboard statistics
- `GET /api/admin/users` — Get all users
- `PUT /api/admin/listings/{id}/approve` — Approve listing
- `PUT /api/admin/listings/{id}/reject` — Reject listing

## Security

- Passwords hashed with bcrypt
- JWT token-based authentication
- Input validation and sanitization
- File upload validation (MIME type, extension, size)
- SQL injection prevention (Doctrine ORM parameterized queries)
- CSRF protection
- Rate limiting on authentication endpoints
- Role-based access control
- Users can only edit/delete their own listings

## Testing

- **Backend:** PHPUnit with Symfony PHPUnit Bridge
- **Frontend:** Jest + React Testing Library
- **E2E:** Playwright

## Development Phases

- [x] Phase 1: Project Setup (Monorepo, Next.js, Symfony)
- [x] Phase 2: Authentication (Backend API, JWT, Frontend Auth Pages)
- [ ] Phase 3: Categories and Locations
- [ ] Phase 4: Advertisement CRUD
- [ ] Phase 5: Search and Filters
- [ ] Phase 6: Favorites
- [ ] Phase 7: Messaging
- [ ] Phase 8: Reports and Moderation
- [ ] Phase 9: Admin Dashboard
- [ ] Phase 10: Notifications
- [ ] Phase 11: SEO
- [ ] Phase 12: Performance and Security
- [ ] Phase 13: Testing
- [ ] Phase 14: Production Deployment

## License

MIT
