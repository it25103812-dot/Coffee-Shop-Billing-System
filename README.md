# ☕ Brew & Bean — Coffee Shop Billing System

A responsive, web-based Point of Sale (POS) and billing application built with **PHP, MySQL, HTML, CSS and JavaScript**. Designed as a portfolio project for a small coffee shop.

## Features

- Secure admin login with PHP password hashing and session regeneration
- Initial administrator setup
- Dashboard with today's revenue, order count and active product count
- Product management: add, edit, activate and deactivate menu items
- POS billing screen with product search and interactive shopping cart
- Automatic subtotal, total, payment and change calculations
- Server-side price validation (prices are never trusted from the browser)
- Transaction-safe checkout using database transactions
- Unique invoice numbers and printable customer receipts
- Sales history with date filters
- Prepared SQL statements and CSRF token validation
- Responsive layout for desktop, tablet and mobile

## Technology stack

- PHP 8.0+
- MySQL 5.7+ / MySQL 8+
- HTML5, CSS3 and JavaScript
- PDO for database access
- XAMPP for local development

## Requirements

- XAMPP (Apache, PHP and MySQL) or another PHP/MySQL environment
- PHP 8.0 or later with PDO MySQL enabled
- Web browser
- Git (optional, for command-line upload)

## Installation with XAMPP

1. Install and open XAMPP.
2. Start **Apache** and **MySQL**.
3. Copy this project folder into the XAMPP `htdocs` directory. Keep the folder name `Coffee-Shop-Billing-System`.
4. Open phpMyAdmin at `http://localhost/phpmyadmin`.
5. Import `database/coffee_shop_db.sql`. The script creates the `coffee_shop_db` database and sample menu items.
6. Open `config/db.php`. The default XAMPP settings are:
   - Host: `localhost`
   - Database: `coffee_shop_db`
   - Username: `root`
   - Password: empty
   Update these values if your MySQL setup is different.
7. Visit `http://localhost/Coffee-Shop-Billing-System/setup_admin.php`.
8. Create the first administrator account using a valid email and a password of at least 8 characters.
9. **Delete `setup_admin.php` after creating the admin account.**
10. Visit `http://localhost/Coffee-Shop-Billing-System/login.php` and sign in.

## How to use

1. Sign in with the administrator account.
2. Open **Products** to add or edit menu items and prices.
3. Open **New Bill**, click products to add them to the order, and adjust quantities.
4. Choose a payment method and enter the amount received.
5. Select **Complete sale**. The system saves the order and payment, then opens its receipt.
6. Print the receipt or review the transaction in **Sales History**.
7. Check the dashboard for today's sales summary.

## Database structure

| Table | Purpose |
|---|---|
| `users` | Admin and cashier accounts |
| `products` | Coffee, drinks, food and menu prices |
| `orders` | Invoice-level sale and payment summary |
| `order_items` | Individual products included in an order |
| `payments` | Payment method, amount and status |

## Project structure

```text
Coffee-Shop-Billing-System/
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── config/db.php
├── database/coffee_shop_db.sql
├── includes/
│   ├── auth.php
│   ├── header.php
│   └── footer.php
├── billing.php
├── checkout.php
├── dashboard.php
├── index.php
├── login.php
├── logout.php
├── products.php
├── receipt.php
├── sales.php
├── setup_admin.php
├── .gitignore
└── README.md
```

## GitHub upload

### Option A — GitHub website

1. Sign in to GitHub and click **New repository**.
2. Name it `Coffee-Shop-Billing-System`.
3. Add a short description: `A PHP and MySQL coffee shop POS and billing system.`
4. Choose **Public** if you want to show it in your portfolio. Do not upload real customer data or credentials.
5. Create the repository.
6. Extract this project ZIP, open the project folder, and upload the project files and folders to the repository. Make sure `README.md` is at the repository root.
7. Commit the files with a message such as `Initial commit - Coffee Shop Billing System`.

### Option B — Git commands

Open a terminal inside the extracted project folder:

```bash
git init
git add .
git commit -m "Initial commit - Coffee Shop Billing System"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/Coffee-Shop-Billing-System.git
git push -u origin main
```

Replace `YOUR-USERNAME` with your GitHub username. Create the empty GitHub repository first.

## Security and deployment notes

- Never commit database passwords, production secrets, customer information or real user data.
- The included `.gitignore` excludes local environment and editor files.
- Delete `setup_admin.php` immediately after creating the first admin account.
- Use HTTPS and a strong database password when hosting online.
- This project records payment method and amount; it does **not** connect to a bank or process card payments through a payment gateway.
- Before using it in a real shop, add role-based permissions, backups, audit logs, tax configuration and tested payment-gateway integration as required.

## Future improvements

- Inventory and ingredient stock tracking
- Low-stock alerts
- Customer records and loyalty points
- Discounts, taxes and configurable service charges
- Exportable PDF/CSV reports
- Multiple branches and staff permissions
- Real payment gateway integration

## Author

Add your name and GitHub profile here.

## License

This project is provided for learning and portfolio use. Add a license file if you choose to distribute it under a specific open-source license.
