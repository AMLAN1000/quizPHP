<?php
session_start();

// Check if the user is logged in and is a student
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../auth/login.php");
    exit();
}

require '../../config/db.php';

// Get available quizzes
$stmt = $pdo->prepare("SELECT * FROM quizzes");
$stmt->execute();
$quizzes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 40px;
            color: #333;
        }

        h1, h2 {
            text-align: center;
            color: #2c3e50;
        }

        ul {
            list-style: none;
            padding: 0;
            max-width: 600px;
            margin: 20px auto;
        }

        li {
            background-color: #ffffff;
            border-radius: 8px;
            margin-bottom: 12px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }

        li:hover {
            transform: scale(1.01);
        }

        a {
            display: block;
            padding: 14px 20px;
            color: #007BFF;
            text-decoration: none;
            font-weight: bold;
            border-left: 5px solid #007BFF;
        }

        a:hover {
            background-color: #f0f8ff;
        }

        .logout-link {
            display: block;
            width: fit-content;
            margin: 30px auto 0;
            text-align: center;
            background-color: #e74c3c;
            color: white;
            padding: 10px 18px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-link:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h1>
<h2>Available Quizzes</h2>

<ul>
    <?php foreach ($quizzes as $quiz): ?>
        <li>
            <a href="take_quiz.php?quiz_id=<?php echo $quiz['id']; ?>">
                <?php echo htmlspecialchars($quiz['title']); ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>

<a class="logout-link" href="../auth/logout.php">Logout</a>

</body>
</html>
