<?php
session_start();
$conn = new mysqli("localhost", "root", "", "printer_monitor");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';
$success = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = md5($_POST['password']); // Consider using password_hash() in production

    // Simple prevention of SQL injection (use prepared statements for better security)
    $username = $conn->real_escape_string($username);
    $password = $conn->real_escape_string($password);

    $q = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");

    if ($q && $q->num_rows > 0) {
        $user = $q->fetch_assoc();
        $_SESSION['user'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Printer Monitor · Login</title>
    <!-- Font Awesome 6 (free) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz@14..32&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        /* CSS variables for themes */
        :root {
            /* Light theme (default) */
            --bg-gradient-start: #e0e7ff;
            --bg-gradient-end: #f0f4ff;
            --card-bg: rgba(255, 255, 255, 0.85);
            --card-border: rgba(255, 255, 255, 0.5);
            --text-primary: #1e293b;
            --text-secondary: #475569;
            --text-muted: #64748b;
            --input-bg: rgba(255, 255, 255, 0.9);
            --input-border: #e2e8f0;
            --input-focus-border: #3b82f6;
            --input-icon: #94a3b8;
            --placeholder-color: #94a3b8;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --error-bg: #fee2e2;
            --error-border: #fecaca;
            --error-text: #b91c1c;
            --theme-toggle-bg: #f1f5f9;
            --theme-toggle-icon: #f59e0b;
            --footer-link: #64748b;
        }

        /* Dark theme */
        [data-theme="dark"] {
            --bg-gradient-start: #0b1120;
            --bg-gradient-end: #1a1f2f;
            --card-bg: rgba(17, 25, 40, 0.85);
            --card-border: rgba(75, 85, 120, 0.4);
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            --input-bg: rgba(30, 41, 59, 0.9);
            --input-border: #334155;
            --input-focus-border: #60a5fa;
            --input-icon: #94a3b8;
            --placeholder-color: #64748b;
            --shadow-color: rgba(0, 0, 0, 0.5);
            --error-bg: #2d1a1a;
            --error-border: #742a2a;
            --error-text: #fca5a5;
            --theme-toggle-bg: #1e293b;
            --theme-toggle-icon: #fbbf24;
            --footer-link: #94a3b8;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, var(--bg-gradient-start), var(--bg-gradient-end));
            transition: background 0.3s ease;
            padding: 1rem;
            position: relative;
        }

        /* Animated background elements */
        .bg-shape {
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(45deg, #3b82f6, #a855f7);
            filter: blur(80px);
            opacity: 0.15;
            z-index: 0;
        }

        .shape1 { top: -100px; left: -100px; }
        .shape2 { bottom: -100px; right: -100px; background: linear-gradient(45deg, #f97316, #ec4899); }

        /* Main card */
        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 32px;
            padding: 2.5rem 2rem;
            box-shadow: 0 25px 50px -12px var(--shadow-color);
            position: relative;
            z-index: 10;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        /* Theme toggle */
        .theme-toggle {
            position: absolute;
            top: 24px;
            right: 24px;
            background: var(--theme-toggle-bg);
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--theme-toggle-icon);
            font-size: 1.4rem;
            transition: 0.2s;
            border: 1px solid var(--card-border);
        }

        .theme-toggle:hover {
            transform: scale(1.1);
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .printer-icon {
            font-size: 2.8rem;
            background: linear-gradient(135deg, #3b82f6, #a855f7);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .brand h2 {
            font-size: 1.8rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        /* Welcome text */
        .welcome {
            margin-bottom: 2rem;
        }

        .welcome h3 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .welcome p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Input groups */
        .input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--input-icon);
            font-size: 1.1rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-field {
            width: 100%;
            padding: 16px 16px 16px 50px;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 40px;
            font-size: 1rem;
            color: var(--text-primary);
            outline: none;
            transition: all 0.2s;
        }

        .input-field:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .input-field::placeholder {
            color: var(--placeholder-color);
            font-weight: 400;
        }

        .input-field:focus + .input-icon {
            color: var(--input-focus-border);
        }

        /* Options row */
        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 1.5rem 0 2rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 1px dashed var(--text-muted);
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }

        /* Login button */
        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3b82f6, #a855f7);
            border: none;
            border-radius: 40px;
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(59, 130, 246, 0.4);
        }

        .login-btn i {
            font-size: 1.2rem;
        }

        /* Error message */
        .error-message {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 40px;
            padding: 14px 20px;
            margin-top: 24px;
            color: var(--error-text);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%,100%{ transform: translateX(0); }
            20%{ transform: translateX(-5px); }
            40%{ transform: translateX(5px); }
            60%{ transform: translateX(-3px); }
            80%{ transform: translateX(3px); }
        }

        /* Footer */
        .footer {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid var(--input-border);
            display: flex;
            justify-content: center;
            gap: 24px;
        }

        .footer a {
            color: var(--footer-link);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: #3b82f6;
        }

        /* System status badge */
        .system-badge {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .system-badge i {
            color: #22c55e;
            font-size: 0.6rem;
            margin-right: 4px;
        }
    </style>
</head>
<body>
    <!-- Background shapes -->
    <div class="bg-shape shape1"></div>
    <div class="bg-shape shape2"></div>

    <?php
    // Check if theme cookie exists
    $theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
    ?>

    <div class="login-card" id="loginCard">
        <!-- Theme toggle button -->
        <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
            <i class="fas <?php echo $theme === 'dark' ? 'fa-sun' : 'fa-moon'; ?>" id="themeIcon"></i>
        </button>

        <!-- Brand -->
        <div class="brand">
            <i class="fas fa-print printer-icon"></i>
            <h2>PrintMonitor</h2>
        </div>

        <!-- Welcome -->
        <div class="welcome">
            <h3>Welcome back</h3>
            <p>Enter your credentials to access the dashboard</p>
        </div>

        <!-- Login form -->
        <form method="post" action="">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" class="input-field" name="username" placeholder="Username" 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                       required autofocus>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" class="input-field" name="password" placeholder="Password" required>
            </div>

            <div class="options">
                <label class="remember">
                    <input type="checkbox" name="remember"> Remember me
                </label>
                <a href="#" class="forgot-link" onclick="alert('Password reset would be sent to your email'); return false;">Forgot password?</a>
            </div>

            <button type="submit" name="login" class="login-btn" onclick="window.location.href='index.php'" >
                <i class="fas fa-arrow-right-to-bracket"></i> Sign in 
            </button>

            <?php if (!empty($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
        </form>

       
        </div>
    </div>

    <script>
        (function() {
            // Theme management
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            
            // Get current theme from cookie or default to light
            function getTheme() {
                const match = document.cookie.match(/theme=([^;]+)/);
                return match ? match[1] : 'light';
            }

            function setTheme(theme) {
                // Set cookie for 30 days
                document.cookie = `theme=${theme}; path=/; max-age=${60*60*24*30}`;
                // Update data attribute
                document.documentElement.setAttribute('data-theme', theme);
                // Update icon
                if (theme === 'dark') {
                    themeIcon.className = 'fas fa-sun';
                } else {
                    themeIcon.className = 'fas fa-moon';
                }
            }

            // Initialize theme
            const currentTheme = getTheme();
            setTheme(currentTheme);

            // Toggle theme
            themeToggle.addEventListener('click', () => {
                const newTheme = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            });

            // Optional: smooth theme transition
            const loginCard = document.getElementById('loginCard');
            loginCard.style.transition = 'background-color 0.3s ease, border-color 0.3s ease';
        })();
    </script>
</body>
</html>