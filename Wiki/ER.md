












# ER: Requirements Specification Component

> The book industry has been rapidly shifting towards digital platforms, with readers increasingly purchasing both physical and digital books online. However, many existing platforms lack personalisation, simplicity, and a community-oriented approach. Liberato emerges as an online bookshop designed to combine accessibility, modern technology, and user engagement, making it easier for readers to find, purchase, and enjoy books from anywhere.

## A1: Liberato

> Goals and description

Provide an intuitive and secure platform for purchasing books. Support authors and publishers by offering visibility and integration with the platform. Liberato is an online bookstore that offers a wide catalogue of books. The system will provide features such as advanced search, personalised recommendations, user reviews, and a simple checkout process. Beyond being a sales platform, Liberato aims to build a space where readers, students, and authors can connect through literature.

> Main Features

- Online catalogue with advanced search and filtering.
- User accounts with wishlists and favourites.
- Reviews and ratings for books.
- Promotional campaigns and discounts.

> User profiles

- Reader: searches for books to buy and expects a simple, fast experience.
- Seller: can shop as a user but has the permission to put their own books for sale.
- Admin: manages catalogue, users, orders, and promotional campaigns.

---

## A2: Actors and User stories

> The goal of this artifact is to identify and describe the different types of users (actors) who will interact with the system, along with the user stories that capture their needs, motivations, and interactions.   
By defining actors and their corresponding user stories, this artifact provides a clear understanding of system functionalities from a user-centered perspective. It helps ensure that the system design aligns with real-world usage scenarios, guiding both functional requirements and development priorities.    

### 1. Actors

> ![111111](uploads/d1b68b68f1e3970c350aeb4d84f951ed/111111.png)

 

| Actor | Relationship | Description |
|-------|--------------|-------------|
| User | Parent / General actor | Represents any person interacting with the system. |
| Unauthenticated User | Inherits from User | Represents a visitor who can browse products, view details, and read general information but cannot make purchases. |
| Authenticated User | Inherits from User | Represents a registered user who can log in, manage their profile, make purchases, and access personalised features. |
| Administrator | Independent of User hierarchy | Represents a separate actor responsible for managing users, products and categories, and maintains the platform. |
| Seller | Inherits from Authenticated User | Represents a type of authenticated user with additional privileges to add, update, and manage their products and stock. |


### 2. User Stories

> User stories organised by actor.    
> For each actor, a table containing a line for each user story, and for each user story: an identifier, a name, a priority, and a description.

### 2.1 User (unauthenticated)

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US01 | Registration | High | Ricardo | As a visitor, I want to register a new account, so that I can start using the system. |
| US02 | Login | High | João | As a user, I want to log in to my account, so that I can securely access and end my sessions. |

### 2.2 User

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US03 | View & Search Products | High | Iara | As a user, I want to view and search products, so that I can browse the store. |
| US04 | Exact Match Search | High | Ricardo | As a user, I want to search with exact matches, so that I can quickly find specific results. |
| US05 | Full-text Search | High | Dinis | As a user, I want to use full-text search, so that I can find results by keywords. |
| US06 | Add item to Cart | High | Ricardo Fernandes | As a user, I want to add items to my cart, so that I can buy my purchases. |
| US07 | Manage Cart | High | Ricardo Fernandes | As a user, I want to update or remove items in my cart, so that I can manage my purchases. |
| US08 | View Product Details | High | Ricardo | As a user, I want to view detailed information about a product, so that I can make informed purchase decisions. |
| US09 | View Product Reviews | Medium | Iara | As a user, I want to view product reviews, so that I can evaluate others’ experiences before buying. |
| US10 | Search over Multiple Attributes | Medium | Iara | As a user, I want to search by multiple attributes, so that I can refine results. |
| US11 | Search Filters | Medium | João | As a user, I want to apply filters to my search, so that I can narrow down results easily. |
| US12 | Ordering of Results | Medium | Ricardo | As a user, I want to order search results, so that I can view items by relevance, date, or other criteria. |
| US13 | Browse Product Categories | Medium | João | As a user, I want to browse product categories, so that I can easily find types of products I’m interested in. |
| US14 | Faceted Search | Innovation | Dinis | As a user, I want to use faceted search, so that I can browse results with categorised attributes. |
| US15 | Placeholders in Form Inputs | Medium | Iara | As a user, I want to see placeholders in form inputs, so that I understand what information to enter. |
| US16 | About Us | Medium | Dinis | As a visitor, I want to see an About Us section, so that I can learn about the company. |
| US17 | Main Features | Medium | Iara | As a visitor, I want to see the system’s main features, so that I can understand what it offers. |
| US18 | Contacts | Medium | João | As a visitor, I want to view contact information, so that I can reach the company if needed. |   


