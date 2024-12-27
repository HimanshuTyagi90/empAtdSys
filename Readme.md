Employee Attendance Management System (EAMS)
EAMS is a robust and user-friendly application for managing employee attendance and data efficiently. Designed with modern technologies, it streamlines administrative tasks, providing a seamless experience for both administrators and employees.

Features
Admin Dashboard:
View total employees.
Manage employee details (add, edit, delete).
Monitor employee attendance.
Employee Data Management:
View and update employee information.
Real-time data display using modals and dynamic tables.
Attendance Tracking:
Record and view attendance data for all employees.
Filter attendance records by employee ID.
Secure Access:
Admin login panel to ensure only authorized personnel can access the system.
Technologies Used
Frontend
HTML5: Structure of the web application.
CSS3: Styling and layout.
Bootstrap: Responsive design and pre-styled components.
JavaScript: Dynamic data handling and UI interactions.
Backend
PHP: Server-side scripting for business logic and database interaction.
MySQL: Relational database to store employee and attendance records.
Getting Started
Prerequisites
A web server like XAMPP, WAMP, or MAMP.
PHP version >= 7.4.
MySQL or MariaDB installed.
Installation Steps
Clone the Repository:

bash
Copy code
git clone https://github.com/himanshu5608/LMS_Python.git
Navigate to the Project Directory:

bash
Copy code
cd LMS_Python/EAMS
Set Up the Database:

Import the eams.sql file into your MySQL database.
Make sure the database is named eams or update the configuration in the PHP files accordingly.
Start the Server:

Place the project in your server's root directory (htdocs for XAMPP).
Start the Apache and MySQL services from the server control panel.
Access the Application:

Open a web browser and navigate to:
bash
Copy code
http://localhost/EAMS/adminLogInPanel.php
Folder Structure
graphql
Copy code
EAMS/
├── _partials/               # Reusable PHP components (e.g., navbar, fetch scripts)
├── _partials/_logout.php    # Script to logout user and nav to index.php (destroying sessions)
├── _partials/_navBar.php    # Reusable nav component
├── _partials/fetchEmployeeAttendance.php    # Reusable nav component
├── index.php                # Main entry point
├── adminDashboard.php       # Admin dashboard page
├── adminLogInPanel.php      # Admin login panel
├── signUpPanel.php          # Employee signup panel

├── attendanceMarking.php    # Attendance Marking Screen for Employee ( EndPoint  for Processing  Location tracing and does Device detection locally)

├── fetchAttendance.php      # backend file for attendance marking table (performing sql operations , database processing also )

├── processAdminLogin.php    # Backend for handling admin login process
├── processLocation.php      # EndPoint backend for attendanceMarking.php
├── processUserSignUp.php    # Backend for handling employee signup process
├── processUserLogin.php     # Backend for handling employee login process
└── README.md                # Project documentation
Usage
Log in to the admin panel using your credentials.
Explore the admin dashboard to:
View employee statistics.
Manage employee data.
Monitor attendance records.
Edit employee details or attendance using the provided modals and forms.
Contributing
We welcome contributions to improve EAMS! To contribute:

Fork the repository.
Create a new branch:
bash
Copy code
git checkout -b feature/YourFeatureName
Commit your changes:
bash
Copy code
git commit -m "Add your message here"
Push to your branch:
bash
Copy code
git push origin feature/YourFeatureName
Submit a pull request.
License
This project is licensed under the MIT License. See the LICENSE file for details.


Making Database Table :

adminData :
    1. id 
    2. adminEmail
    3. password

    query to create it : 

    CREATE TABLE `eams`.`admindata` (`id` INT(50) NOT NULL AUTO_INCREMENT , `adminEmail` VARCHAR(50) NOT NULL , `password` VARCHAR(50) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

    query to insert data in it :

    INSERT INTO `admindata` (`id`, `adminEmail`, `password`) VALUES ('1', 'admin@gmail.com', 'admin90');

users :
    1. userId
    2. firstName
    3. lastName
    4. phone
    5. email
    6. longitude
    7. latitude
    8. created_at
    9. password

    query to create it : 

    CREATE TABLE users (
    userId INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    phone VARCHAR(15) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255) NOT NULL,
    longitude VARCHAR(50),
    latitude VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

    query to insert data to this table : 

    INSERT INTO users (firstName, lastName, phone, email, password, longitude, latitude)
    VALUES ('John', 'Doe', '1234567890', 'john.doe@example.com', 'password', '78.0421', '27.1751');





P.S.

For those employees who didn't marked the attendance : -

we have a script in _partials called absentUsers.php

To automatically run the script (absentUsers.php) on  local XAMPP server (or any local server), we can achieve this by using Task Scheduler on Windows, or by setting up a scheduled task with XAMPP's built-in scheduler if you'd prefer to avoid external scheduling. Since you're running a local XAMPP server, I'll explain how to set up a scheduled task using Windows Task Scheduler.

Steps to Automate the PHP Script Execution on XAMPP (Windows)
Locate Your PHP Script: Ensure your PHP script (e.g., markAbsentForAll.php) is saved in a known directory. For example, you might save it under C:\xampp\htdocs\your_project\markAbsentForAll.php.

Verify PHP Path in XAMPP: Find the location of the php.exe executable in your XAMPP installation. Typically, it’s located in:

text
Copy code
C:\xampp\php\php.exe
Create a Batch File (Optional but recommended): This step is useful to ensure the PHP script is executed via the correct PHP version from XAMPP.

Create a batch file (runMarkAbsent.bat) to execute the PHP script via the XAMPP PHP executable.
Open Notepad and enter the following:
batch
Copy code
@echo off
"C:\xampp\php\php.exe" "C:\xampp\htdocs\your_project\markAbsentForAll.php"
Save the file with a .bat extension (e.g., runMarkAbsent.bat).
Create a Scheduled Task in Windows Task Scheduler:

Open Task Scheduler: Press Win + R, type taskschd.msc, and press Enter.
On the right side, click Create Basic Task.
Name your task (e.g., "Mark Absent for All Users").
Set the Trigger:
Choose Daily and set the time for when you want the script to run (e.g., 7:00 PM).
Set the Action to Start a Program:
Click Browse and select the batch file (runMarkAbsent.bat) you created earlier.
Finish the task setup by clicking Finish.
Test the Task:

To test, right-click the task in the Task Scheduler Library and choose Run. It should execute the batch file and run the PHP script.
Check if the PHP script is properly updating the database as expected.


Contact
For any queries or support, feel free to reach out:

Author: Himanshu Tyagi
Contact No : 8126243285
