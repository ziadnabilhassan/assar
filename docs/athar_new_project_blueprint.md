# Athar Flutter Backend Project Blueprint

This blueprint describes a clean Laravel backend that matches the current Athar project and is easier to consume from Flutter.

## Stack

- Laravel 10
- MySQL
- Laravel Sanctum token auth for Flutter
- Blade admin dashboard, matching the current dashboard modules
- JSON API under `/api`
- Uploads stored under `storage/app/public`
- Translatable fields stored as JSON, for example `{ "en": "Shirt", "ar": "قميص" }`

## Main Apps

1. Flutter mobile app
   - Uses `/api/*`
   - Authenticates with `Authorization: Bearer <token>`
   - Uses JSON request and response bodies

2. Admin dashboard
   - Uses web session login
   - Path: `/admin`
   - Similar modules to the current dashboard:
     - Sliders
     - Banners
     - Pages
     - Designs
     - Reviews
     - Settings
     - Contacts and social links
     - Category types
     - Categories
     - Brands
     - Colors
     - Sizes
     - Products
     - Product images
     - Deliveries
     - Promo codes
     - Orders
     - Users
     - Messages
     - Newsletters

## Recommended API Endpoints

### Auth

| Method | Endpoint | Purpose |
| --- | --- | --- |
| POST | `/api/auth/register` | Create mobile customer account |
| POST | `/api/auth/login` | Login and return Sanctum token |
| GET | `/api/auth/me` | Get authenticated user |
| POST | `/api/auth/logout` | Delete current token |

### Storefront

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/home` | Home data: sliders, banners, categories, products, reviews |
| GET | `/api/pages/{id}` | Static page details |
| GET | `/api/contacts` | Contact and social data |
| GET | `/api/products` | Paginated product listing |
| GET | `/api/products/category-types/{id}` | Products by category type |
| GET | `/api/products/categories/{id}` | Products by category |
| GET | `/api/products/{id}` | Product details |
| GET | `/api/products/{productId}/colors/{colorId}/variants` | Variant list by product and color |

### Cart

For Flutter, prefer a database cart or local Flutter cart. The current backend uses session cart, which is harder for mobile.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/cart` | Get current cart |
| POST | `/api/cart/items` | Add item |
| PATCH | `/api/cart/items/{cartItemId}` | Update quantity |
| DELETE | `/api/cart/items/{cartItemId}` | Remove item |
| DELETE | `/api/cart` | Clear cart |

### Checkout

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/checkout` | Get cart, delivery, coupon, totals |
| POST | `/api/orders` | Create order |
| GET | `/api/orders` | Customer order history |
| GET | `/api/orders/{id}` | Customer order details |

### Admin API

Use these only if the dashboard is a SPA. If the dashboard is Blade, keep resource controllers under web routes.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/admin/dashboard` | Counts and recent orders |
| CRUD | `/api/admin/sliders` | Slider management |
| CRUD | `/api/admin/banners` | Banner management |
| CRUD | `/api/admin/pages` | Page management |
| CRUD | `/api/admin/designs` | Design management |
| CRUD | `/api/admin/reviews` | Review management |
| CRUD | `/api/admin/category-types` | Category type management |
| CRUD | `/api/admin/categories` | Category management |
| CRUD | `/api/admin/products` | Product management |
| CRUD | `/api/admin/colors` | Color management |
| CRUD | `/api/admin/sizes` | Size management |
| CRUD | `/api/admin/deliveries` | Delivery management |
| CRUD | `/api/admin/promo-codes` | Promo code management |
| CRUD | `/api/admin/orders` | Order management |
| CRUD | `/api/admin/users` | User management |

## Response Format

Use one consistent format:

```json
{
  "status": true,
  "message": "Success message",
  "data": {}
}
```

Validation error:

```json
{
  "status": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

## Flutter Login Flow

1. Send `POST /api/auth/login`.
2. Save the returned token in secure storage.
3. Send protected requests with:

```http
Authorization: Bearer <token>
Accept: application/json
```

4. On logout, call `POST /api/auth/logout`, then remove the token locally.

## Dashboard Navigation

Recommended dashboard sidebar:

- Overview
- Storefront
  - Sliders
  - Banners
  - Pages
  - Designs
  - Reviews
- Catalog
  - Category Types
  - Categories
  - Brands
  - Products
  - Colors
  - Sizes
- Sales
  - Orders
  - Deliveries
  - Promo Codes
- Customers
  - Users
  - Addresses
- Communication
  - Messages
  - Newsletters
  - Contacts
- Settings

## Files Created

- Postman collection: `postman/athar_flutter_api.postman_collection.json`
- Database collection: `database/athar_database_collection.json`

