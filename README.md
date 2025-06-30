# 🎓 Online Learning Portal

An online learning management system (LMS) developed using **HTML**, **CSS**, **PHP**, and **MySQL**. The portal facilitates student enrollment, course access, lesson tracking, and assessments. Admins can manage users, courses, and monitor student progress.

---

## 🔧 Tech Stack

- **Frontend**: HTML5, CSS3  
- **Backend**: PHP 7+  
- **Database**: MySQL  
- **Server**: Apache (Recommended: XAMPP or LAMP stack)

---

## 🚀 Features

### 👨‍🎓 Student Panel
- Student registration and login
- Browse and enroll in available courses
- Watch lessons and download resources
- Attempt quizzes or assignments
- Track course completion status

### 🧑‍🏫 Instructor / Admin Panel
- Admin login
- Add/edit/delete courses and modules
- Upload videos, PDFs, and quiz content
- View student progress and performance

---

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

git clone https://github.com/opsrijan/online-learning-portal.git
cd online-learning-portal

### 2. Move to Server Directory
Place the project inside your XAMPP htdocs folder or LAMP www directory.

### 3. Import the Database
Start Apache and MySQL via XAMPP.

Open phpMyAdmin.

Create a database (e.g., learning_portal) and import the provided onlinelearning.sql file.

### 4. Configure Database Connection
Update the database credentials in includes/db_connect.php:
// includes/db_connect.php
$host = "localhost";
$user = "root";
$password = "";
$database = "onlinelearning";

### 5. Access the Portal
Open your browser and visit:
http://localhost/online-learning-portal/

🔐 Default Admin Credentials
Username: admin

Password: admin123

(You can modify credentials directly in the MySQL database if needed.)

📄 License
This project is licensed under the MIT License.
You are free to use, modify, and distribute with attribution.
