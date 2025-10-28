Tour Lanka - Travel Management System

📋 Project Description
Tour Lanka is a comprehensive web-based travel management system designed specifically for Sri Lankan tourism. This platform provides an integrated solution for managing tour packages, hotel accommodations, customer bookings, and administrative operations through a user-friendly interface.

🚀 Features
👥 User Management
Secure user registration and authentication

Profile management with validation

Session-based login system

Password hashing for security

🗺️ Tour Management
Dynamic tour catalog with filtering

CRUD operations for tour packages

Image management for tours

Search and sort functionality

🏨 Hotel Management
Comprehensive hotel listings

Location-based filtering

Price management per night

Hotel image galleries

📅 Booking System
Integrated tour and hotel booking

Date validation and availability checking

Automatic price calculation

Booking status tracking (Pending/Confirmed/Cancelled)

👨‍💼 Admin Panel
Comprehensive dashboard with statistics

Management of tours, hotels, and bookings

User administration

System overview and analytics

🛠️ Technology Stack
Frontend: HTML5, CSS3, JavaScript, Font Awesome

Backend: PHP

Database: MySQL

Security: Prepared Statements, Password Hashing, Session Management

Styling: Custom CSS with responsive design

📁 Project Structure
text
tour-lanka/
│
├── admin/
│   ├── admin_dashboard.php
│   ├── admin_login.php
│   ├── manage_tours.php
│   ├── manage_hotels.php
│   └── manage_bookings.php
│
├── assets/
│   ├── css/
│   ├── images/
│   └── js/
│
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── db_connect.php
│
├── pages/
│   ├── index.php
│   ├── tours.php
│   ├── hotels.php
│   ├── about.php
│   ├── contact.php
│   ├── login.php
│   ├── register.php
│   └── booking.php
│
├── tourlanka_db.sql
└── README.md
🚀 Installation Guide
Prerequisites
Web server (Apache/Nginx)

PHP 7.4 or higher

MySQL 5.7 or higher

Web browser with JavaScript enabled

Setup Instructions
Clone the Repository

bash
git clone https://github.com/yourusername/tour-lanka.git
cd tour-lanka
Database Setup

Create a MySQL database named tourlanka_db

Import the SQL file:

bash
mysql -u username -p tourlanka_db < tourlanka_db.sql
Configuration

Update database credentials in includes/db_connect.php:

php
$host = "localhost";
$user = "your_username";
$password = "your_password";
$db_name = "tourlanka_db";
File Permissions

Set appropriate permissions for upload directories:

bash
chmod 755 assets/images/
Access the Application

Open your web browser and navigate to the project directory

Admin access: /admin/admin_login.php (Default: admin/admin)

👥 User Roles
👤 Customer
Browse tours and hotels

Create account and login

Make bookings

View booking history

👨‍💼 Administrator
Manage all system content

Process bookings

View system analytics

Manage users and content

🔒 Security Features
SQL injection prevention using prepared statements

Password hashing with PHP password_hash()

Session management and validation

Input sanitization and validation

XSS protection

📱 Responsive Design
Mobile-first approach

Cross-browser compatibility

Optimized for various screen sizes

Touch-friendly interface

🎯 Key Functionalities
For Customers
✈️ Browse and search tour packages

🏨 Explore hotel accommodations

📝 Easy registration process

💳 Secure booking system

📱 Mobile-responsive design

For Administrators
📊 Comprehensive dashboard

🛠️ Full CRUD operations

👥 User management

📈 Booking analytics

🔧 System configuration

🔄 Future Enhancements
Payment gateway integration

Email notification system

Multi-language support

Mobile application

Advanced reporting system

Social media integration

Review and rating system

🤝 Contributing
Fork the project

Create your feature branch (git checkout -b feature/AmazingFeature)

Commit your changes (git commit -m 'Add some AmazingFeature')

Push to the branch (git push origin feature/AmazingFeature)

Open a Pull Request

📄 License
This project is licensed under the MIT License - see the LICENSE.md file for details.

👨‍💻 Developer
S.P Madhusanka

GitHub: @yourusername

Email: your.email@example.com
