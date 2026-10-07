CREATE DATABASE IF NOT EXISTS smart_campus_maintenance;
USE smart_campus_maintenance;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100) NOT NULL,email VARCHAR(100) NOT NULL UNIQUE,password VARCHAR(255) NOT NULL,phone VARCHAR(15),department VARCHAR(80),role ENUM('user','staff','admin') DEFAULT 'user',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE staff(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),email VARCHAR(100),phone VARCHAR(15),department VARCHAR(80),specialization VARCHAR(80),status ENUM('Active','Inactive') DEFAULT 'Active');
CREATE TABLE complaints(id INT AUTO_INCREMENT PRIMARY KEY,user_id INT NOT NULL,category VARCHAR(40),title VARCHAR(150),description TEXT,location VARCHAR(150),severity ENUM('Low','Medium','High'),priority ENUM('LOW','MEDIUM','HIGH'),image VARCHAR(255),status ENUM('Pending','Assigned','In Progress','Resolved','Closed') DEFAULT 'Pending',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE);
CREATE TABLE assignments(id INT AUTO_INCREMENT PRIMARY KEY,complaint_id INT,staff_id INT,assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,FOREIGN KEY(staff_id) REFERENCES staff(id) ON DELETE CASCADE);
CREATE TABLE updates(id INT AUTO_INCREMENT PRIMARY KEY,complaint_id INT,staff_id INT,status VARCHAR(20),notes TEXT,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,FOREIGN KEY(staff_id) REFERENCES staff(id) ON DELETE CASCADE);
CREATE TABLE feedback(id INT AUTO_INCREMENT PRIMARY KEY,complaint_id INT,user_id INT,rating TINYINT,comments TEXT,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(complaint_id) REFERENCES complaints(id) ON DELETE CASCADE,FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE);
INSERT INTO users(name,email,password,phone,department,role) VALUES
('Campus Admin','admin@campus.com','$2b$10$vw0sXdm63HSza3zQwnOi7unocTBud86klrDst4MeYsIBEgLIgzc56','9000000001','Administration','admin'),
('Ramesh Kumar','staff@campus.com','$2b$10$GuukJkKMcMQCi7NgYzmxWeOID2MhNxPKYeCMcB0JLAjiucuBsR9ey','9000000002','Maintenance','staff'),
('Anitha Student','student@campus.com','$2b$10$o.BqGgv6dXjcapROs1Uk5Og3GzkN8p8Zojg2ljnatvRxRkl4F0NDO','9000000003','CSE','user');
INSERT INTO staff(name,email,phone,department,specialization) VALUES
('Ramesh Kumar','staff@campus.com','9000000002','Maintenance','Electrical'),
('Priya Devi','priya.staff@campus.com','9000000004','Maintenance','Plumbing');
INSERT INTO complaints(user_id,category,title,description,location,severity,priority,status) VALUES
(3,'Electrical','Sparking switch board','Switch board sparks when fan is on.','Block A, Floor 2, Room 204','High','HIGH','Assigned'),
(3,'Water','Tap leakage','Water leaking near washroom.','Block B, Floor 1, Room 105','Medium','MEDIUM','Pending'),
(3,'Furniture','Broken bench','Bench leg is broken.','Block C, Floor 3, Room 301','Low','LOW','Resolved'),
(3,'Network/Internet','Wi-Fi down in lab','No internet in CSE lab.','Block A, Floor 1, Lab 3','High','HIGH','In Progress');
INSERT INTO assignments(complaint_id,staff_id) VALUES(1,1),(4,1);
INSERT INTO updates(complaint_id,staff_id,status,notes) VALUES(4,1,'In Progress','Checking router and switch.');
