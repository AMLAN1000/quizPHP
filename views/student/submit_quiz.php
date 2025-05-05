<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$quiz_id = $_POST['quiz_id'];
$student_id = $_SESSION['user']['id'];
$answers = $_POST['answers'] ?? [];

if (empty($answers)) {
    echo "Please answer all the questions.";
    exit();
}

// Calculate the score
$score = 0;
foreach ($answers as $question_id => $answer) {
    $stmt = $pdo->prepare("SELECT correct_option FROM questions WHERE id = ?");
    $stmt->execute([$question_id]);
    $question = $stmt->fetch();

    if ($question && $question['correct_option'] == $answer) {
        $score++;
    }
}

// Save the submission to the database
$stmt = $pdo->prepare("INSERT INTO submissions (quiz_id, student_id, score, answers) VALUES (?, ?, ?, ?)");
$stmt->execute([$quiz_id, $student_id, $score, json_encode($answers)]);

echo "Quiz submitted successfully! Your score: $score";
?>

<br>
<!-- Back to quiz or dashboard -->
<a href="../student/dashboard.php">Back to Dashboard</a> | 

<!-- Logout option -->
<a href="../../views/auth/logout.php">Logout</a>
