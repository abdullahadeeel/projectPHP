# Cuisine - Food Management System

A PHP-based web application for managing food dishes with user authentication and a secure dashboard.

## 🚀 Features
- **User Authentication:** Secure Sign Up and Login system with password hashing (`password_hash`).
- **Secure Dashboard:** Manage your dishes (CRUD: Create, Read, Update, Delete).
- **Security First:** 
  - All database queries use **MySQLi Prepared Statements** to prevent SQL Injection.
  - XSS protection using `htmlspecialchars`.
  - Secure session management via HttpOnly cookies.
- **Automated Setup:** Built-in installer script for database and table creation.

---

## 🛠️ Prerequisites
Before running this project, ensure you have the following installed:
- **XAMPP**, **WAMP**, or any local server environment with **PHP (7.4+)** and **MySQL**.

---

## 📦 Installation & Setup

### 1. Folder Placement
Move the entire `projectPHP` folder into your local server's root directory:
- **XAMPP:** `C:\xampp\htdocs\`
- **WAMP:** `C:\wamp64\www\`

### 2. Start Services
1. Open the **XAMPP Control Panel**.
2. Click **Start** for both **Apache** and **MySQL**.

### 3. Database Configuration
You can set up the database in two simple steps:

#### Step A: Create the Database Manually
1. Open your browser and go to `http://localhost/phpmyadmin/`.
2. Click on **New** in the left sidebar.
3. Enter a name for your database (e.g., `cuisine_db`) and click **Create**.

#### Step B: Update the Project Variables
Open the following files in your code editor and ensure the `$db` (or `$dbname`) variable matches the name you just created:
- **`connection.php`**: ` $db = 'your_database_name'; `
- **`install.php`**: ` $dbname = 'your_database_name'; `

### 4. Run the Installer Script
Once the database is created and the variables are set, run the following URL in your browser to automatically create the tables:
```
http://localhost/projectPHP/install.php
```
The script will create the following tables:
- `users`: User accounts.
- `dish`: Food items/dishes managed in the dashboard.
- `cart`: Shopping cart items.
- `orders`: Order history.
- `order_items`: Specific items within each order.

### Checkout Workflow
1. Users add items to their `cart` by posting to `actions/add_to_cart.php` (with `dishId`).
2. Running `actions/checkout.php` will:
   - Calculate the total price based on cart items.
   - Insert a record into `orders`.
   - Move all items from `cart` to `order_items`.
   - Empty the `cart`.

---

## 🚀 Usage
- **Home Page:** Access the main site at `http://localhost/projectPHP/index.html`.
- **Authentication:** Click the user icon on the home page to Sign Up or Login.
- **Dashboard:** Once logged in, you can manage your dishes.

---

## 📂 Project Structure
- `/actions`: PHP scripts for Login, Signup, and Logout.
- `/assests`: Static images and icons.
- `/dashboard`: Dashboard logic and styling.
- `/pages`: Frontend HTML and CSS files.
- `connection.php`: Centralized database connection.
- `install.php`: Automated database setup script.
- `index.html`: Project landing page.

---

## 🔒 Security Notes
- This project uses `password_hash()` for storing passwords.
- Database interactions are handled via prepared statements.
- Session tokens are stored in secure cookies.

---

## 📝 License
This project is open-source. Feel free to modify and use it for your own learning or development!
