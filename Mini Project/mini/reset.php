<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['user']))
{
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","printer_monitor");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['reset']))
{
    $conn->query("DELETE FROM print_logs");
    echo "<script>
        alert('Print count reset successfully'); 
        window.location='index.php';
    </script>";
}
?>

<html>
<head>
    <title>Reset Print Logs - Printer Monitor</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background bubbles */
        .bubble {
            position: fixed;
            bottom: -100px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            pointer-events: none;
            animation: rise 20s infinite ease-in;
            z-index: 0;
        }

        @keyframes rise {
            0% {
                transform: translateY(0) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(-1200px) rotate(720deg);
                opacity: 0;
            }
        }

        /* Create multiple bubbles */
        .bubble:nth-child(1) { width: 80px; height: 80px; left: 10%; animation-duration: 18s; animation-delay: 0s; }
        .bubble:nth-child(2) { width: 120px; height: 120px; left: 20%; animation-duration: 22s; animation-delay: 2s; }
        .bubble:nth-child(3) { width: 60px; height: 60px; left: 35%; animation-duration: 15s; animation-delay: 4s; }
        .bubble:nth-child(4) { width: 100px; height: 100px; left: 50%; animation-duration: 25s; animation-delay: 1s; }
        .bubble:nth-child(5) { width: 40px; height: 40px; left: 65%; animation-duration: 12s; animation-delay: 3s; }
        .bubble:nth-child(6) { width: 90px; height: 90px; left: 80%; animation-duration: 20s; animation-delay: 5s; }
        .bubble:nth-child(7) { width: 70px; height: 70px; left: 45%; animation-duration: 17s; animation-delay: 2.5s; }
        .bubble:nth-child(8) { width: 110px; height: 110px; left: 75%; animation-duration: 23s; animation-delay: 3.5s; }
        .bubble:nth-child(9) { width: 50px; height: 50px; left: 15%; animation-duration: 14s; animation-delay: 1.5s; }
        .bubble:nth-child(10) { width: 85px; height: 85px; left: 90%; animation-duration: 19s; animation-delay: 4.5s; }

        .container {
            max-width: 550px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1;
            position: relative;
        }

        @keyframes slideUp {
            from {
                transform: translateY(100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1%, transparent 1%);
            background-size: 50px 50px;
            animation: shimmer 20s linear infinite;
        }

        @keyframes shimmer {
            from {
                transform: translate(0, 0);
            }
            to {
                transform: translate(50px, 50px);
            }
        }

        .header-icon {
            font-size: 70px;
            margin-bottom: 15px;
            display: inline-block;
            animation: shake 0.5s ease-in-out 3;
        }

        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 35px;
            background: white;
        }

        .warning-box {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe8e8 100%);
            border-left: 4px solid #e74c3c;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            animation: pulseWarning 2s ease-in-out infinite;
        }

        @keyframes pulseWarning {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(231, 76, 60, 0.4);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(231, 76, 60, 0);
            }
        }

        .warning-box h3 {
            color: #e74c3c;
            font-size: 20px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .warning-box p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .stats-preview {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e0e0e0;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: #666;
            font-weight: 500;
        }

        .stat-value {
            color: #667eea;
            font-weight: bold;
            font-size: 18px;
        }

        .reset-form {
            margin-bottom: 25px;
        }

        .reset-btn {
            width: 100%;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            border: none;
            padding: 16px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            position: relative;
            overflow: hidden;
        }

        .reset-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .reset-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .reset-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(231, 76, 60, 0.4);
        }

        .reset-btn:active {
            transform: translateY(0);
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
            text-decoration: none;
            padding: 14px;
            font-size: 16px;
            font-weight: 500;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }

        .divider::before,
        .divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 45%;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before {
            left: 0;
        }

        .divider::after {
            right: 0;
        }

        .divider span {
            background: white;
            padding: 0 10px;
            color: #999;
            font-size: 14px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            animation: modalSlideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes modalSlideIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .modal-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .modal h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 24px;
        }

        .modal p {
            color: #666;
            margin-bottom: 25px;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .modal-btn {
            padding: 10px 25px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .modal-btn.confirm {
            background: #e74c3c;
            color: white;
        }

        .modal-btn.confirm:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .modal-btn.cancel {
            background: #95a5a6;
            color: white;
        }

        .modal-btn.cancel:hover {
            background: #7f8c8d;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .content {
                padding: 30px 20px;
            }
            
            .header h2 {
                font-size: 26px;
            }
            
            .header-icon {
                font-size: 50px;
            }
            
            .warning-box h3 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <!-- Animated Background Bubbles -->
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>
    <div class="bubble"></div>

    <div class="container">
        <div class="header">
            <div class="header-icon">⚠️</div>
            <h2>Reset Print Logs</h2>
            <p>This action will permanently delete all print history</p>
        </div>

        <div class="content">
            <!-- Warning Box -->
           

            <?php
            // Fetch current statistics
            $total_logs = $conn->query("SELECT COUNT(*) as count FROM print_logs")->fetch_assoc()['count'];
            $total_pages = $conn->query("SELECT SUM(pages) as total FROM print_logs")->fetch_assoc()['total'];
            
            if($total_logs > 0):
            ?>
            <div class="stats-preview">
                <h3 style="margin-bottom: 15px; color: #333;">Current Statistics</h3>
                <div class="stat-item">
                    <span class="stat-label">Total Print Jobs</span>
                    <span class="stat-value"><?php echo $total_logs; ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Total Pages Printed</span>
                    <span class="stat-value"><?php echo $total_pages ?: 0; ?></span>
                </div>
            </div>
            <?php else: ?>
            <div class="stats-preview">
                <div style="text-align: center; color: #999; padding: 20px;">
                    📭 No print logs available to reset
                </div>
            </div>
            <?php endif; ?>

            <!-- Reset Form with Modal Trigger -->
            <form id="resetForm" method="post" style="display: inline-block; width: 100%;">
                <button type="button" class="reset-btn" id="showModalBtn" <?php echo $total_logs == 0 ? 'disabled style="opacity: 0.5; cursor: not-allowed;"' : ''; ?>>
                    <span>🗑️</span> Reset All Print Logs
                </button>
                <input type="hidden" name="reset" id="resetInput" value="1">
            </form>

            <div class="divider">
                <span>or</span>
            </div>

            <a href="index.php" class="back-btn">
                <span>←</span> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-icon">⚠️</div>
            <h3>Confirm Reset</h3>
            <p>Are you sure you want to reset all print logs? This action cannot be undone and will permanently delete <?php echo $total_logs; ?> print job record(s).</p>
            <div class="modal-buttons">
                <button class="modal-btn cancel" onclick="closeModal()">Cancel</button>
                <button class="modal-btn confirm" onclick="confirmReset()">Yes, Reset All</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('confirmationModal');
        const showModalBtn = document.getElementById('showModalBtn');
        const resetForm = document.getElementById('resetForm');
        const resetInput = document.getElementById('resetInput');

        function showModal() {
            modal.style.display = 'flex';
            // Add animation to modal
            modal.style.animation = 'fadeIn 0.3s ease';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        function confirmReset() {
            // Submit the form
            resetInput.value = '1';
            resetForm.submit();
            
            // Add loading state to button
            const confirmBtn = document.querySelector('.modal-btn.confirm');
            confirmBtn.textContent = 'Resetting...';
            confirmBtn.disabled = true;
        }

        // Show modal when reset button is clicked
        if(showModalBtn) {
            showModalBtn.addEventListener('click', function(e) {
                e.preventDefault();
                showModal();
            });
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Prevent accidental form submission
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            }
        });

        // Add hover animation to stats
        const statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(5px)';
                this.style.transition = 'all 0.3s ease';
            });
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    </script>
</body>
</html>