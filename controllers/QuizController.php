<?php
session_start();
require_once '../config/db.php';

if (isset($_POST['create_quiz'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $teacher_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, teacher_id) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $teacher_id]);
    $quiz_id = $conn->lastInsertId();

    for ($i = 0; $i < count($_POST['question']); $i++) {
        $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option_1, option_2, option_3, option_4, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $quiz_id,
            $_POST['question'][$i],
            $_POST['option_1'][$i],
            $_POST['option_2'][$i],
            $_POST['option_3'][$i],
            $_POST['option_4'][$i],
            $_POST['correct_answer'][$i]
        ]);
    }

    header('Location: ../views/teacher/dashboard.php');
    exit();
}

if (isset($_POST['submit_quiz'])) {
    $student_id = $_SESSION['user']['id'];
    $quiz_id = $_POST['quiz_id'];

    foreach ($_POST['answers'] as $question_id => $student_answer) {
        $stmt = $conn->prepare("INSERT INTO submissions (student_id, quiz_id, question_id, student_answer) VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_id, $quiz_id, $question_id, $student_answer]);
    }

    header('Location: ../views/student/quizzes.php');
    exit();
}
?>