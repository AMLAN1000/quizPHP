<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f4f4f4;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px 16px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background-color: #007BFF;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        .nav-links {
            text-align: center;
            margin-top: 30px;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Submissions for Quiz ID: <?php echo htmlspecialchars($quiz_id); ?></h1>

<?php if (count($submissions) === 0): ?>
    <p>No submissions found for this quiz.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Score</th>
                <th>Answers</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submissions as $submission): ?>
                <tr>
                    <td>
                        <?php
                        $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
                        $stmt->execute([$submission['student_id']]);
                        $student = $stmt->fetch();
                        echo htmlspecialchars($student['name'] ?? 'Unknown');
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($submission['score']); ?></td>
                    <td>
                        <?php
                        $answers = json_decode($submission['answers'], true);
                        if (is_array($answers)) {
                            foreach ($answers as $q => $a) {
                                echo "Q$q: " . htmlspecialchars($a) . "<br>";
                            }
                        } else {
                            echo "Invalid or empty answers";
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<div class="nav-links">
    <a href="/quiz-website/views/teacher/dashboard.php">Back to Dashboard</a>
    <a href="/quiz-website/views/auth/logout.php" style="margin-left: 10px;">Logout</a>
</div>
</body>
</html>
