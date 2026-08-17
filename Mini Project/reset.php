<?php
session_start();

$conn = new mysqli("localhost","root","","printer_monitor");

if(isset($_POST['reset']))
{
    $conn->query("DELETE FROM print_logs");
    echo "<script>alert('Print count reset successfully'); window.location='index.php';</script>";
}
?>

<html>
<body>

<h3>Reset Print Count</h3>

<form method="post">
<button name="reset">Reset All Print Logs</button>
</form>

<br>

<a href="index.php">Back to Dashboard</a>

</body>
</html>