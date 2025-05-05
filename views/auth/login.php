<?php
session_start();
require '../../config/db.php';

if (!isset($_SESSION['role'])) {
    header("Location: role_select.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_SESSION['role'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        if ($role === 'teacher') {
            header("Location: ../teacher/dashboard.php");
        } else {
            header("Location: ../student/dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid credentials.";
    }
}

$roleColor = ($_SESSION['role'] === 'teacher') ? '#4361ee' : '#4cc9f0';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucfirst($_SESSION['role']); ?> Login - QuizQuizzer</title>
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
            --role-color: <?php echo $roleColor; ?>;
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
            max-width: 450px;
            padding: 40px;
        }
        
        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .role-indicator {
            display: inline-block;
            background-color: var(--role-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        h2 {
            color: var(--dark-color);
            margin-bottom: 30px;
            font-size: 1.8rem;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--role-color);
            box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        }
        
        .form-group i {
            position: absolute;
            left: 15px;
            top: 15px;
            color: #757575;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background-color: var(--role-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
        }
        
        .footer p {
            margin-bottom: 10px;
            color: #555;
        }
        
        .footer a {
            color: var(--role-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .switch-role {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: #777;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">QuizQuizzer</div>
        
        <div class="role-indicator">
            <i class="fas fa-<?php echo ($_SESSION['role'] === 'teacher') ? 'chalkboard-teacher' : 'user-graduate'; ?>"></i>
            <?php echo ucfirst($_SESSION['role']); ?>
        </div>
        
        <h2><?php echo ucfirst($_SESSION['role']); ?> Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Address" required>
            </div>
            
            <div class="form-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div class="footer">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <a href="logout.php" class="switch-role">
                <i class="fas fa-exchange-alt"></i> Not a <?php echo ucfirst($_SESSION['role']); ?>? Change Role
            </a>
        </div>
    </div>
</body>
</html>