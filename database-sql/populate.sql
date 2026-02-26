-- POPULATION

-- Platform Information
INSERT INTO platform_information (name, value) VALUES
('site_name', 'Liberato Books'),
('support_email', 'support@liberato.com'),
('support_phone', '911911911'),
('about_us_title', 'Sobre Liberato Books'),
('about_us', 'Designed to redefine the reading experience, Liberato brings together technology, accessibility, and community to create a modern space for book lovers.'),
('about_us_long', 'Designed to redefine the reading experience, Liberato is more than just a platform—it’s a place where stories, technology, and people come together. We believe reading should be accessible, engaging, and deeply personal, no matter who you are or where you are in your journey as a reader.
By thoughtfully integrating modern technology with inclusive design, Liberato removes barriers and opens doors for readers of all abilities. Our tools are built to adapt to individual needs, making it easier to discover, enjoy, and connect with books in ways that feel natural and empowering. Whether you’re a lifelong bibliophile or rediscovering the joy of reading, Liberato meets you where you are.
At the heart of Liberato is community. We’re creating a shared space where readers can explore new ideas, exchange perspectives, and celebrate the power of stories together. Through collaboration, conversation, and a shared love of books, Liberato aims to transform reading from a solitary activity into a connected, enriching experience—one that inspires curiosity, understanding, and lifelong learning.');

-- Admins
INSERT INTO admin (email, password_hash, name) VALUES
('admin1@liberato.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Alice Manager'),
('admin2@liberato.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Bob Supervisor');

-- Users
INSERT INTO users (username, email, password_hash, profile_img_url) VALUES
('john_doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('mary_smith', 'mary@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('carlos', 'carlos@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('lucia', 'lucia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('sofia', 'sofia@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('miguel', 'miguel@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('andrea', 'andrea@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('ricardo', 'ricardo@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('paula', 'paula@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL),
('pedro', 'pedro@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL);

-- Sellers (inherits from users)
INSERT INTO seller (seller_id) VALUES
(3),  -- carlos
(6),  -- miguel
(9);  -- paula

-- Categories
INSERT INTO category (category_name) VALUES
('Fiction'),
('Science'),
('Children'),
('History'),
('Romance');

-- Books
INSERT INTO book (name, author, price, language, synopsis, category_name, seller_id, available_stock)
VALUES
('The Silent Sea', 'James Rollins', 15.99, 'English', 'A thrilling maritime adventure.', 'Fiction', 3, 20),
('Quantum Realm', 'Lisa Randall', 22.50, 'English', 'Exploring the mysteries of quantum physics.', 'Science', 3, 15),
('The Little Prince', 'Antoine de Saint-Exupéry', 10.00, 'French', 'A timeless children’s classic.', 'Children', 6, 30),
('Love and Letters', 'Jane Austen', 12.99, 'English', 'A tale of love and misunderstanding.', 'Romance', 9, 25),
('World at War', 'Max Hastings', 18.75, 'English', 'An in-depth look into WWII.', 'History', 9, 10),
('Star Quest', 'Arthur C. Clarke', 16.50, 'English', 'A sci-fi space odyssey.', 'Fiction', 3, 12),
('Cosmic Wonders', 'Neil deGrasse Tyson', 20.00, 'English', 'Astrophysics made simple.', 'Science', 6, 18),
('My Secret Garden', 'L.M. Montgomery', 9.99, 'English', 'A story of hope and friendship.', 'Children', 9, 22),
('Galactic Tales', 'Isaac Asimov', 14.25, 'English', 'Short stories from the future.', 'Fiction', 6, 17),
('Timeless Hearts', 'Nicholas Sparks', 11.49, 'English', 'A romantic journey through time.', 'Romance', 9, 14);

-- SellerBook
INSERT INTO seller_book (seller_id, book_id, available_stock) VALUES
(3, 1, 20),
(3, 2, 15),
(3, 6, 12),
(6, 3, 30),
(6, 7, 18),
(6, 9, 17),
(9, 4, 25),
(9, 5, 10),
(9, 8, 22),
(9, 10, 14);

-- Requests
INSERT INTO request (user_id, type, comment, resolved_by) VALUES
(1, 'UnblockAppeal', 'I was blocked unfairly.', 'admin1@liberato.com'),
(2, 'Seller', 'I would like to become a seller.', 'admin2@liberato.com'),
(4, 'TicketIssue', 'My order has not arrived yet.', 'admin1@liberato.com');

-- Payments
INSERT INTO payment (payment_method, amount) VALUES
('Card', 38.48),
('Paypal', 22.99),
('MBWay', 31.00);

-- Orders
INSERT INTO orders (user_id, payment_id, total_price, date) VALUES
(1, 1, 38.48, '2025-11-01'),
(2, 2, 22.99, '2025-11-02'),
(4, 3, 31.00, '2025-11-03');

-- OrderBook
INSERT INTO order_book (order_id, book_id, unit_price_at_purchase, quantity) VALUES
(1, 1, 15.99, 1),
(1, 4, 12.99, 1),
(2, 3, 10.00, 2),
(3, 6, 16.50, 1),
(3, 5, 14.50, 1);

-- Reviews
INSERT INTO review (user_id, book_id, rating) VALUES
(1, 1, 5),
(2, 3, 4),
(4, 6, 5),
(5, 2, 3),
(7, 4, 5),
(8, 10, 4);

-- Wishlist
INSERT INTO wishlist (user_id, book_id) VALUES
(1, 2),
(2, 5),
(5, 1),
(6, 9),
(8, 10),
(10, 3);

-- Shopping Cart
INSERT INTO shopping_cart (user_id, book_id, quantity) VALUES
(1, 9, 1),
(2, 6, 2),
(5, 7, 1),
(6, 4, 3),
(8, 8, 2),
(10, 1, 1);

-- Notifications
-- Payment approved + order status updates (order-linked)
INSERT INTO notification (user_id, type, message, is_read, order_id) VALUES
(1, 'PaymentApprovedNotification', 'Payment approved and order processed.', FALSE, 1),
(2, 'OrderStatusNotification', 'Order status updated.', FALSE, 2);

-- Cart price change (book-linked; users with items in shopping_cart)
INSERT INTO notification (user_id, type, message, is_read, book_id) VALUES
(1, 'CartPriceNotification', 'Price of a cart item has changed.', FALSE, 9),  -- user 1 has book 9 in cart
(6, 'CartPriceNotification', 'Price of a cart item has changed.', FALSE, 4);  -- user 6 has book 4 in cart

-- Wishlist item on sale (book-linked; users with items in wishlist)
INSERT INTO notification (user_id, type, message, is_read, book_id) VALUES
(1, 'WishlistedOnSaleNotification', 'A wishlist book is now on sale.', FALSE, 2),   -- user 1 has book 2 in wishlist
(8, 'WishlistedOnSaleNotification', 'A wishlist book is now on sale.', FALSE, 10);  -- user 8 has book 10 in wishlist

-- Request notifications (message-only, matches old seed intent)
INSERT INTO notification (user_id, type, message, is_read) VALUES
(1, 'RequestResolvedNotification', 'Your account has been reviewed.', FALSE),
(2, 'RequestResolvedNotification', 'Your seller request was approved.', TRUE),
(4, 'RequestResolvedNotification', 'Support is reviewing your issue.', FALSE),
(3, 'RequestResolvedNotification', 'Your new book listing was accepted.', TRUE);

COMMIT;
