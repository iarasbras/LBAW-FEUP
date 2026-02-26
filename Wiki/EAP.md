# EAP: Architecture Specification and Prototype

> Designed to redefine the reading experience, Liberato brings together technology, accessibility, and community to create a modern space for book lovers. The platform alInnovations readers to discover, purchase, and review books with ease. By giving costumers a seamless shopping experience, Liberato empowers readers, authors, and publishers to connect through a shared passion for literature.

## A7: Web Resources Specification

> This artifact describes the web API to be developed for Literato, detailing the required resources, their properties, and JSON responses. This API includes create, read, update, and delete operations (where applicable) for each resource.

### 1. Overview

> Identification and overview of the modules that will be part of the application.

| Identifier | Module | Description |
|------------|--------|-------------|
| M01 | Authentication | Web resources for user authentication and registration, including login, logout and user sign-up. |
| M02 | Profile | Web resources for accessing user information: profile page, edit profile, password recovery and account settings. |
| M03 | Catalog | Web resources for browsing and viewing books: listings, detail pages, categories, genre and pagination. |
| M04 | Shopping Cart | Web resources to add/update/remove items; session cart for guests and DB-backed cart for authenticated users. |
| M05 | Checkout & Orders | Web resources for checkout, order creation, order confirmation and order history and stock checks. |
| M06 | Payments | Web resources for payment handling, supports Card, MBWay and PayPal. |
| M07 | Search & Discovery | Web resources for Exact Match and Full-Text Search, filters, ordering and faceted/multi-attribute queries. |
| M08 | Reviews & Ratings | Web resources to view, write, edit and remove product reviews, including moderation/reporting capabilities. |
| M09 | Notifications | Web resources for user notifications and alerts (order status, wishlist availability, price changes); notification list and mark-as-read actions. |
| M10 | Administration | Admin web resources to manage users, products, orders, stock, categories and platform moderation. |
| M11 | Seller Tools | Web resources for sellers to manage products, stock and sales statistics. |
| M12 | Home & Platform Info | Web resources for the landing page, About, Contact and platform metadata used across the site. |

### 2. Permissions

> The permissions used by each module, necessary to access its data and features.

| Identifier | Name | Description |
|------------|------|-------------|
| PUB | Public | Unauthenticated user (guest) - can view public pages and access login/register. |
| USR | User | Authenticated user - can access profile, cart and purchase fInnovations (prototype), and other user-only features. |
| BLK | Blocked | Blocked user - restricted from normal user actions. |
| OWN | Owner | Owner of a resource (e.g., a user owns their profile or a user owns their content) - used for owner-only actions. |
| ADM | Administrator | Admin of the system - full access to `/admin` and management operations. |

### 3. OpenAPI Specification

> OpenAPI specification in YAML format to describe the vertical prototype's web resources.

