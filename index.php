<?php
include "db.php";
$labCount = 0; $stockCount = 0; $lowStock = 0;
$r = mysqli_query($conn, "SELECT COUNT(*) c FROM labs"); if ($r) $labCount = mysqli_fetch_assoc($r)["c"];
$r = mysqli_query($conn, "SELECT COUNT(*) c FROM stocks"); if ($r) $stockCount = mysqli_fetch_assoc($r)["c"];
$r = mysqli_query($conn, "SELECT COUNT(*) c FROM stocks WHERE quantity <= 5"); if ($r) $lowStock = mysqli_fetch_assoc($r)["c"];
?>
<!DOCTYPE html>
<html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartLab Inventory</title><link rel="stylesheet" href="style.css"></head>
<body>
<nav><div class="brand">SmartLab <span>Inventory</span></div><a class="active" href="index.php">Dashboard</a><a href="labs.php">Labs</a></nav>
<main><section class="hero"><div><p class="eyebrow">SMART LAB MANAGEMENT</p><h1>Inventory Dashboard</h1><p>Manage laboratory stock quickly and efficiently.</p></div><a class="btn" href="labs.php">View Labs →</a></section>
<section class="cards">
<div class="card"><small>Total Labs</small><strong><?= $labCount ?></strong></div>
<div class="card"><small>Total Stock Items</small><strong><?= $stockCount ?></strong></div>
<div class="card warning"><small>Low Stock Items</small><strong><?= $lowStock ?></strong></div>
</section>
<section class="panel"><h2>Quick Actions</h2><div class="actions"><a href="labs.php">🏫 Manage Labs</a><a href="labs.php">📦 Manage Inventory</a></div></section>
</main><script src="script.js"></script></body></html>