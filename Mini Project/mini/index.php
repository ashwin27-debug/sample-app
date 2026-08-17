<?php
session_start();

if(!isset($_SESSION['user']))
{
header("Location:login.php");
}
?>

<html>
<head>
    <title>Printer Monitoring Dashboard</title>
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
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
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
            padding: 25px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .header h2 {
            font-size: 28px;
            font-weight: 600;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header h2::before {
            content: "🖨️";
            font-size: 32px;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius:5px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid rgba(255,255,255,0.3);
            backdrop-filter: None;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .main-content {
            padding: 30px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 16px;
            color: #555;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .stat-card .stat-value {
            font-size: 32px;
            font-weight: bold;
            color: #667eea;
        }

        .table-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border: none;
        }

        thead tr {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 15px 20px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tbody tr {
            border-bottom: 1px solid #e0e0e0;
            transition: background-color 0.2s ease;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        td {
            padding: 15px 20px;
            color: #555;
        }

        .file-name {
            font-weight: 600;
            color: #333;
        }

        .user-name {
            background: #e8f0fe;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
        }

        .pages-badge {
            background: #667eea;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }

        .time-stamp {
            color: #888;
            font-size: 13px;
        }

        .loading-indicator {
            text-align: center;
            padding: 40px;
            color: #888;
            font-style: italic;
        }

        .loading-indicator::after {
            content: " ⏳";
            display: inline-block;
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: rotate(0deg); }
            50% { opacity: 0.5; transform: rotate(180deg); }
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        .empty-state p {
            font-size: 16px;
            margin-top: 10px;
        }

        /* Refresh Notification */
        .refresh-indicator {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            z-index: 1000;
            opacity: 0;
            transform: translateY(100px);
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            pointer-events: none;
        }

        .refresh-indicator.show {
            opacity: 1;
            transform: translateY(0);
        }

        @media (max-width: 768px) {
            .container {
                border-radius: 10px;
            }

            .header {
                padding: 20px;
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header h2 {
                font-size: 24px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            th, td {
                padding: 12px 15px;
            }

            .refresh-indicator {
                bottom: 20px;
                right: 20px;
                padding: 8px 16px;
                font-size: 12px;
            }
        }
    </style>
    <script>
        let lastUpdateTime = new Date();
        let previousDataHash = '';
        let updateCount = 0;

        function generateDataHash(html) {
            // Simple hash to detect changes
            return html.replace(/\s/g, '');
        }

        function loadData() {
            const xhr = new XMLHttpRequest();
            const dataContainer = document.getElementById("data");
            const refreshIndicator = document.getElementById("refreshIndicator");

            // Show loading state only if empty
            if (dataContainer.children.length === 0) {
                dataContainer.innerHTML = '<tr><td colspan="4" class="loading-indicator">Loading printer data...</td></tr>';
            }

            xhr.open("GET", "fetch_data.php", true);

            xhr.onload = function() {
                if (this.status === 200) {
                    let responseText = this.responseText.trim();
                    const newDataHash = generateDataHash(responseText);
                    
                    // Check if data changed
                    const dataChanged = (newDataHash !== previousDataHash);
                    
                    if (dataChanged) {
                        // Update the table content without animations
                        if (responseText === "" || responseText === " " || responseText === "&nbsp;") {
                            dataContainer.innerHTML = '<tr><td colspan="4" class="empty-state"><div style="font-size: 48px;">🖨️</div><p>No print jobs found</p></td></tr>';
                        } else {
                            dataContainer.innerHTML = responseText;
                        }
                        
                        // Update statistics
                        updateStats();
                        
                        // Show refresh notification
                        updateCount++;
                        lastUpdateTime = new Date();
                        refreshIndicator.innerHTML = `🔄 Updated at ${lastUpdateTime.toLocaleTimeString()} (${updateCount} updates)`;
                        refreshIndicator.classList.add('show');
                        
                        // Hide notification after 2 seconds
                        setTimeout(() => {
                            refreshIndicator.classList.remove('show');
                        }, 2000);
                        
                        // Update hash
                        previousDataHash = newDataHash;
                    }
                } else {
                    dataContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: #e74c3c;">❌ Error loading data. Please check your connection.</td></tr>';
                }
            };

            xhr.onerror = function() {
                dataContainer.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 20px; color: #e74c3c;">❌ Network error. Please refresh the page.</td></tr>';
            };

            xhr.send();
        }

        function updateStats() {
            const rows = document.querySelectorAll('#data tr');
            // Filter out empty state rows
            const validRows = Array.from(rows).filter(row => {
                return !row.querySelector('.empty-state') && row.cells.length === 4;
            });
            
            const totalPrints = validRows.length;
            
            let totalPages = 0;
            validRows.forEach(row => {
                const pagesCell = row.cells[2];
                if (pagesCell) {
                    const pagesText = pagesCell.textContent.replace(/[^0-9]/g, '');
                    totalPages += parseInt(pagesText) || 0;
                }
            });

            // Update stat cards
            document.getElementById('totalPrints').textContent = totalPrints;
            document.getElementById('totalPages').textContent = totalPages;
            
            const avgPages = totalPrints > 0 ? (totalPages / totalPrints).toFixed(1) : 0;
            document.getElementById('avgPages').textContent = avgPages;
        }

        // Manual refresh function with visual feedback
        function manualRefresh() {
            const refreshIndicator = document.getElementById("refreshIndicator");
            refreshIndicator.innerHTML = `🔄 Manual refresh...`;
            refreshIndicator.classList.add('show');
            
            loadData();
            
            setTimeout(() => {
                refreshIndicator.classList.remove('show');
            }, 1500);
        }

        // Set up auto-refresh every 2 seconds
        setInterval(loadData, 2000);

        // Load data on page load
        window.onload = function() {
            loadData();
        };

        // Add keyboard shortcut (Ctrl+R or F5) for manual refresh
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
                e.preventDefault();
                manualRefresh();
            }
        });

        // Add touch pull-to-refresh for mobile
        let touchStart = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStart = e.touches[0].clientY;
        });
        
        document.addEventListener('touchend', function(e) {
            const touchEnd = e.changedTouches[0].clientY;
            const pullDistance = touchEnd - touchStart;
            
            if (window.scrollY === 0 && pullDistance > 100) {
                manualRefresh();
            }
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
        <button class="logout-btn" onclick="window.location.href='reset.php'">Reset</button>
            <h2>Printer Monitoring Dashboard</h2>
            <a href="logout.php" class="logout-btn">🚪 Logout</a>
        </div>
        
        <div class="main-content">
            <!-- Statistics Cards -->
            <div class="stats-container">
                <div class="stat-card">
                    <h3>Total Prints</h3>
                    <div class="stat-value" id="totalPrints">0</div>
                </div>
                <div class="stat-card">
                    <h3>Total Pages</h3>
                    <div class="stat-value" id="totalPages">0</div>
                </div>
                <div class="stat-card">
                    <h3>Avg Pages/Print</h3>
                    <div class="stat-value" id="avgPages">0</div>
                </div>
                <div class="stat-card">
                    <h3>Live Updates</h3>
                    <div class="stat-value" id="updateFrequency">2s</div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>📄 File Name</th>
                            <th>👤 User</th>
                            <th>📊 Pages</th>
                            <th>⏰ Time</th>
                        </tr>
                    </thead>
                    <tbody id="data">
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div style="font-size: 48px;">🖨️</div>
                                <p>Waiting for printer data...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Refresh Notification -->
    <div id="refreshIndicator" class="refresh-indicator"></div>
</body>
</html>