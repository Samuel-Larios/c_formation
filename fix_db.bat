@echo off
cd C:\xampp\mysql\bin
mysql.exe -u root -e "USE songhai; ALTER TABLE migrations DISCARD TABLESPACE;"
mysql.exe -u root -e "USE songhai; CREATE TABLE migrations (id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, batch INT NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
pause