> Full file available at [`a7_openapi.yaml`](https://gitlab.up.pt/lbaw/lbaw2526/lbaw25134/-/blob/main/a7_openapi.yaml?ref_type=heads) in the associated repository.

```yaml
openapi: 3.0.0
info:
  title: Liberato API
  description: |
    OpenAPI specification for the Liberato online bookstore.
    This documents the public catalog, shopping cart, user profile, and admin endpoints.
  version: 1.0.0
servers:
  - url: http://127.0.0.1:8001/
    description: Local Development Server

paths:
...
```

---

## A8: Vertical prototype

> The Vertical Prototype implements the project's highest-priority user stories to validate the chosen architecture and to provide hands‑on familiarity with the technologies used in the project. This artifact lists the implemented user stories, maps each implemented story to the exact web resources (routes, controller, views) and the steps necessary to test the prototype.

### 1. Implemented Features

### 1.1 Implemented User Stories

> User stories that were implemented in the prototype.

#### 1.1.1 User (unauthenticated)

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US01 | Registration | High | João Santos | As a visitor, I want to register a new account, so that I can start using the system. |
| US02 | Login | High | João Santos | As a user, I want to log in to my account, so that I can securely access and end my sessions. |
| US03 | View & Search Products | High | João Santos | As a user, I want to view and search products, so that I can browse the store. |
| US04 | Exact Match Search | High | João Santos | As a user, I want to search with exact matches, so that I can quickly find specific results. |
| US05 | Full-text Search | High | Dinis Pinto | As a user, I want to use full-text search, so that I can find results by keywords. |
| US06 | Add item to Cart | High | Iara Brás | As a user, I want to add items to my cart, so that I can buy my purchases. |
| US07 | Manage Cart | High | Iara Brás | As a user, I want to update or remove items in my cart, so that I can manage my purchases. |
| US08 | View Product Details | High | João Santos | As a user, I want to view detailed information about a product, so that I can make informed purchase decisions. |
| US09 | View Product Reviews | Medium | João Santos | As a user, I want to view product reviews, so that I can evaluate others’ experiences before buying. |
| US11 | Search Filters (category) | Medium | João Santos | As a user, I want to apply filters to my search, so that I can narrow down results easily. |
| US13 | Browse Product Categories | Medium | João Santos | As a user, I want to browse product categories, so that I can easily find types of products I’m interested in. |
| US15 | Placeholders in Form Inputs | Innovation | Ricardo Fernandes | As a user, I want to see placeholders in form inputs, so that I understand what information to enter. |

#### 1.1.2 User

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US10 | Search over Multiple Attributes | Medium | Dinis Pinto | As a user, I want to search by multiple attributes, so that I can refine results. |

#### 1.1.3 Authenticated user

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US19 | View Profile | High | João Santos | As an authenticated user, I want to view my profile information, so that I can check my personal data. |
| US20 | Edit Profile | High | João Santos | As an authenticated user, I want to edit my profile, so that I can update my information when needed. |
| US21 | Checkout | High | Iara Brás | As an authenticated user, I want to proceed to checkout, so that I can complete my purchase securely and efficiently. |
| US26 | Upload/Update Profile Picture | Medium | João Santos | As an authenticated user, I want to upload or update my profile picture, so that my account is personalised. |
| US32 | Log Out | Medium | João Santos | As an authenticated user, I want to log out of my account, so that I can ensure my personal data and session remain secure when I leave the system. |
| US34 | Manage Purchases | Medium | Ricardo Fernandes | As an authenticated user, I want to view my purchase history, so that I can track past orders. |

#### 1.2. Implemented Web Resources

> Identify the web resources that were implemented in the prototype.

> Module M01: Authentication & Profile

| Web Resource Reference | URL |
|------------------------|-----|
| R01: Login Form | `/login` (GET) |
| R02: Login Action | `POST /login` |
| R03: Logout Action | `GET /logout` |
| R04: Register Form | `/register` (GET) |
| R05: Register Action | `POST /register` |
| R06: Profile (show/update) | `/profile` (GET / POST, auth required) |

> Module M02: Catalog

| Web Resource Reference | URL |
|------------------------|-----|
| R11: Catalog listing (paginated, search, category) | `/catalog` (GET, supports `?q=` and `?category=`) |
| R12: Book detail | `/catalog/{book}` (GET) |

> Module M03: Shopping Cart

| Web Resource Reference | URL |
|------------------------|-----|
| R21: Cart page (view) | `/cart` (GET) |
| R22: Add item to cart | `POST /cart/add` |
| R23: Buy action | `POST /cart/buy` |
| R24: Checkout page | `/cart/checkout` (GET) |
| R25: Complete purchase | `POST /cart/complete` |
| R26: Update cart quantities | `PUT/PATCH /cart/update` |
| R27: Remove item from cart | `POST /cart/remove` |

Note: The prototype implements a hybrid cart strategy — guests use a session-based cart while authenticated users have a DB-backed cart stored in the `shopping_cart` table. On user login, the session cart is merged into the DB cart (`app/Providers/AppServiceProvider.php`), and controller actions keep the session in sync for the UI.

> Module M04: Home & Platform Information

| Web Resource Reference | URL |
|------------------------|-----|
| R31: Home / landing page | `/` (GET) |

### 2. Prototype

> Command to start the Docker image from the group's Container Registry. User credentials necessary to test all features. Link to the source code in the group's Git repository.

**Start the docker image:**

```
docker-compose build
```

```
docker-compose up
```

**Outros:**

```
php artisan storage:link  # abre a ligação para utilização de imagens
```

#### User Credentials:

**Admin**

- Email: admin1@liberato.com
- Password: password

**Regular User**

- Email: john@example.com
- Password: password

**The code is available at**: https://gitlab.up.pt/lbaw/lbaw2526/lbaw25134

---

## Revision history

Changes made to the first submission:

1. ...
2. ...

---

GROUP25134, 26/11/2025

* Dinis Afonso Nunes Pinto, up202306480@up.pt (Editor)
* Iara Catarina Sampaio dos Santos Brás, up202208825@up.pt
* João Paulo Silva Santos, up202006525@up.pt
* Ricardo Alexandre Ribeiro Fernandes, up202304126@up.pt
