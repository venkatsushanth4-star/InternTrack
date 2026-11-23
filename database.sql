CREATE DATABASE IF NOT EXISTS student_internship_tracker
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_internship_tracker;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    college VARCHAR(150) DEFAULT NULL,
    profile_image VARCHAR(255) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS internships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    company VARCHAR(150) NOT NULL,
    role VARCHAR(150) NOT NULL,
    location VARCHAR(150) DEFAULT NULL,
    type VARCHAR(60) NOT NULL DEFAULT 'Internship',
    applied_date DATE DEFAULT NULL,
    deadline DATE DEFAULT NULL,
    interview_date DATE DEFAULT NULL,
    interview_notes TEXT DEFAULT NULL,
    offer_received_date DATE DEFAULT NULL,
    offer_deadline DATE DEFAULT NULL,
    stipend VARCHAR(100) DEFAULT NULL,
    internship_start_date DATE DEFAULT NULL,
    offer_notes TEXT DEFAULT NULL,
    status ENUM('Applied','Shortlisted','Interview','Offer Received','Accepted','Selected','Rejected','Withdrawn','In Progress','Completed') NOT NULL DEFAULT 'Applied',
    notes TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_internship_student FOREIGN KEY (student_id)
        REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_student_status (student_id, status),
    INDEX idx_student_interview (student_id, interview_date)
);
