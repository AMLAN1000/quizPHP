-- Create database if it does not exist
CREATE DATABASE IF NOT EXISTS quiz_db;
USE quiz_db;

-- Create the users table (teachers and students)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('teacher', 'student') NOT NULL
);

-- Create the quizzes table (with a foreign key linking to the teacher)
CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    teacher_id INT,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create the questions table (with a foreign key linking to quizzes)
CREATE TABLE IF NOT EXISTS questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    question_text TEXT,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1),  -- This field will store the letter representing the correct option (A, B, C, or D)
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE
);

-- Create the submissions table (with foreign keys linking to quizzes and users)
CREATE TABLE IF NOT EXISTS submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT,
    student_id INT,
    score INT DEFAULT 0,   -- Default score is 0
    answers JSON,          -- Store answers as JSON, which makes it easier to process later
    submission_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,  -- To record when the submission was made
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE
);
