<?php
include "db.php";
$id=intval($_GET["id"]??0); $lab_id=intval($_GET["lab_id"]??0);
$stmt=mysqli_prepare($conn,"DELETE FROM stocks WHERE id=?"); mysqli_stmt_bind_param($stmt,"i",$id); mysqli_stmt_execute($stmt);
header("Location: stocks.php?lab_id=$lab_id"); exit;
?>