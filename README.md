# 🛒 Laravel E-commerce API

A full-featured **E-commerce RESTful API** built with **Laravel**, supporting:
- Dual Authentication: Admin & Customer
- Product and Category Management
- Cart and Order System
- Order Approval Workflow

## 🚀 Features

- ✅ Separate Admin and Customer login systems
- ✅ Product & Category management by Admin
- ✅ Customers can add to cart, view cart, update quantity
- ✅ Order placing system
- ✅ Admin order approval/decline
- ✅ Secure token-based API authentication (Laravel Sanctum)

---

## 🔥 Future Enhancements
- Payment gateway integration
- Inventory management
- Admin dashboard charts
- Customer profile and address management
- Email notification on order status update
- Customer Shipping Addresses

---

## 👥 User Roles

### Admin
- Access via: `/api/admin/login`
- Manages products, categories, and orders.

### Customer
- Register/Login via: `/api/customer/register`, `/api/customer/login`
- Can browse products, add to cart, place orders.

---

## 🔐 Authentication

- Laravel **Sanctum** for API token-based authentication.
- Role-based guards and middleware:
  - `auth:sanctum` + role check (`admin`, `customer`).
- Pass token in the `Authorization: Bearer <token>` header.

---

## 🧩 API Endpoints

### 🔸 Admin API

| Method | Endpoint                          | Action                     |
|--------|-----------------------------------|----------------------------|
| POST   | `/api/admin/login`                | Admin Login                |
| GET    | `/api/admin/categories`           | List Categories            |
| POST   | `/api/admin/categories`           | Create Category            |
| PUT    | `/api/admin/categories/{id}`      | Update Category            |
| DELETE | `/api/admin/categories/{id}`      | Delete Category            |
| GET    | `/api/admin/products`             | List Products              |
| POST   | `/api/admin/products`             | Add Product                |
| PUT    | `/api/admin/products/{id}`        | Update Product             |
| DELETE | `/api/admin/products/{id}`        | Delete Product             |
| GET    | `/api/admin/orders`               | View All Orders            |
| GET    | `/api/admin/orders/{id}`          | View Order Details         |
| PATCH  | `/api/admin/orders/{id}/status`   | Approve or Decline Order   |

---

### 🔸 Customer API

| Method | Endpoint                       | Action                        |
|--------|--------------------------------|-------------------------------|
| POST   | `/api/customer/register`       | Register                      |
| POST   | `/api/customer/login`          | Login                         |
| GET    | `/api/products`                | List Products                 |
| GET    | `/api/products/{id}`           | Product Details               |
| POST   | `/api/cart`                    | Add to Cart                   |
| GET    | `/api/cart`                    | View Cart                     |
| PUT    | `/api/cart/{id}`               | Update Cart Quantity          |
| DELETE | `/api/cart/{id}`               | Remove from Cart              |
| POST   | `/api/orders`                  | Place Order from Cart         |
| GET    | `/api/orders`                  | View Customer Orders          |
| GET    | `/api/orders/{id}`             | Order Details                 |

---

## 🗄️ Database Schema (Core Tables)

### users
| id | name | email | password | role (admin, customer) |

### categories
| id | name |

### products
| id | name | category_id | description | price | image |

### carts
| id | user_id | product_id | quantity |

### orders
| id | user_id | status (`pending`, `approved`, `declined`) |

### order_items
| id | order_id | product_id | quantity | price |

---

## Sample API Payloads

### Create Product (Admin)

POST /api/admin/products
```json
{
  "name": "iPhone 14",
  "category_id": 1,
  "description": "Latest Apple iPhone",
  "price": 1299,
  "image": "storage/products/iphone14.jpg"
}
```
---

## Sample API Response Example
```json
{
  "status": "success",
  "message": "Order placed successfully.",
  "data": {
    "order_id": 123,
    "status": "pending"
  }
}
```
---

## 🔧 Installation

### Requirements
- PHP 8.1+
- Composer 2.0+
- MySQL 8.0+

### Steps
```bash
git clone https://github.com/ashikurrahman-dev/e-commerce_api.git
cd e-commerce-api

# Backend
composer install
cp .env.example .env
php artisan key:generate

# Configure DB in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_pass

php artisan migrate
php artisan db:seed

# Sanctum (for API auth)
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate




