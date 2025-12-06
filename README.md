


# Human Resource Information System (HRIS)

## Overview
A modern, lightweight, and fully functional web-based Human Resource Information System built for small to medium-sized companies. This HRIS allows HR staff to efficiently manage employee records, track daily attendance, and send professional company-wide announcements via email.

The system features a clean dashboard, real-time AJAX-powered updates, and a beautiful announcement composer with audience targeting (All / Department / Individual employees).

---
## Features

### Dashboard
- Clean overview of total employees, active staff, departments, and recent activity
- Quick HR metrics at a glance

### Employee Management
- Complete employee directory with search and filters
- View detailed profiles (personal info, position, department, status, etc.)
- Add, edit, and manage employee records

### Attendance Tracking
- Daily attendance logging
- Mock biometric interface (manual Clock In / Clock Out for testing)
- Real-time attendance updates using AJAX
- Visual indicators for Present / Absent / Late

### Announcements (Fully Working)
- Beautiful two-step announcement composer
- Rich preview with subject, header, body, and sign-off
- Target audience options:
  - All Active **All Employees**
  - Active **Specific Department(s)**
  - Active **Individual Employee(s)**
- Delivery via **Email (BCC)** using Gmail SMTP
- Success confirmation modal after sending
- Full history stored in database with read-tracking support
- Active **SMS functionality has been intentionally removed** for reliability and cost control

### User Authentication & Settings
- Secure login system with session management
- Role-based access (HR / Admin / Employee)
- Personal profile and settings page

---
## Technology Stack
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript (Vanilla + jQuery)
- **Backend:** PHP 8+
- **Database:** MySQL / MariaDB
- **Email Delivery:** PHPMailer + Gmail SMTP (App Password recommended)
- **Live Updates:** AJAX + JSON
- **Hosting Tested On:** XAMPP / Windows (fully compatible with Linux & shared hosting too)

---
## Installation / Setup

1. Place the entire project folder inside your web server root  
   (e.g., `C:\xampp\htdocs\HRIS\` or `/var/www/html/HRIS/`)

2. Import the database:
   ```bash
   mysql -u root -p hr_management_system < sqldump.sql
   
3. Clone the repository:
   ```bash
   git clone https://github.com/RKLOPEZ13/Human-Resource-Information-System.git
