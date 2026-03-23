# UTEVS - Universal Transparent E-Voting System (MIME-VOTE)

UTEVS (Universal Transparent E-Voting System) is a secure and robust custom MVC PHP-based electronic voting system designed to facilitate transparent online elections. It features a dedicated voter dashboard, secure voting mechanisms with receipt generation, a comprehensive admin panel for full election management, and a public observer mode for live election statistics.

## ✨ Features

### 🗳️ Voting & Voter Experience
- **Secure Authentication**: Robust voter login and registration system.
- **Voter Dashboard**: Intuitive interface for voters to view active elections and participate.
- **Secure Vote Casting**: Trustless voting mechanism guaranteeing user choice integrity.
- **Vote Receipts**: Cryptographic receipt generation for voters to verify their cast vote without compromising anonymity.

### 🛡️ Administration
- **Admin Dashboard**: Centralized management interface for election administrators.
- **Election Management**: Full CRUD capabilities to create, schedule, edit, and manage multiple elections.
- **Position Management**: Define specific roles/positions available for contention in each election.
- **Candidate Management**: Register candidates, assign them to positions, and manage their profiles.

### 📊 Transparency & Monitoring
- **Observer Mode**: A public-facing live statistics page allowing anyone to monitor real-time vote counts and election progress securely.
- **API Endpoints**: Provision of API stats endpoints (`/api/observer/stats`) for integrating live results elsewhere.

## 🛠️ Tech Stack

- **Backend**: Pure PHP (Custom MVC Architecture using PSR-4 Autoloading)
- **Database**: MySQL / MariaDB
- **Routing**: Custom Object-Oriented PHP Router
- **Security Features**: Built-in CSRF protection, prepared statements (PDO), secure session handling, and AES-256-CBC encryption.

## 📁 Project Structure

- `app/` - The core application encompassing MVC architecture (Controllers, Core, Models, Views).
- `config/` - Application environment scaling (e.g., `app.php`) and secure database connection files.
- `database/` - Contains the database schemas, seeders, or SQL dumps required to spin up the system.
- `public/` - Publicly accessible front-end assets (CSS, JavaScript, Images).
- `index.php` - The central entry point for the custom router taking all incoming HTTPS/HTTP requests.

## 🚀 Installation & Local Setup (XAMPP/WAMP)

1. **Clone or Extract the Project**:
   Place the project folder (`MIME-VOTE`) into your local server's document root directory (e.g., `C:\xampp\htdocs\MIME-VOTE` for Windows XAMPP).

2. **Database Setup**:
   - Open phpMyAdmin (or your preferred MySQL client) and create a new database.
   - Import the provided SQL schema from the `/database` directory into your new database.
   - Open `config/database.php` and update the local database connection credentials.

3. **Application Configuration**:
   - Open `config/app.php`.
   - Verify that your `'base_url'` maps correctly to your local environment (e.g., `http://localhost/MIME-VOTE`).
   - **CRITICAL**: For production deployments, you **must** change the `'encryption_key'` and `'hash_salt'` variables to secure, unique cryptographic keys.

4. **Web Server Requirements**:
   - Ensure you are running Apache with the `mod_rewrite` module enabled. This is required for the included `.htaccess` file to successfully route all URL requests to `index.php`.

5. **Launch the Application**:
   - Start your Apache and MySQL services.
   - Navigate to `http://localhost/MIME-VOTE` in a modern web browser.

## 🔒 Security Posture

UTEVS is built with a security-first mindset:
- **Single Entry Point**: All traffic routes through `index.php`, ensuring bootloading security protocols (like CSRF token generation) never get skipped.
- **Middleware Filtering**: Administrative dashboard operations and functions are strictly protected by AdminController middleware.
- **Data Encapsulation**: Configuration keys and database connect strings are kept outside the public-facing application realm.
