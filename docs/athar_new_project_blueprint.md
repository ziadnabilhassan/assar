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

Flutter cart endpoints use Sanctum token auth and store cart rows per user in the database. Designer cart items may omit `product_id` when they are mockup-only products, and `design_data` is stored as JSON without reshaping.

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/cart` | Get current cart |
| POST | `/api/cart/items` | Add item |
| PUT | `/api/cart/items/{cartItemId}` | Update quantity, color, or size |
| DELETE | `/api/cart/items/{cartItemId}` | Remove item |
| DELETE | `/api/cart` | Clear cart |

#### Add Custom Designer Item

Save the designer state first, then pass the returned saved design `data.id` as the cart item's `design_id`.

```http
POST /api/designs/saved
Authorization: Bearer {token}
Content-Type: application/json
```

```json
{
  "name": "Custom T-shirt Design",
  "design_id": 1,
  "design_data": {
    "product": {},
    "template": {},
    "canvas": {},
    "layers": []
  },
  "sticker_ids": [1, 2]
}
```

```json
{
  "success": true,
  "data": {
    "id": 15,
    "name": "Custom T-shirt Design",
    "design_id": 1,
    "design_data": {
      "product": {},
      "template": {},
      "canvas": {},
      "layers": []
    },
    "preview_image_url": null,
    "created_at": "2026-07-22T10:00:00.000000Z"
  }
}
```

```http
POST /api/cart/items
Authorization: Bearer {token}
Content-Type: application/json
```

```json
{
  "product_id": null,
  "name": "T-shirt مخصص",
  "price": 299,
  "quantity": 1,
  "color": "أبيض",
  "size": "M",
  "image_url": "assets/images/design/t-shirt.png",
  "preview_image_url": null,
  "is_custom_design": true,
  "design_id": 15,
  "design_data": {
    "product": {},
    "template": {},
    "canvas": {},
    "layers": []
  }
}
```

#### Add Normal Product

```http
POST /api/cart/items
Authorization: Bearer {token}
Content-Type: application/json
```

```json
{
  "product_id": 123,
  "variant_id": 45,
  "quantity": 2,
  "color": "Black",
  "size": "L",
  "is_custom_design": false
}
```

If `variant_id` is sent, the backend validates available stock. If `name`, `price`, `image_url`, `color`, or `size` are omitted for normal products, the backend fills what it can from the catalog.

Custom designer cart items must include either `design_id` or `design_data`. The cart item keeps a JSON snapshot of `design_data` so checkout still has the exact design even if the saved design changes later.

#### Cart Response

```json
{
  "success": true,
  "status": true,
  "data": {
    "items": [
      {
        "id": 1,
        "product_id": null,
        "variant_id": null,
        "name": "T-shirt مخصص",
        "price": 299,
        "quantity": 1,
        "image_url": "assets/images/design/t-shirt.png",
        "preview_image_url": null,
        "color": "أبيض",
        "size": "M",
        "is_custom_design": true,
        "design_id": 15,
        "design_data": {
          "product": {},
          "template": {},
          "canvas": {},
          "layers": []
        }
      }
    ],
    "subtotal": 299,
    "delivery_fee": 50,
    "total": 349
  }
}
```

### Checkout

| Method | Endpoint | Purpose |
| --- | --- | --- |
| GET | `/api/checkout` | Get cart, delivery, coupon, totals |
| POST | `/api/orders` | Create order |
| GET | `/api/orders` | Customer order history |
| GET | `/api/orders/{id}` | Customer order details |

`POST /api/orders` creates an authenticated user's order from the database cart, copies each cart item into `order_details`, preserves `is_custom_design`, `design_id`, `design_data`, and `image_url`, then clears the cart after the database transaction succeeds.

```http
POST /api/orders
Authorization: Bearer {token}
Content-Type: application/json
```

```json
{
  "first_name": "Athar",
  "last_name": "User",
  "phone": "01000000000",
  "city": "Cairo",
  "address": "Nasr City",
  "delivery_id": 1,
  "note": "Call before delivery"
}
```

```json
{
  "success": true,
  "status": true,
  "message": "Order created successfully",
  "data": {
    "order": {
      "id": 55,
      "code": 123456,
      "user_id": 9,
      "user_name": "Athar User",
      "phone": "01000000000",
      "delivery": "Delivery",
      "city": "Cairo",
      "address": "Nasr City",
      "shipping": 50,
      "total": 349,
      "status": "pending",
      "note": "Call before delivery",
      "items": [
        {
          "id": 88,
          "product_id": null,
          "name": "T-shirt مخصص",
          "price": 299,
          "quantity": 1,
          "total_price": 299,
          "image_url": "assets/images/design/t-shirt.png",
          "preview_image_url": null,
          "color": "أبيض",
          "size": "M",
          "is_custom_design": true,
          "design_id": 15,
          "design_data": {
            "product": {},
            "template": {},
            "canvas": {},
            "layers": []
          }
        }
      ]
    },
    "cart": {
      "items": [],
      "subtotal": 0,
      "delivery_fee": 0,
      "total": 0
    }
  }
}
```

#### Order History

```http
GET /api/orders
Authorization: Bearer {token}
```

```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 1001,
        "status": "pending",
        "total": 349,
        "created_at": "2026-07-22T10:00:00.000000Z",
        "items_count": 1
      }
    ]
  }
}
```

```http
GET /api/orders/1001
Authorization: Bearer {token}
```

```json
{
  "success": true,
  "data": {
    "id": 1001,
    "status": "pending",
    "subtotal": 299,
    "delivery_fee": 50,
    "discount": 0,
    "total": 349,
    "shipping_info": {},
    "payment_method": "cashOnDelivery",
    "items": [
      {
        "id": 1,
        "product_id": null,
        "name": "T-shirt مخصص",
        "price": 299,
        "quantity": 1,
        "image_url": "assets/images/design/t-shirt.png",
        "preview_image_url": null,
        "color": "أبيض",
        "size": "M",
        "is_custom_design": true,
        "design_id": 15,
        "design_data": {
          "product": {},
          "template": {},
          "canvas": {},
          "layers": []
        }
      }
    ]
  }
}
```

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
