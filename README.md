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

### 1. Database Initialization
This project includes an automated script to set up the database and tables for you.

1. Start your Apache and MySQL modules (e.g., in XAMPP Control Panel).
2. Open your browser and navigate to:
   ```
   http://localhost/projectPHP/install.php
   ```
3. You should see a message saying "Database and Tables created successfully."

### 2. Configuration (Optional)
If your MySQL username or password is not the default (root/empty), update the following files:
- `connection.php`
- `install.php`

### 3. Usage
- **Home Page:** Access the main site at `index.html`.
- **Authentication:** Go to the user icon to Sign Up or Login.
- **Dashboard:** Once logged in, you can view your dishes, publish new ones, or edit/delete existing entries.

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
