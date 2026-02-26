
CREATE DATABASE liberato_db;

--
-- DROPS
--

-- 1. Drop Triggers (must be dropped before the function they execute)
DROP TRIGGER IF EXISTS trg_update_book_stock ON order_book;
DROP TRIGGER IF EXISTS trg_prevent_negative_stock ON order_book;
DROP TRIGGER IF EXISTS trg_update_order_total ON order_book;
DROP TRIGGER IF EXISTS trg_auto_block_user ON request;
DROP TRIGGER IF EXISTS trg_sync_seller_stock ON book;
DROP TRIGGER IF EXISTS trg_book_search_update ON book; -- New trigger from previous recommendation

-- 2. Drop Functions (must be dropped before the tables if they reference them, or just to be safe)
DROP FUNCTION IF EXISTS update_book_stock_after_order();
DROP FUNCTION IF EXISTS prevent_negative_stock();
DROP FUNCTION IF EXISTS update_order_total();
DROP FUNCTION IF EXISTS auto_block_user();
DROP FUNCTION IF EXISTS sync_seller_stock();
DROP FUNCTION IF EXISTS book_search_update();

-- 3. Drop Tables (must be dropped in an order that respects foreign key constraints)
DROP TABLE IF EXISTS seller_book CASCADE;
DROP TABLE IF EXISTS order_book CASCADE;
DROP TABLE IF EXISTS orders CASCADE;
DROP TABLE IF EXISTS payment CASCADE;
DROP TABLE IF EXISTS shopping_cart CASCADE;
DROP TABLE IF EXISTS wishlist CASCADE;
DROP TABLE IF EXISTS review CASCADE;
DROP TABLE IF EXISTS book CASCADE;
DROP TABLE IF EXISTS category CASCADE;
DROP TABLE IF EXISTS request CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS seller CASCADE;
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS admin CASCADE;
DROP TABLE IF EXISTS platform_information CASCADE;
DROP TABLE IF EXISTS password_reset_tokens;

-- 4. Drop Enumerations (Types)
DROP TYPE IF EXISTS request_type;
DROP TYPE IF EXISTS payment_method_enum;

-- 
-- ENUMERATIONS
-- 

CREATE TYPE request_type AS ENUM ('UnblockAppeal', 'Seller', 'TicketIssue');
CREATE TYPE payment_method_enum AS ENUM ('MBWay', 'Card', 'Paypal');

-- 
-- TABLES
-- 

-- Platform Information
CREATE TABLE platform_information (
    name TEXT PRIMARY KEY,
    value TEXT NOT NULL
);

-- Admin
CREATE TABLE admin (
    admin_id SERIAL PRIMARY KEY,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    name TEXT NOT NULL UNIQUE
);

-- Users
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    profile_img_url TEXT,
    is_blocked BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    CHECK (
        email ~* '^(?:[a-zA-Z0-9!#$%&''*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&''*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}|(?:\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-zA-Z-]*[a-zA-Z]:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]+)\]))$'
    )
);

-- Seller (inherits from Users)
CREATE TABLE seller (
    seller_id INTEGER PRIMARY KEY REFERENCES users(user_id) ON DELETE CASCADE
);

-- Request
CREATE TABLE request (
    request_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    type request_type NOT NULL,
    comment TEXT NOT NULL,
    resolved_by TEXT REFERENCES admin(email) ON DELETE SET NULL
);

-- Category
CREATE TABLE category (
    category_name TEXT PRIMARY KEY
);

-- Book
CREATE TABLE book (
    book_id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    author TEXT NOT NULL,
    price NUMERIC(10,2) CHECK (price >= 0),
    language TEXT,
    synopsis TEXT,
    category_name TEXT REFERENCES category(category_name) ON DELETE SET NULL,
    seller_id INTEGER REFERENCES seller(seller_id) ON DELETE SET NULL,
    available_stock INTEGER DEFAULT 0 CHECK (available_stock >= 0),
    CONSTRAINT unique_book_name_author UNIQUE (name, author)
);

-- Review
CREATE TABLE review (
    review_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE,
    rating NUMERIC CHECK (rating >= 0 AND rating <= 5),
    date DATE DEFAULT CURRENT_DATE NOT NULL,
    UNIQUE (user_id, book_id)
);

-- Wishlist
CREATE TABLE wishlist (
    wishlist_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE,
    UNIQUE (user_id, book_id)
);

-- Shopping Cart
CREATE TABLE shopping_cart (
    cart_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    UNIQUE (user_id, book_id)
);

-- Payment
CREATE TABLE payment (
    payment_id SERIAL PRIMARY KEY,
    payment_method payment_method_enum NOT NULL,
    amount NUMERIC NOT NULL CHECK (amount >= 0)
);

-- Order
CREATE TABLE orders ( 
    order_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    payment_id INTEGER REFERENCES payment(payment_id) ON DELETE SET NULL,
    total_price NUMERIC(10,2) DEFAULT 0 CHECK (total_price >= 0),
    date DATE DEFAULT CURRENT_DATE NOT NULL
);

