# 📦 PIM — Product Inventory Management

A full-stack, multi-tenant inventory management web application built as a BCA final year project. PIM enables factories and businesses to manage products, suppliers, stock levels, and sales — with an AI-powered analytics module for smart restocking decisions.


---

## 🚀 Features

### 🔐 Authentication & Security
- Multi-tenant login — each business logs in via their unique **Store Name**
- **Owner-approval system** — new users can only access the system after the owner manually approves them
- Role-based access: owners can approve, reject, edit, or delete users
- Session-based authentication

### 📊 Dashboard
- Real-time summary: Total Products, Suppliers, Stocks, Categories, Workers
- Quick access to analytics

### 📦 Products
- Add, edit, delete products
- Track product name, category, supplier, quantity, price per item
- Search functionality
- Direct **Sell** action from product list

### 🏭 Suppliers
- Manage supplier details: name, location, email, phone
- Track quantity ordered, received, and remaining per supplier

### 📈 Stocks
- Real-time stock levels per product
- Total quantity and total inventory value (in ₹)
- Timestamps for last update

### 💰 Sales
- Complete sales records: product, quantity sold, total price, sold by, date
- Full sales history per store

### 🤖 AI Sales Analysis
- AI-powered restocking suggestions per product
- Estimated profit calculation
- Stock status indicators (Overstock / Low)
- Demand level classification
- **Low Stock Alerts** with urgency levels: Critical 🔴 / Moderate 🟡

### ⚙️ Settings
- Change password
- Help & Support
- Terms & Conditions
- Logout

---

## 🛠️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML, CSS, JavaScript |
| Backend | PHP |
| Database | MySQL |
| Local Server | XAMPP (Apache) |

---

## ⚙️ Setup Instructions

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) installed
- A modern browser (Chrome recommended)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/inventory-management-system.git
   ```

2. **Move to XAMPP's htdocs folder**
   ```
   C:/xampp/htdocs/IMS
   ```

3. **Import the database**
   - Start XAMPP → Start **Apache** and **MySQL**
   - Open `localhost/phpmyadmin`
   - Create a new database named `ims` (or check your config file for the DB name)
   - Click **Import** → select `database.sql` → click **Go**

4. **Configure database connection**
   - Open your `config.php` or `db.php` file
   - Verify these match your setup:
     ```php
     $host = "localhost";
     $user = "root";
     $password = "";
     $database = "your_database_name";
     ```

5. **Open the app**
   ```
   localhost/IMS/home.php
   ```

6. **Register and get started**
   - Click **Get Started** → Register with your store name
   - Log in as owner to approve new users

---

## 📁 Project Structure

```
IMS/
├── home.php          # Landing page
├── login.php         # Login page
├── register.php      # Registration page
├── dashboard.php     # Main dashboard
├── products.php      # Products management
├── suppliers.php     # Suppliers management
├── stocks.php        # Stock levels
├── sales.php         # Sales records
├── ai.php            # AI analysis & alerts
├── users.php         # User management
├── settings.php      # Settings panel
├── database.sql      # Database export
└── ...               # CSS, JS, assets
```

---

## 👤 Author

Gaurvi Chaturvedi
BCA Final Year Project — St.Xavier's College, Jaipur
LinkedIn- https://www.linkedin.com/in/gaurvi-chaturvedi-69ba94300/ | GitHub- https://github.com/gc8352992

---

## 📄 License

This project is for educational purposes.
