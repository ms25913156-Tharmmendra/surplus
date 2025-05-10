# Surplus Project

This repository contains the SQL scripts and instructions for setting up the Surplus database on a local PHP environment. The Surplus project aims to connect food posters and food seekers, allowing users to share surplus food.

## Prerequisites

Before you begin, ensure that you have the following installed:

- PHP
- MySQL
- phpMyAdmin
- A local server like XAMPP or WAMP

## Setup Instructions

Follow these steps to get the project up and running:

### 1. Create the Database
- Open **phpMyAdmin** and log in to your MySQL server.
- Create a new database named `surplus`.

### 2. Import the SQL Script
- Download or clone this repository.
- Locate the `surplus.sql` file in the repository.
- In phpMyAdmin, select the `surplus` database and click on the "Import" tab.
- Upload the `surplus.sql` file to import all the required tables and data.

### 3. Create a Folder for the Project
- Create a folder named `surplus` in the **htdocs** directory of your local server (e.g., `C:\xampp\htdocs` for XAMPP or `C:\wamp\www` for WAMP).
- Copy all the contents of this repository into the newly created `surplus` folder.

### 4. Access the Project in Your Browser
- Open your web browser and type `localhost/surplus` in the address bar.
- The Surplus website should now be accessible on your local server.

### 5. Login Details
Here are the login details for the various roles:

- **Admin**:  
  - Email: `ms25913156@my.sliit.lk`  
  - Password: `123456`

- **Food Posters**:  
  - Email: `raj123@gmail.com`  
  - Password: `123456`

- **Food Seekers**:  
  - Email: `tharmmendra@gmail.com`  
  - Password: `123456`

### 6. Troubleshooting
- Ensure that your local server (XAMPP or WAMP) is running and that both Apache and MySQL are started.
- If you encounter a 500 Internal Server Error, check the server logs for more details.

## License
This project is open-source and available under the [MIT License](LICENSE).