### 2.3 Authenticated user

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US19 | View Profile | High | João | As an authenticated user, I want to view my profile information, so that I can check my personal data. |
| US20 | Edit Profile | High | Ricardo | As an authenticated user, I want to edit my profile, so that I can update my information when needed. |
| US21 | Checkout | High | João Santos | As an authenticated user, I want to proceed to checkout, so that I can complete my purchase securely and efficiently. |
| US22 | Review Purchased Product | High | Dinis | As an authenticated user, I want to review a product I purchased, so that I can share my experience with other users. |
| US23 | Payment Approved | High | Iara | As an authenticated user, I want to receive a notification when my payment is approved, so that I know my order is being processed. |
| US24 | Recover Password | Medium | Dinis | As an authenticated user, I want to recover my password, so that I can regain access if I forget it. |
| US25 | Delete Account | Medium | Iara | As an authenticated user, I want to delete my account, so that I can permanently remove my data from the system. |
| US26 | Upload/Update Profile Picture | Medium | Dinis | As an authenticated user, I want to upload or update my profile picture, so that my account is personalised. |
| US27 | View Personal Notifications | Medium | Iara | As an authenticated user, I want to view my personal notifications, so that I stay informed about updates. |
| US28 | Contextual Error Messages | Medium | João | As an authenticated user, I want to receive contextual error messages, so that I know how to fix issues while using the system. |
| US29 | Contextual Help | Medium | Ricardo | As an authenticated user, I want to access contextual help, so that I can get guidance while using the system. |
| US30 | View Notification List | Medium | Ricardo | As a user, I want to view my notification list, so that I can keep track of system updates. |
| US31 | Mark Notifications as Read | Medium | Dinis | As a user, I want to mark notifications as read, so that I can manage which alerts I’ve already seen. |
| US32 | Log Out | Medium | Dinis Pinto | As an authenticated user, I want to log out of my account, so that I can ensure my personal data and session remain secure when I leave the system. |
| US33 | View Product Details & Reviews | Medium | João Santos | As an authenticated user, I want to view product details and reviews, so that I can decide what to buy. |
| US34 | Manage Purchases | Medium | Dinis Pinto | As an authenticated user, I want to view my purchase history, so that I can track past orders. |
| US35 | Manage Wishlist | Medium | Iara Brás | As an authenticated user, I want to manage a wishlist, so that I can save products for later. |
| US36 | Write Reviews | Medium | João Santos | As an authenticated user, I want to write and report reviews, so that I can share feedback and keep reviews fair. |
| US37 | Edit Review | Medium | Ricardo Fernandes | As an authenticated user, I want to edit my review, so that I can update my feedback based on new experiences or opinions. |
| US38 | Remove Review | Medium | Iara Brás | As an authenticated user, I want to remove my review, so that I can delete feedback I no longer wish to share. |
| US39 | Track Order | Medium | Dinis | As an authenticated user, I want to track my order status, so that I can know when my purchase will arrive. |
| US40 | Cancel Order | Medium | João Santos | As an authenticated user, I want to cancel my order, so that I can stop the purchase if I change my mind or find an issue. |
| US41 | Change in Order Processing Stage | Medium | Dinis | As an authenticated user, I want to be notified when the processing stage of my order changes, so that I can track its progress. |
| US42 | Product in Wishlist Available | Medium | Ricardo | As an authenticated user, I want to receive a notification when a product in my wishlist becomes available, so that I can purchase it quickly. |
| US43 | Product on Cart Price Change | Medium | João | As an authenticated user, I want to be notified when a product in my cart changes price, so that I can decide whether to complete the purchase. |
| US44 | Appeal for Unblock | Innovation | João | As a blocked user, I want to send an unblock appeal, so that I can request access to my account again. |



### 2.4 Seller

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US45 | Manage their stock | High | João | As a seller, I want to add, update and manage my products and stock, so that the stock remains accurate and organised. |
| US46 | Manage their sales stats | Medium | Dinis | As a seller, I want to view my sales statistics and manage discounts on my products, so that I can optimise my exposure. |


