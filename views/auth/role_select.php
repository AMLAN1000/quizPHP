<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: login.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --teacher-color: #4361ee;
            --student-color: #4cc9f0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fb;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            padding: 40px;
            text-align: center;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 30px;
            font-size: 1.5rem;
        }
        
        .role-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }
        
        .role-button {
            background-color: white;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            width: 45%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .role-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .role-button.teacher:hover {
            border-color: var(--teacher-color);
        }
        
        .role-button.student:hover {
            border-color: var(--student-color);
        }
        
        .role-button i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .teacher i {
            color: var(--teacher-color);
        }
        
        .student i {
            color: var(--student-color);
        }
        
        .role-button span {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark-color);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">QuizQuizzer</div>
        <h2>Welcome! Select Your Role</h2>
        
        <form method="POST" action="set_role.php">
            <div class="role-buttons">
                <button type="submit" name="role" value="teacher" class="role-button teacher">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Teacher</span>
                </button>
                
                <button type="submit" name="role" value="student" class="role-button student">
                    <i class="fas fa-user-graduate"></i>
                    <span>Student</span>
                </button>
            </div>
        </form>
    </div>
</body>
</html>