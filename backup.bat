@echo off
set dbUser =shira
set dbName=fsvolunteer
set backupDir="C:\xampp\htdocs\MyPHPSite\workshop 2\backupfile"
set backupFile=%backupDir%\backup_file_%date:~-4,4%-%date:~-10,2%-%date:~-7,2%_%time:~0,2%-%time:~3,2%-%time:~6,2%.sql

"C:\xampp\mysql\bin\mysqldump" -u %dbUser % %dbName% > %backupFile%