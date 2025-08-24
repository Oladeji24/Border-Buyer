# Border Buyers - Database & Models Documentation

## Database Configuration

This project uses Supabase PostgreSQL as the database. The connection details are configured in the `.env` file:

```
DB_CONNECTION=pgsql
DB_HOST=aws-0-us-west-1.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.yourusername
DB_PASSWORD=yourpassword
```

## Models

### User Model

The `User` model represents all users in the system with different roles:
- `buyer`: Users who want to purchase goods from other countries
- `seller`: Users who want to sell goods to other countries
- `agent`: Users who facilitate transactions between buyers and sellers
- `admin`: System administrators

**Fields:**
- `name`: User's full name
- `email`: User's email address (unique)
- `password`: Hashed password
- `role`: User's role (buyer, seller, agent, admin)
- `country`: User's country
- `phone`: User's phone number
- `profile_image`: URL to user's profile image

**Relationships:**
- `agentProfile`: One-to-one relationship with AgentProfile
- `serviceRequests`: One-to-many relationship with ServiceRequest (as buyer)
- `marketplaceListings`: One-to-many relationship with MarketplaceListing (as seller)
- `transactions`: One-to-many relationship with Transaction
- `reviews`: One-to-many relationship with Review (as reviewer)
- `receivedReviews`: One-to-many relationship with Review (as reviewee)

### AgentProfile Model

The `AgentProfile` model contains additional information specific to agents.

**Fields:**
- `user_id`: Foreign key to User
- `bio`: Agent's biography
- `specialization`: Agent's area of specialization
- `experience_years`: Number of years of experience
- `languages`: JSON array of languages spoken
- `verification_status`: Verification status (pending, verified, rejected)
- `rating`: Average rating (1-5)
- `completed_transactions`: Number of completed transactions

**Relationships:**
- `user`: BelongsTo relationship with User
- `serviceRequests`: One-to-many relationship with ServiceRequest
- `marketplaceListings`: One-to-many relationship with MarketplaceListing
- `reviews`: One-to-many relationship with Review

### ServiceRequest Model

The `ServiceRequest` model represents requests made by buyers to find agents for sourcing products.

**Fields:**
- `buyer_id`: Foreign key to User (buyer)
- `agent_id`: Foreign key to User (agent)
- `title`: Request title
- `description`: Detailed description of the request
- `category`: Product category
- `budget`: Maximum budget for the service
- `country_from`: Buyer's country
- `country_to`: Country to source products from
- `status`: Request status (open, assigned, in_progress, completed)
- `deadline`: Deadline for completion

**Relationships:**
- `buyer`: BelongsTo relationship with User
- `agent`: BelongsTo relationship with User
- `transactions`: One-to-many relationship with Transaction
- `reviews`: One-to-many relationship with Review

### MarketplaceListing Model

The `MarketplaceListing` model represents products or services listed by sellers.

**Fields:**
- `seller_id`: Foreign key to User (seller)
- `agent_id`: Foreign key to User (agent)
- `title`: Listing title
- `description`: Detailed description of the listing
- `category`: Product category
- `price`: Price of the product/service
- `country_from`: Seller's country
- `country_to`: Target country for the product
- `status`: Listing status (active, pending, sold)
- `is_featured`: Whether the listing is featured
- `expires_at`: Expiration date of the listing

**Relationships:**
- `seller`: BelongsTo relationship with User
- `agent`: BelongsTo relationship with User
- `transactions`: One-to-many relationship with Transaction
- `reviews`: One-to-many relationship with Review

### Transaction Model

The `Transaction` model represents financial transactions in the system.

**Fields:**
- `user_id`: Foreign key to User
- `service_request_id`: Foreign key to ServiceRequest (nullable)
- `marketplace_listing_id`: Foreign key to MarketplaceListing (nullable)
- `amount`: Transaction amount
- `currency`: Currency code
- `status`: Transaction status (pending, completed, failed)
- `payment_method`: Payment method used
- `transaction_reference`: Unique transaction reference
- `completed_at`: Timestamp of completion

**Relationships:**
- `user`: BelongsTo relationship with User
- `serviceRequest`: BelongsTo relationship with ServiceRequest
- `marketplaceListing`: BelongsTo relationship with MarketplaceListing
- `reviews`: One-to-many relationship with Review

### Review Model

The `Review` model represents reviews left by users for other users or agents.

**Fields:**
- `reviewer_id`: Foreign key to User (reviewer)
- `reviewee_id`: Foreign key to User (reviewee)
- `agent_id`: Foreign key to User (agent)
- `service_request_id`: Foreign key to ServiceRequest (nullable)
- `marketplace_listing_id`: Foreign key to MarketplaceListing (nullable)
- `transaction_id`: Foreign key to Transaction (nullable)
- `rating`: Rating (1-5)
- `comment`: Review comment
- `is_public`: Whether the review is public

**Relationships:**
- `reviewer`: BelongsTo relationship with User
- `reviewee`: BelongsTo relationship with User
- `agent`: BelongsTo relationship with User
- `serviceRequest`: BelongsTo relationship with ServiceRequest
- `marketplaceListing`: BelongsTo relationship with MarketplaceListing
- `transaction`: BelongsTo relationship with Transaction

## Database Migrations

The following migrations have been created:

1. `2023_08_01_000000_add_fields_to_users_table.php` - Adds role, country, phone, and profile_image fields to the users table
2. `2023_08_01_000001_create_agent_profiles_table.php` - Creates the agent_profiles table
3. `2023_08_01_000002_create_service_requests_table.php` - Creates the service_requests table
4. `2023_08_01_000003_create_marketplace_listings_table.php` - Creates the marketplace_listings table
5. `2023_08_01_000004_create_transactions_table.php` - Creates the transactions table
6. `2023_08_01_000005_create_reviews_table.php` - Creates the reviews table

## Database Seeders

The following seeders have been created to populate the database with sample data:

1. `UserSeeder.php` - Creates admin, agent, buyer, and seller users with sample data
2. `ServiceRequestSeeder.php` - Creates sample service requests
3. `MarketplaceListingSeeder.php` - Creates sample marketplace listings
4. `TransactionSeeder.php` - Creates sample transactions
5. `ReviewSeeder.php` - Creates sample reviews

## Running Migrations and Seeders

To run the migrations and seed the database with sample data, use the following commands:

```bash
php artisan migrate
php artisan db:seed
```

Or to run migrations and seeders in a single command:

```bash
php artisan migrate:fresh --seed
```

## Sample Data

The seeders create the following sample data:

### Users
- 1 admin user
- 2 Nigerian agents (Tunde Adekunle and Ngozi Okafor)
- 2 Chinese agents (Wei Zhang and Li Mei)
- 5 sample buyers
- 5 sample sellers

### Service Requests
- 15 sample service requests with various categories and statuses

### Marketplace Listings
- 20 sample marketplace listings with various categories and statuses

### Transactions
- Sample transactions for service requests and marketplace listings

### Reviews
- Sample reviews for completed transactions
