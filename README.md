# 🛒 Secure E-Commerce Platform

A full-featured e-commerce web application built with PHP and MySQL, designed with security best practices in mind.

## 📋 Features

- **User Management**
  - User registration and login system
  - Email verification for new accounts
  - Secure password reset functionality
  - User profile management

- **Product Management**
  - Browse products by category
  - Product search functionality
  - Detailed product pages
  - Product image gallery

- **Shopping Cart**
  - Add/remove products
  - Update quantities
  - Session-based cart for guests
  - Persistent cart for logged-in users

- **Checkout & Payment**
  - Secure checkout process
  - PayFast payment gateway integration
  - Order tracking
  - Email notifications

- **Admin Panel**
  - Product management
  - Order management
  - User management
  - Sales reporting and analytics

## 🔒 Security Features

- Secure session management with HTTPOnly and Secure cookies
- Password hashing using bcrypt
- CSRF protection ready
- XSS prevention with output sanitization
- SQL injection prevention using prepared statements
- Rate limiting for brute force protection
- HTTP security headers (X-Frame-Options, X-Content-Type-Options)
- Directory listing disabled

## 🚀 Getting Started

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for PHPMailer dependencies)

### Installation

1. Clone the repository:
```bash
git clone https://github.com/aneeqa923/Secure-E-Commerce.git
cd Secure-E-Commerce
```

2. Import the database:
```bash
mysql -u your_username -p your_database < database/ecomm.sql
```

3. Configure database connection:
   - Edit `includes/conn.php`
   - Update database credentials

4. Install dependencies:
```bash
composer install
```

5. Configure email settings:
   - Edit `register.php`
   - Update SMTP credentials for email verification

6. Set up PayFast:
   - Edit `payfast.php`
   - Add your PayFast credentials

7. Start your web server and navigate to the project directory

## 📁 Project Structure

```
├── admin/              # Admin panel
├── database/           # Database schema
├── images/             # Product images
├── includes/           # Core PHP files
│   ├── conn.php       # Database connection
│   ├── session.php    # Session management
│   └── security.php   # Security helper functions
├── vendor/             # Composer dependencies
├── index.php          # Homepage
├── login.php          # User login
├── signup.php         # User registration
├── cart.php           # Shopping cart
└── checkout.php       # Checkout process
```

## 🛠️ Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Email**: PHPMailer
- **Payment**: PayFast API
- **Frontend**: HTML, CSS, JavaScript, Bootstrap
- **Dependencies**: Composer

## ⚙️ Configuration

### Database Configuration
Update `includes/conn.php` with your database credentials:
```php
private $server = "mysql:host=localhost;dbname=your_database";
private $username = "your_username";
private $password = "your_password";
```

### Email Configuration
Update SMTP settings in `register.php` for email functionality.

### Payment Gateway
Update PayFast credentials in `payfast.php` for payment processing.

## 📝 License

This project is available for educational and commercial use.

## 👤 Author

**Aneeqa**
- GitHub: [@aneeqa923](https://github.com/aneeqa923)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## ⭐ Show your support

Give a ⭐️ if this project helped you!
