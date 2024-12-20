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