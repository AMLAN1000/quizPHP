<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header("Location: ../auth/login.php");
    exit();
}

require '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $teacher_id = $_SESSION['user']['id'];

    // Insert the quiz into the quizzes table
    $stmt = $pdo->prepare("INSERT INTO quizzes (title, teacher_id) VALUES (?, ?)");
    $stmt->execute([$title, $teacher_id]);
    $quiz_id = $pdo->lastInsertId(); // Get the last inserted quiz ID

    // Loop through the questions and insert them into the questions table
    foreach ($_POST['questions'] as $index => $question) {
        $question_text = $question['question_text'];
        $option_a = $question['option_a'];
        $option_b = $question['option_b'];
        $option_c = $question['option_c'];
        $option_d = $question['option_d'];
        $correct_option = $question['correct_option'];

        // Insert the question into the questions table
        $stmt = $pdo->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$quiz_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option]);
    }

    header("Location: dashboard.php"); // Redirect to teacher dashboard after successful creation
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz - EduConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --teacher-color: #4361ee;
            --light-bg: #f5f7fb;
            --border-color: #e0e0e0;
            --success-color: #198754;
            --warning-color: #ffc107;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light-bg);
            color: var(--dark-color);
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
            margin: 0;
        }
        
        .header .nav-links {
            display: flex;
            gap: 15px;
        }
        
        .nav-link {
            color: var(--dark-color);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover {
            background-color: var(--light-bg);
        }
        
        .nav-link.home {
            background-color: var(--light-bg);
        }
        
        .nav-link.logout {
            color: #dc3545;
        }
        
        .nav-link i {
            margin-right: 5px;
        }
        
        .main-content {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }
        
        .section-title {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        }
        
        .question-card {
            background-color: var(--light-bg);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--primary-color);
        }
        
        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .question-number {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.1rem;
        }
        
        .option-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        
        @media (max-width: 768px) {
            .option-group {
                grid-template-columns: 1fr;
            }
        }
        
        .option-label {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .option-letter {
            background-color: var(--primary-color);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8rem;
            margin-right: 8px;
            font-weight: bold;
        }
        
        .correct-option-group {
            margin-top: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #157347;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        
        .remove-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .remove-btn:hover {
            color: #b02a37;
        }
        
        select.form-control {
            padding-right: 30px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 12px;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-edit"></i> Create New Quiz</h1>
            <div class="nav-links">
                <a href="dashboard.php" class="nav-link home"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="../auth/logout.php" class="nav-link logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
        
        <div class="main-content">
            <h2 class="section-title">Quiz Details</h2>
            
            <form method="POST">
                <div class="form-group">
                    <label for="title">Quiz Title</label>
                    <input type="text" name="title" id="title" class="form-control" placeholder="Enter a descriptive title for your quiz" required>
                </div>
                
                <h2 class="section-title">Quiz Questions</h2>
                
                <div id="questions">
                    <div class="question-card">
                        <div class="question-header">
                            <div class="question-number">Question 1</div>
                        </div>
                        
                        <div class="form-group">
                            <label for="question_text">Question Text:</label>
                            <input type="text" name="questions[0][question_text]" class="form-control" placeholder="Type your question here" required>
                        </div>
                        
                        <div class="option-group">
                            <div class="form-group">
                                <div class="option-label">
                                    <div class="option-letter">A</div>
                                    <label for="option_a">Option A:</label>
                                </div>
                                <input type="text" name="questions[0][option_a]" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <div class="option-label">
                                    <div class="option-letter">B</div>
                                    <label for="option_b">Option B:</label>
                                </div>
                                <input type="text" name="questions[0][option_b]" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <div class="option-label">
                                    <div class="option-letter">C</div>
                                    <label for="option_c">Option C:</label>
                                </div>
                                <input type="text" name="questions[0][option_c]" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <div class="option-label">
                                    <div class="option-letter">D</div>
                                    <label for="option_d">Option D:</label>
                                </div>
                                <input type="text" name="questions[0][option_d]" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group correct-option-group">
                            <label for="correct_option">Correct Option:</label>
                            <select name="questions[0][correct_option]" class="form-control" required>
                                <option value="">-- Select Correct Answer --</option>
                                <option value="A">Option A</option>
                                <option value="B">Option B</option>
                                <option value="C">Option C</option>
                                <option value="D">Option D</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <button type="button" onclick="addQuestion()" class="btn btn-secondary">
                        <i class="fas fa-plus"></i> Add Another Question
                    </button>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Create Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let questionCount = 1;

        function addQuestion() {
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question-card');
            questionDiv.innerHTML = `
                <div class="question-header">
                    <div class="question-number">Question ${questionCount + 1}</div>
                    <button type="button" class="remove-btn" onclick="removeQuestion(this)">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
                
                <div class="form-group">
                    <label for="question_text">Question Text:</label>
                    <input type="text" name="questions[${questionCount}][question_text]" class="form-control" placeholder="Type your question here" required>
                </div>
                
                <div class="option-group">
                    <div class="form-group">
                        <div class="option-label">
                            <div class="option-letter">A</div>
                            <label for="option_a">Option A:</label>
                        </div>
                        <input type="text" name="questions[${questionCount}][option_a]" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="option-label">
                            <div class="option-letter">B</div>
                            <label for="option_b">Option B:</label>
                        </div>
                        <input type="text" name="questions[${questionCount}][option_b]" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="option-label">
                            <div class="option-letter">C</div>
                            <label for="option_c">Option C:</label>
                        </div>
                        <input type="text" name="questions[${questionCount}][option_c]" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <div class="option-label">
                            <div class="option-letter">D</div>
                            <label for="option_d">Option D:</label>
                        </div>
                        <input type="text" name="questions[${questionCount}][option_d]" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group correct-option-group">
                    <label for="correct_option">Correct Option:</label>
                    <select name="questions[${questionCount}][correct_option]" class="form-control" required>
                        <option value="">-- Select Correct Answer --</option>
                        <option value="A">Option A</option>
                        <option value="B">Option B</option>
                        <option value="C">Option C</option>
                        <option value="D">Option D</option>
                    </select>
                </div>
            `;
            document.getElementById('questions').appendChild(questionDiv);
            questionCount++;
        }

        function removeQuestion(button) {
            const questionCard = button.closest('.question-card');
            questionCard.remove();
            
            // Update question numbers
            const questionNumbers = document.querySelectorAll('.question-number');
            questionNumbers.forEach((element, index) => {
                element.textContent = `Question ${index + 1}`;
            });
        }
    </script>
</body>
</html>