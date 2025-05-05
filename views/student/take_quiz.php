<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

$quiz_id = $_GET['quiz_id'] ?? null;
$student_id = $_SESSION['user']['id'];

// Check if the student has already taken the quiz
$stmt = $pdo->prepare("SELECT * FROM submissions WHERE quiz_id = ? AND student_id = ?");
$stmt->execute([$quiz_id, $student_id]);
$existing_submission = $stmt->fetch();

if ($existing_submission) {
    echo "You have already submitted this quiz!";
    echo "<br><a href='dashboard.php'>Back to Dashboard</a>";
    exit();
}

// Fetch the quiz questions
$stmt = $pdo->prepare("SELECT * FROM questions WHERE quiz_id = ?");
$stmt->execute([$quiz_id]);
$questions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        p {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        label {
            display: block;
            font-size: 1rem;
            margin: 8px 0;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-size: 1.2rem;
            padding: 10px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 1.1rem;
            color: #3498db;
        }

        .back-link a {
            text-decoration: none;
            color: inherit;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>Take Quiz: <?php echo htmlspecialchars($quiz_id); ?></h1>

<form method="POST" action="submit_quiz.php">
    <input type="hidden" name="quiz_id" value="<?php echo $quiz_id; ?>">

    <?php foreach ($questions as $question): ?>
        <p><?php echo htmlspecialchars($question['question_text']); ?></p>
        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="A"> <?php echo htmlspecialchars($question['option_a']); ?></label>
        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="B"> <?php echo htmlspecialchars($question['option_b']); ?></label>
        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="C"> <?php echo htmlspecialchars($question['option_c']); ?></label>
        <label><input type="radio" name="answers[<?php echo $question['id']; ?>]" value="D"> <?php echo htmlspecialchars($question['option_d']); ?></label>
    <?php endforeach; ?>

    <button type="submit">Submit Quiz</button>
</form>

<div class="back-link">
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>
