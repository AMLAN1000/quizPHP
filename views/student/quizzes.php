<?php
require_once '../../config/db.php';
$stmt = $conn->query("SELECT * FROM quizzes");
$quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($quizzes as $quiz) {
    echo "<a href='take_quiz.php?quiz_id={$quiz['id']}'>{$quiz['title']}</a><br>";
}
?>