-- WARNING: this script will delete all data from the database
-- DO NOT RUN ON PRODUCTION DATABASE

-- Nukes everything and resets auto-incrementing IDs
TRUNCATE TABLE
    "admin",
    "book",
    "category",
    "notification",
    "order_book",
    "orders",
    "payment",
    "platform_information",
    "request",
    "review",
    "seller",
    "seller_book",
    "shopping_cart",
    "users",
    "wishlist"
RESTART IDENTITY
CASCADE;