# DnD Libraries - File Management System

A comprehensive web-based file management and document library system developed as a college project. DnD Libraries allows users to upload, organize, search, and manage documents with features like categorization, tagging, ratings, and admin approval workflows.

## 👥 Authors

**DnD** - College Project Collaboration
- **D** - [Your Name]
- **D** - dinothelo

This project was developed as part of our college coursework, combining our initials to create the "DnD Libraries" system.

## 📋 Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Setup](#database-setup)
- [Features Overview](#features-overview)

## ✨ Features

### User Features
- **User Authentication**: Secure login and registration system
- **Document Upload**: Upload multiple files with metadata (title, description, tags, category)
- **Document Management**: View, edit, and delete your uploaded documents
- **Search & Filter**: Search documents by title, tags, or category
- **Document Details**: View detailed information about documents including ratings and reviews
- **Favorites System**: Mark documents as favorites for quick access
- **Profile Management**: Edit user profile information
- **Notifications**: Receive notifications for document approvals and rejections
- **History Tracking**: View file modification history

### Admin Features
- **Dashboard**: Comprehensive admin dashboard with analytics
- **User Management**: View and manage all users, delete user accounts
- **Document Approval**: Approve or reject uploaded documents
- **Document Management**: View all documents, manage visibility, delete documents
- **Analytics**: View system statistics and usage analytics
- **Authorization System**: Control document approval workflows

## 🛠 Technologies Used

- **Backend**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: 
  - HTML5
  - CSS3
  - JavaScript
  - Bootstrap 5.3.2
  - Font Awesome 6.0
- **Server**: Apache (XAMPP/WAMP recommended)

## 📁 Project Structure

```
DND-Libraries-Repository/
│
├── admin/              # Admin panel files
│   ├── admin_dashboard.php
│   ├── admin_index.php
│   ├── analytics.php
│   ├── authorization.php
│   ├── delete_document.php
│   ├── delete_user.php
│   ├── needs_approval.php
│   ├── view_documents.php
│   ├── view_notifications.php
│   └── view_users.php
│
├── auth/               # Authentication files
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── forgot_password.php
│   ├── loginUnsuccess.php
│   └── successReg.php
│
├── includes/           # Reusable components
│   ├── config.php      # Database configuration
│   └── navbar.php      # Navigation bar component
│
├── pages/              # Main user-facing pages
│   ├── index.php       # Main dashboard
│   ├── profile.php
│   ├── upload.php
│   ├── document_details.php
│   ├── search_results.php
│   ├── categoryResults.php
│   ├── notifications.php
│   ├── notification_history.php
│   ├── history.php
│   ├── uploaded_files.php
│   └── view_details.php
│
├── actions/            # Action/operation files
│   ├── edit_file.php
│   ├── edit_fileHistory.php
│   ├── edit_profile.php
│   ├── delete_file.php
│   ├── update_visibility.php
│   ├── submit_rating.php
│   └── reject.php
│
├── css/                # Stylesheets
│   └── bootstrap.min.css
│
├── js/                 # JavaScript files
│   ├── bootstrap.bundle.js
│   └── bootstrap.bundle.min.js
│
├── img/                # Images and assets
│   ├── logo.png
│   ├── document.jpg
│   ├── menu.png
│   └── forgotPassword.mp4
│
├── sql/                # Database files
│   ├── opensource.sql
│   └── opensource.txt
│
├── uploadedDocs/       # User uploaded documents
│
├── styles.css          # Custom styles
├── .gitignore          # Git ignore file
└── README.md           # This file
```

## 🚀 Installation

### Prerequisites

- PHP 8.2 or higher
- MySQL/MariaDB 10.4 or higher
- Apache Web Server
- XAMPP, WAMP, or similar local development environment

### Step 1: Clone the Repository

```bash
git clone https://github.com/chowderkk/DND-Libraries-Repository.git
cd DND-Libraries-Repository
```

### Step 2: Setup Local Server

1. Copy the project folder to your web server directory:
   - **XAMPP**: `C:\xampp\htdocs\DND-Libraries-Repository`
   - **WAMP**: `C:\wamp64\www\DND-Libraries-Repository`

2. Start Apache and MySQL services from your control panel

### Step 3: Database Setup

1. Open phpMyAdmin (usually at `http://localhost/phpmyadmin`)
2. Create a new database named `opensource`
3. Import the SQL file:
   - Go to the `opensource` database
   - Click on "Import" tab
   - Choose file: `sql/opensource.sql`
   - Click "Go"

### Step 4: Configuration

1. Open `includes/config.php`
2. Update database credentials if needed:

```php
$config = [
    'host' => 'localhost',
    'username' => 'root',        // Your MySQL username
    'password' => '',             // Your MySQL password
    'database' => 'opensource'
];
```

### Step 5: Set Permissions

Ensure the `uploadedDocs/` folder has write permissions:
- **Windows**: Right-click folder → Properties → Security → Edit permissions
- **Linux/Mac**: `chmod 755 uploadedDocs/`

## ⚙️ Configuration

### Database Configuration

Edit `includes/config.php` to match your database settings:

```php
$config = [
    'host' => 'localhost',      // Database host
    'username' => 'root',         // Database username
    'password' => 'your_password', // Database password
    'database' => 'opensource'    // Database name
];
```

### File Upload Configuration

The system supports various file types:
- PDF documents
- Images (JPG, PNG, GIF)
- Compressed files (ZIP, RAR)
- Documents (DOCX, etc.)
- Media files (MP4, MP3)

Upload size limits can be configured in `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 50M
```

## 📖 Usage

### Accessing the Application

1. Open your web browser
2. Navigate to: `http://localhost/DND-Libraries-Repository/pages/index.php`
   - Or: `http://localhost/DND-Libraries-Repository/auth/login.php`

### Default Admin Account

After importing the database, you may need to create an admin account or use the default credentials (if any were set up in the SQL file).

### User Registration

1. Go to the registration page
2. Fill in your details (First Name, Last Name, Email, Password)
3. Click "Register"
4. You'll be redirected to login

### Uploading Documents

1. Login to your account
2. Navigate to "Upload" from the navigation menu
3. Select file(s)
4. Fill in document details:
   - Title
   - Description
   - Category
   - Tags (comma-separated)
   - Visibility (Public/Private)
   - Favorite (optional)
5. Click "Upload"
6. Wait for admin approval (if required)

### Admin Functions

1. Login as admin
2. Access admin dashboard
3. Approve/reject pending documents
4. Manage users and documents
5. View analytics and system statistics

## 🗄️ Database Setup

The database includes the following main tables:

- `users` - User accounts and information
- `documents` - Document metadata and files
- `ratings` - Document ratings and reviews
- `notifications` - System notifications
- `user_favorites` - User favorite documents
- `edit_history` - Document modification history
- `authorization` - Document approval records

Import the `sql/opensource.sql` file to set up all required tables and structure.

## 🎯 Features Overview

### Document Categories
- PDF
- Compressed Folder (ZIP, RAR)
- Other (Images, Media, etc.)

### Document Status
- **Active**: Approved and visible
- **Inactive**: Pending approval
- **Rejected**: Rejected by admin

### Visibility Options
- **Public**: Visible to all users
- **Private**: Visible only to the owner

### Search Capabilities
- Search by title
- Search by tags
- Filter by category
- View search results with document details

## 🔒 Security Features

- Session-based authentication
- Password hashing (ensure passwords are hashed in registration)
- SQL injection prevention (using mysqli_real_escape_string)
- File type validation
- User authorization checks

## 📝 Notes

- This is a college project developed for educational purposes
- Ensure proper security measures are implemented before production use
- Regular backups of the database are recommended
- File uploads are stored in the `uploadedDocs/` directory

## 🤝 Contributing

This is a college project. For suggestions or improvements, please open an issue or contact the authors.

## 📄 License

This project is developed for educational purposes as part of college coursework.

## 👨‍💻 Development

**Project Type**: College Coursework  
**Course**: [Your Course Name]  
**Institution**: [Your College/University Name]  
**Year**: 2024

---

**DnD Libraries** - Organizing knowledge, one document at a time.

For questions or support, please contact the project authors.