### 2.4 Admin

| Identifier | Name | Priority | Responsible | Description |
|------------|------|----------|-------------|-------------|
| US47 | Support Admin Accounts | High | Iara | As an admin, I want to have a dedicated admin account, so that I can manage system functionalities securely. |
| US48 | Administer User Accounts | High | João | As an admin, I want to search, view, edit, and create user accounts, so that I can manage the platform’s users. |
| US49 | Add Product | High | João | As an admin, I want to add new products to the system, so that I can expand the catalog available to users. |
| US50 | Manage Products Information | High | Ricardo | As an admin, I want to edit and update product information, so that all product details remain accurate and up to date. |
| US51 | Manage Order Status | High | João | As an admin, I want to update and manage the status of orders, so that I can oversee order processing and fulfilment. |
| US52 | Block/Unblock User Accounts | Medium | Ricardo | As an admin, I want to block or unblock accounts, so that I can enforce rules and restore access if needed. |
| US53 | Delete User Account (Admin) | Medium | Dinis | As an admin, I want to delete user accounts, so that I can remove inactive or problematic accounts. |
| US54 | Manage Products Stock | Medium | Dinis | As an admin, I want to manage product stock levels, so that I can ensure product availability and prevent overselling. |
| US55 | Manage Product Categories | Medium | Iara | As an admin, I want to create, edit, and delete product categories, so that I can organise the catalog effectively. |
| US56 | View Users’ Purchase History | Medium | Ricardo | As an admin, I want to view users’ purchase histories, so that I can monitor activity and assist with support or audits. |


### 3. Supplementary Requirements

> Section including business rules, technical requirements, and restrictions.\
> For each subsection, a table containing identifiers, names, and descriptions for each requirement.

#### 3.1. Business rules

| Identifier | Name | Priority | Category | Description |
|------------|------|----------|-------------|-------------|  
| BR.01 | Administrator Role Separation | High | Access/Security | Administrator accounts are independent of user accounts and cannot buy products (i.e., they have no purchasing capability). |   
| BR.02 | Product Price Floor | High | Finance/Product | The price of any book on the platform must be greater than 0 (\>0). |   
| BR.03 | Inventory Pre-Requisite | High | Sales/Inventory | Buyers can only complete a purchase for books that are currently in stock (Stock ≥ 1). |   
| BR.04 | Product Data Completeness | High | Data Integrity | Sellers must input the book name and its author as mandatory fields when listing a book for sale. |   
| BR.05 | Price Lock During Payment | High | Finance/Transaction | Books that are part of an order currently in the payment processing stage cannot have their price changed.|    
| BR.06 | Product Purchase Availability | High | Products/Store Management | A product can only be purchased after its publication date. |  
| BR.07 | Order Quantity Limit | Medium | Sales/Inventory | Buyers cannot purchase over X units of the same book in a single order (where X is the available stock for the book). |   
| BR.08 | Data Anonymisation | Medium | Privacy/Data Retention | Upon account deletion (FR.014), shared user data (e.g., comments, reviews, likes) must be retained but made permanently anonymous.|  


#### 3.2. Technical requirements


| Identifier | Name | Priority | Category | Description |
|-------------|------|-----------|------------|--------------|
| TR.01 | Performance (Response Time) | High | Usability/Speed | The system must have response times shorter than 2 seconds for all critical user actions to ensure sustained user attention and engagement. |
| TR.02 | Robustness (Error Handling) | High | Reliability | The system must be prepared to handle runtime errors gracefully (e.g., database connection failure) and continue operating or fail safely without crashing. |
| TR.03 | Scalability (Growth Management) | High | Maintainability/Growth | The system must be designed to effectively deal with a substantial growth in the number of users and their actions (e.g., horizontal scaling capability). |
| TR.04 | Accessibility (WCAG Compliance) | High | Usability/Legal | The system must ensure that everyone can access and interact with the pages, regardless of disabilities or the specific Web browser they use. |
| TR.05 | Security (Password Storage) | High | Security/Data Integrity | The system must store all user passwords securely, using modern, industry-standard hashing and salting algorithms (e.g., Argon2 or bcrypt). |


#### 3.3. Restrictions