-- Notification (Single Table Inheritance for 5 types)
CREATE TABLE notification (
    notification_id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(user_id) ON DELETE CASCADE,
    type TEXT NOT NULL CHECK (type IN ('OrderStatusNotification', 'PaymentApprovedNotification', 'RequestResolvedNotification', 'WishlistedOnSaleNotification', 'CartPriceNotification')),
    message TEXT, -- nullable, used by RequestResolvedNotification
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    is_read BOOLEAN DEFAULT FALSE NOT NULL,
    -- Foreign keys for specific notification types (nullable)
    order_id INTEGER REFERENCES orders(order_id) ON DELETE CASCADE, -- for OrderStatusNotification and PaymentApprovedNotification
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE -- for WishlistedOnSaleNotification and CartPriceNotification
);

-- OrderBook (association between Order and Book)
CREATE TABLE order_book (
    order_id INTEGER REFERENCES orders(order_id) ON DELETE CASCADE, 
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE,
    unit_price_at_purchase NUMERIC(10,2) NOT NULL CHECK (unit_price_at_purchase >= 0),
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    PRIMARY KEY (order_id, book_id)
);

-- SellerBook (represents seller’s available stock)
CREATE TABLE seller_book (
    seller_id INTEGER REFERENCES seller(seller_id) ON DELETE CASCADE,
    book_id INTEGER REFERENCES book(book_id) ON DELETE CASCADE,
    available_stock INTEGER DEFAULT 0 CHECK (available_stock >= 0),
    PRIMARY KEY (seller_id, book_id)
);

-- Password Reset Tokens
CREATE TABLE password_reset_tokens (
    email TEXT PRIMARY KEY,
    token TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Performance Indices

CREATE INDEX idx_notification_user ON notification(user_id);
CREATE INDEX idx_request_user ON request(user_id);
CREATE INDEX idx_request_admin ON request(resolved_by);
CREATE INDEX idx_book_category ON book(category_name);
CREATE INDEX idx_book_seller ON book(seller_id);
CREATE INDEX idx_review_book ON review(book_id);
CREATE INDEX idx_wishlist_user ON wishlist(user_id);
CREATE INDEX idx_cart_user ON shopping_cart(user_id);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_book_book ON order_book(book_id);
CREATE INDEX idx_seller_book_seller ON seller_book(seller_id);

-- Full-search index

-- Add column to book to store computed ts_vectors.
ALTER TABLE book
ADD COLUMN tsvectors TSVECTOR;

-- Create a function to automatically update ts_vectors.
CREATE OR REPLACE FUNCTION book_search_update() RETURNS TRIGGER AS $$
BEGIN
    NEW.tsvectors = (
        setweight(to_tsvector('english', NEW.name), 'A') ||
        setweight(to_tsvector('english', NEW.author), 'B') ||
        setweight(to_tsvector('english', coalesce(NEW.synopsis, '')), 'C') ||
        setweight(to_tsvector('english', coalesce(NEW.category_name, '')), 'D')
    );
    RETURN NEW;
END
$$
LANGUAGE plpgsql;

-- Create a trigger before insert or update on book.
CREATE TRIGGER trg_book_search_update
BEFORE INSERT OR UPDATE OF name, author, synopsis, category_name ON book
FOR EACH ROW
EXECUTE FUNCTION book_search_update();

-- Drop the old index
DROP INDEX IF EXISTS book_search_idx;

-- Create a GIN index on the pre-computed tsvectors column.
CREATE INDEX book_search_idx ON book USING GIN (tsvectors);

--
-- Triggers
--

-- TRIGGER01: Atualiza o stock do livro após uma nova encomenda
CREATE OR REPLACE FUNCTION update_book_stock_after_order()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE book
    SET available_stock = available_stock - NEW.quantity
    WHERE book_id = NEW.book_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_book_stock
AFTER INSERT ON order_book
FOR EACH ROW
EXECUTE FUNCTION update_book_stock_after_order();


-- TRIGGER02: Impede que o stock de um livro se torne negativo
CREATE OR REPLACE FUNCTION prevent_negative_stock()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT available_stock FROM book WHERE book_id = NEW.book_id) - NEW.quantity < 0 THEN
        RAISE EXCEPTION 'Stock insuficiente para o livro %', NEW.book_id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_prevent_negative_stock
BEFORE INSERT ON order_book
FOR EACH ROW
EXECUTE FUNCTION prevent_negative_stock();


-- TRIGGER03: Atualiza automaticamente o total da encomenda
CREATE OR REPLACE FUNCTION update_order_total()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE orders
    SET total_price = total_price + (NEW.unit_price_at_purchase * NEW.quantity)
    WHERE order_id = NEW.order_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_order_total
AFTER INSERT ON order_book
FOR EACH ROW
EXECUTE FUNCTION update_order_total();


-- TRIGGER04: Bloqueia automaticamente um utilizador quando cria um pedido de desbloqueio
CREATE OR REPLACE FUNCTION auto_block_user()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.type = 'UnblockAppeal' THEN
        UPDATE users
        SET is_blocked = TRUE
        WHERE user_id = NEW.user_id;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_auto_block_user
AFTER INSERT ON request
FOR EACH ROW
EXECUTE FUNCTION auto_block_user();


-- TRIGGER05: Sincroniza o stock entre book e seller_book
CREATE OR REPLACE FUNCTION sync_seller_stock()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE seller_book
    SET available_stock = NEW.available_stock
    WHERE book_id = NEW.book_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_sync_seller_stock
AFTER UPDATE OF available_stock ON book
FOR EACH ROW
EXECUTE FUNCTION sync_seller_stock();
