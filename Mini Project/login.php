<?php
session_start();
$conn = new mysqli("localhost","root","","printer_monitor");

if($conn->connect_error){
    die("Database Connection Failed");
}

if(isset($_POST['login']))
{
    $username = trim($_POST['username']);
    $password = md5($_POST['password']);  // consider upgrading to password_hash() later

    // Prevent SQL injection with simple escaping (or use prepared statements)
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if($result->num_rows > 0)
    {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];  // optional

        header("Location: index.php");
        exit();
    }
    else
    {
        $error = "Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printer Monitor · Login</title>
    <!-- Font Awesome for icons (optional but nice) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', system-ui, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 30%, #1a2f47, #0b1725);
            position: relative;
            overflow: hidden;
        }

        /* animated background orbs */
        .orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            background: rgba(0, 242, 255, 0.2);
            filter: blur(100px);
            z-index: 0;
        }

        .orb1 {
            top: -150px;
            left: -150px;
            background: #00c9ff;
            opacity: 0.5;
            animation: float 20s infinite alternate;
        }

        .orb2 {
            bottom: -150px;
            right: -150px;
            background: #ff00c8;
            opacity: 0.4;
            animation: float 18s infinite alternate-reverse;
        }

        .orb3 {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background: #7700ff;
            opacity: 0.15;
            filter: blur(160px);
            animation: pulse 15s infinite;
        }

        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(80px, 50px) scale(1.2); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.1; }
            50% { opacity: 0.25; }
        }

        /* main card */
        .login-card {
            width: 420px;
            background: rgba(20, 30, 45, 0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border-radius: 32px;
            padding: 48px 40px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(255, 255, 255, 0.05) inset;
            color: white;
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        /* header with icon */
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .printer-icon {
            font-size: 42px;
            background: linear-gradient(135deg, #00f2ff, #ff00c8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            filter: drop-shadow(0 0 15px #00f2ffaa);
        }

        .brand h2 {
            font-weight: 500;
            font-size: 24px;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #ffffff, #e0e0ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* subtitle */
        .welcome {
            text-align: center;
            margin-bottom: 32px;
        }

        .welcome .greeting {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #ffffff, #c0d0ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .welcome .hint {
            color: #a0b5cc;
            font-size: 14px;
            font-weight: 400;
        }

        /* input groups with icons */
        .input-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #8ba0bc;
            font-size: 18px;
            transition: color 0.2s;
            pointer-events: none;
        }

        .input-field {
            width: 100%;
            padding: 16px 16px 16px 48px;
            background: rgba(0, 0, 0, 0.3);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px;
            font-size: 16px;
            color: white;
            outline: none;
            transition: all 0.25s;
            backdrop-filter: blur(5px);
        }

        .input-field:focus {
            border-color: #00f2ff;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 0 4px rgba(0, 242, 255, 0.15);
        }

        .input-field:focus + .input-icon {
            color: #00f2ff;
        }

        .input-field::placeholder {
            color: #6f8aac;
            font-weight: 400;
            font-size: 14px;
        }

        /* extras: remember / forgot (optional) */
        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0 28px;
            font-size: 14px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #b0c6e0;
            cursor: pointer;
        }

        .remember input {
            accent-color: #00f2ff;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .forgot-link {
            color: #b0c6e0;
            text-decoration: none;
            transition: color 0.2s;
            border-bottom: 1px dashed #4f6b8f;
        }

        .forgot-link:hover {
            color: #00f2ff;
            border-bottom-color: #00f2ff;
        }

        /* login button */
        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 40px;
            background: linear-gradient(135deg, #00c9ff, #ff00c8);
            color: white;
            font-size: 18px;
            font-weight: 600;
            letter-spacing: 0.5px;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(0, 242, 255, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.7s;
        }

        .login-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 10px 28px rgba(255, 0, 200, 0.5);
        }

        .login-btn:hover::before {
            left: 100%;
        }

        /* error message */
        .error-message {
            background: rgba(255, 75, 75, 0.15);
            border: 1px solid rgba(255, 80, 80, 0.4);
            backdrop-filter: blur(10px);
            color: #ffb0b0;
            padding: 12px 16px;
            border-radius: 40px;
            margin-top: 24px;
            font-size: 14px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%,100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .error-message i {
            color: #ff6b6b;
        }

        /* footer */
        .footer {
            margin-top: 32px;
            text-align: center;
            color: #6f8aac;
            font-size: 13px;
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 24px;
            display: flex;
            justify-content: center;
            gap: 24px;
        }

        .footer a {
            color: #b0c6e0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #00f2ff;
        }

        /* small status (optional) */
        .system-status {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-top: 16px;
            font-size: 12px;
            color: #5f7d9e;
        }
        .system-status i {
            color: #2affa0;
            font-size: 8px;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <!-- floating orbs -->
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="orb orb3"></div>

    <!-- login card -->
    <div class="login-card">
        <div class="brand">
            <i class="fas fa-print printer-icon"></i>
            <h2>PrintMonitor</h2>
        </div>

        <div class="welcome">
            <div class="greeting">Welcome back</div>
            <div class="hint">enter your credentials to access the dashboard</div>
        </div>

        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="input-field" name="username" placeholder="Username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required autofocus>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="input-field" name="password" placeholder="Password" required>
            </div>

            <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="forgot-link" onclick="alert('Demo: reset link would be sent');">Forgot?</a>
            </div>

            <button type="submit" name="login" class="login-btn">
                <i class="fas fa-arrow-right-to-bracket" style="margin-right: 8px;"></i> Sign in
            </button>

            <?php if(isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
        </form>

       

        <div class="footer">
            <a href="#"><i class="far fa-question-circle"></i> Help</a>
            <a href="#"><i class="far fa-shield"></i> Privacy</a>
            <a href="#"><i class="far fa-keyboard"></i> Status</a>
        </div>
    </div>

    <!-- tiny script for demo: prevent "forgot" from navigating -->
    <script>
        (function() {
            // optional: make forgot link harmless demo
            const forgot = document.querySelector('.forgot-link');
            if(forgot) {
                forgot.addEventListener('click', (e) => {
                    e.preventDefault();
                    // you can replace with a small tooltip or just nothing
                });
            }
        })();
    </script>
</body>
</html>