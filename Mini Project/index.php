<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost","root","","printer_monitor");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// TOTAL JOBS
$totalJobs = 0;
$q1 = $conn->query("SELECT COUNT(*) as total FROM print_logs");
if($q1 && $row = $q1->fetch_assoc()){
    $totalJobs = $row['total'];
}

// TOTAL PAGES
$totalPages = 0;
$q2 = $conn->query("SELECT SUM(pages) as total FROM print_logs");
if($q2 && $row = $q2->fetch_assoc()){
    $totalPages = $row['total'] ?? 0;
}

// ACTIVE USERS
$totalUsers = 0;
$q3 = $conn->query("SELECT COUNT(DISTINCT user) as total FROM print_logs");
if($q3 && $row = $q3->fetch_assoc()){
    $totalUsers = $row['total'];
}

// SUCCESS PRINTS
$successPrints = 0;
$q4 = $conn->query("SELECT COUNT(*) as total FROM print_logs WHERE status='success'");
if($q4 && $row = $q4->fetch_assoc()){
    $successPrints = $row['total'];
}

// FAILED PRINTS
$failedPrints = 0;
$q5 = $conn->query("SELECT COUNT(*) as total FROM print_logs WHERE status='failed'");
if($q5 && $row = $q5->fetch_assoc()){
    $failedPrints = $row['total'];
}
?>

<html>

<head>
<title>Printer Monitoring Dashboard</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<script>
// Live table refresh
function loadData(){
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_data.php", true);
    xhr.onload = function(){
        document.getElementById("data").innerHTML = this.responseText;
    };
    xhr.send();
}
setInterval(loadData, 2000);
window.onload = loadData;
</script>

<style>
body{ background:#f4f6f9; }
.header-bar{
    background:linear-gradient(45deg,#007bff,#00c6ff);
    color:white;
    padding:15px;
    border-radius:10px;
}
.card-stat{
    border:none;
    border-radius:10px;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
    margin-bottom:20px;
}
</style>

</head>

<body>
<div class="container mt-4">

<!-- HEADER -->
<div class="header-bar d-flex justify-content-between align-items-center">
    <h4><i class="bi bi-printer"></i> Printer Monitoring Dashboard</h4>
    <div>
        <span class="badge bg-light text-dark">
            <i class="bi bi-circle-fill text-success"></i> Live
        </span>
        <a href="logout.php" class="btn btn-light btn-sm ms-3">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<!-- STATS -->
<div class="row mt-4">
    <div class="col-md-2">
        <div class="card card-stat p-3">
            <h6>Total Jobs</h6>
            <h3 class="text-primary"><?php echo $totalJobs; ?></h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stat p-3">
            <h6>Total Pages</h6>
            <h3 class="text-success"><?php echo $totalPages; ?></h3>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stat p-3">
            <h6>Active Users</h6>
            <h3 class="text-danger"><?php echo $totalUsers; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3">
            <h6>Success Prints</h6>
            <h3 class="text-success"><?php echo $successPrints; ?></h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3">
            <h6>Failed Prints</h6>
            <h3 class="text-danger"><?php echo $failedPrints; ?></h3>
        </div>
    </div>
</div>

<!-- PRINT JOBS TABLE -->
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-dark text-white">
        <i class="bi bi-list"></i> Print Jobs
    </div>
    <div class="card-body">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>File Name</th>
                    <th>User</th>
                    <th>Pages</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="data"></tbody>
        </table>
    </div>
</div>

</div>
</body>
</html>