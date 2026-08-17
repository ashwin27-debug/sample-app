<?php
include "db.php";
$id=intval($_GET["id"]??0); $lab_id=intval($_GET["lab_id"]??0);
if(isset($_POST["update"])){ $item=trim($_POST["item_name"]); $qty=intval($_POST["quantity"]); $stmt=mysqli_prepare($conn,"UPDATE stocks SET item_name=?,quantity=? WHERE id=?"); mysqli_stmt_bind_param($stmt,"sii",$item,$qty,$id); mysqli_stmt_execute($stmt); header("Location: stocks.php?lab_id=$lab_id"); exit; }
$r=mysqli_query($conn,"SELECT * FROM stocks WHERE id=$id"); $row=mysqli_fetch_assoc($r); if(!$row) die("Stock item not found.");
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Edit - SmartLab</title><link rel="stylesheet" href="style.css"></head>
<body><nav><div class="brand">SmartLab <span>Inventory</span></div><a href="index.php">Dashboard</a><a href="labs.php">Labs</a></nav><main>
<section class="panel form-panel"><p class="eyebrow">UPDATE STOCK</p><h1>Edit Item</h1><form method="post"><input name="item_name" value="<?= htmlspecialchars($row["item_name"]) ?>" required><input type="number" min="0" name="quantity" value="<?= $row["quantity"] ?>" required><button class="btn" name="update">Update</button><a href="stocks.php?lab_id=<?= $lab_id ?>">Cancel</a></form></section></main></body></html>