<?php
session_start();

// Include database connection
require '../config/db.php'; // Fix the path to match your file structure

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

// Get quiz ID from the URL
$quiz_id = $_GET['quiz_id'] ?? null;

if (!$quiz_id) {
    die("Quiz ID not provided!");
}

// Fetch submissions for the quiz
$stmt = $pdo->prepare("SELECT * FROM submissions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$submissions = $stmt->fetchAll();

// Pass the data to the view (e.g., 'view_submission.php')
include '../views/teacher/view_submissions.php'; // Ensure this path is correct
?>
