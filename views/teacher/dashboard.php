<?php
session_start();
require '../../config/db.php';

// Ensure the user is logged in and is a teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch quizzes created by the teacher
$stmt = $pdo->prepare("SELECT * FROM quizzes WHERE teacher_id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$quizzes = $stmt->fetchAll();

// Count total submissions for all quizzes by this teacher
$totalSubmissions = 0;
if (count($quizzes) > 0) {
    $quizIds = array_column($quizzes, 'id');
    $placeholders = implode(',', array_fill(0, count($quizIds), '?'));

    $stmt = $pdo->prepare("SELECT COUNT(*) as submission_count FROM submissions WHERE quiz_id IN ($placeholders)");
    $stmt->execute($quizIds);
    $result = $stmt->fetch();
    $totalSubmissions = $result['submission_count'];
}

// Get teacher name
$teacherName = isset($_SESSION['user']['name']) ? $_SESSION['user']['name'] : 'Teacher';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f5f5f5; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; background-color: #fff; margin-bottom: 20px; }
        th, td { padding: 12px; border: 1px solid #ccc; text-align: left; }
        th { background-color: #eee; }
        a.button { display: inline-block; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; }
        a.button:hover { background-color: #0056b3; }
        .stats { margin: 20px 0; }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($teacherName); ?>!</h1>

    <div class="stats">
        <p><strong>Total Quizzes:</strong> <?php echo count($quizzes); ?></p>
        <p><strong>Total Submissions:</strong> <?php echo $totalSubmissions; ?></p>
    </div>

    <h2>Your Quizzes</h2>

    <?php if (count($quizzes) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Quiz Title</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                        <td>
                            <a class="button" href="../../controllers/submission_controller.php?quiz_id=<?php echo $quiz['id']; ?>">View Submissions</a>
                            
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You haven't created any quizzes yet.</p>
    <?php endif; ?>

    <a class="button" href="create_quiz.php">Create New Quiz</a>
    <a class="button" href="../auth/logout.php">Logout</a>
</body>
</html>