| Identifier | Name | Category | Description | Justification (Implied) |   
|-------------|------|-----------|--------------|--------------------------|   
| C.01 | Project Deadline | Time/Schedule | The project must be concluded before January 12th (delivery of final working system). | Defines the hard limit for all development and testing phases. |
| C.02 | Version Control System | Tooling/DevOps | GitLab must be used for collaborative software development, documentation, and versioning. | Enforces the entire CI/CD pipeline and code repository location. |
| C.03 | Database System | Technology Stack | PostgreSQL must be used as the primary relational database system. | Restricts the choice of RDBMS, excluding MySQL, MariaDB, etc. |
| C.04 | Backend Language | Technology Stack | PHP must be used as the programming language on the server side. | Enforces the backend language choice. |
| C.05 | Backend Framework | Technology Stack | Laravel must be used as the server web framework. | Enforces the use of a specific PHP framework, limiting architectural freedom. |
| C.06 | Virtualisation Environment | Tooling/Deployment | Docker must be used as the virtualisation environment for deployment. | Enforces containerization for deployment and environment consistency. |
| C.07 | Web Server | Infrastructure | NGINX must be used as the web server (to handle HTTP requests). | Restricts the use of other web servers like Apache HTTP Server. |
| C.08 | Frontend Languages | Technology Stack | HTML, CSS, and JavaScript must be used as client-side languages. | Defines the foundational languages for the browser, common for all web projects. |


---

## A3: Information Architecture

> The goal of this artifact is to define the overall structure and organisation of the system’s content and navigation. It provides a clear overview of how users will access and interact with the main sections of the platform.

### 1. Sitemap

> ![image.png](uploads/b143122aa7778a1bca584d7147bf9c3e/image.png){width="797" height="694"}

### 2. Wireframes

> Wireframes for some of the principal pages of our website, the homepage, the product page, the shopping cart and the profile page.

#### UI01: HomePage

> ![2Capture](uploads/12ea7c78f8a21f2d26d3739ae6de090c/2Capture.PNG)

1. Logo of Liberato.
2. Filter menu, filter items by genre, author, price, language, collection or seller type;
3. Search bar to look through the stock;
4. Browse the most recent available products here;
5. Button to access your notifications, check your latest updates and alerts;
6. Button to access your wishlist, view and manage your saved favorites;
7. Button to access your shopping cart, see the items you plan to buy;
8. Button to access your profile, access and edit your personal account.

#### UI02: Cart

> ![Capture3](uploads/42b9776aa6918726cc7587bd9dfd42f7/Capture3.PNG)

 \
**1.** Book information, displays the book cover, title, and author to identify each item in the cart;\
**2.** Quantity selector, alInnovations you to increase or decrease the number of copies you wish to purchase.\
**3.** Unit price, shows the price for a single copy of the selected book.\
**4.** Remove button, deletes the item from your cart.\
**5.** Cart total, displays the total price of all items in your shopping cart.\
**6.** Checkout button, proceeds to the checkout process to complete the purchase.

#### UI03: Book Page

> ![Capture5](uploads/8555a09a927eb99475a08a20d791d5f9/Capture5.PNG)

 \
**1.** Product image, displays the book’s cover;\
**2.** Main product information, includes book title, author, average rating, and price;\
**3.** Action buttons, add the book to the cart or to your wishlist;\
**4.** Book details section, provides access to synopsis, product specifications (e.g. dimensions, ISBN, language, genre), and customer reviews.

#### UI04: Profile

> ![Capturem](uploads/8d5b4dade93cb34c1c42070d64c419ab/Capturem.PNG)

 \
**1.** Displays basic details of the authenticated user, such as name, email, and profile picture.\
**2.** Settings button, alInnovations access to account settings, where the user can view personal data, update passwords, and edit other information.\
**3.** Previous orders summary, provides an overview of previous orders, including order number, purchase date, product details, and total price.

---

## Revision history

Changes made to the first submission:

1. Error correction in user stories;
2. User Stories and requirements organised by priority;
3. Actor system removed, unnecessary;
4. Correction of A1 user profiles

---

GROUP25134, 25/09/2025

* Dinis Afonso Nunes Pinto, up202306480@up.pt (editor)
* Iara Catarina Sampaio dos Santos Brás, up202208825@up.pt
* João Paulo Silva Santos, up202006525@up.pt
* Ricardo Alexandre Ribeiro Fernandes, up202304126@up.pt