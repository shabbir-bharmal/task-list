# Task Management App

## Overview

This **Task Management App** is a simple task management tool built with **PHP 8.3**, using a **custom MVC (Model-View-Controller)** design pattern and **jQuery** for frontend interactions. It enables users to manage tasks, update statuses, and delete tasks. The application utilizes a MySQL database for storing task-related data.

## Features

- Add new tasks with details.
- Update task statuses (e.g., Completed, Pending).
- Delete tasks.
- Simple MVC structure for clean code organization.
- Input validation with jQuery for a smoother user experience.

## Technologies Used

- **Backend**: PHP 8.3 (Custom MVC Architecture)
- **Frontend**: HTML, CSS, jQuery, Bootstrap
- **Database**: MySQL
- **Version Control**: Git

## Installation

### Requirements

- PHP 8.3 or higher
- MySQL
- Apache/Nginx server
  
### Steps

1. Clone the repository:

   ```bash
   git clone https://github.com/shabbir-bharmal/task-list.git
2. Navigate to the project directory:
     ```bash
     cd task-list
3. Import the database:
- Open your MySQL client (e.g., phpMyAdmin or MySQL CLI).
- Import the database.sql file located in the project root to set up the necessary tables.   
4. Configure the database connection:
- Open the config/database.php file.
- Update the following fields with your MySQL credentials:  
     ```define('DB_HOST', 'localhost');
     define('DB_USER', 'your-username');
     define('DB_PASS', 'your-password');
     define('DB_NAME', 'your-database-name');
