-- JobBoard Database Schema
-- Student: Abdullah | Roll No: 37891

CREATE DATABASE IF NOT EXISTS jobboard;
USE jobboard;

-- Users table (admin + employers + seekers)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','employer','seeker') DEFAULT 'seeker',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Jobs table
CREATE TABLE jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    company VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    type ENUM('Full-time','Part-time','Remote','Internship') DEFAULT 'Full-time',
    description TEXT,
    requirements TEXT,
    salary VARCHAR(50),
    status ENUM('active','closed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Applications table
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    cover_letter TEXT,
    resume_file VARCHAR(255),
    status ENUM('pending','reviewed','accepted','rejected') DEFAULT 'pending',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Contacts table
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    message TEXT,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed admin user (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@jobboard.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Seed sample jobs
INSERT INTO users (name, email, password, role) VALUES
('TechCorp HR', 'hr@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'employer');

INSERT INTO jobs (user_id, title, company, location, type, description, requirements, salary) VALUES
(2, 'PHP Developer', 'TechCorp', 'Islamabad', 'Full-time', 'We need a skilled PHP developer to join our team.', 'PHP, MySQL, HTML, CSS', '50000-80000 PKR'),
(2, 'Frontend Developer', 'TechCorp', 'Remote', 'Remote', 'Build modern UIs using HTML/CSS/JS.', 'HTML, CSS, JavaScript, Bootstrap', '40000-70000 PKR'),
(2, 'Web Design Intern', 'TechCorp', 'Lahore', 'Internship', 'Learn and contribute to real projects.', 'Basic HTML/CSS knowledge', 'Stipend');
