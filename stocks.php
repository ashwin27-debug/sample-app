<?php
include "db.php";
$lab_id = intval($_GET["lab_id"] ?? 0);
$r = mysqli_query($conn, "SELECT * FROM labs WHERE id=$lab_id"); $lab = mysqli_fetch_assoc($r);
if (!$lab) die("Lab not found.");
if (isset($_POST["add"])) {
    $item=trim($_POST["item_name"]); $qty=intval($_POST["quantity"]);
    $stmt=mysqli_prepare($conn,"INSERT INTO stocks(lab_id,item_name,quantity) VALUES(?,?,?)"); mysqli_stmt_bind_param($stmt,"isi",$lab_id,$item,$qty); mysqli_stmt_execute($stmt);
}
$search = trim($_GET["search"] ?? "");
if ($search !== "") {
    $safe=mysqli_real_escape_string($conn,$search);
    $stocks=mysqli_query($conn,"SELECT * FROM stocks WHERE lab_id=$lab_id AND item_name LIKE '%$safe%' ORDER BY id DESC");
} else $stocks=mysqli_query($conn,"SELECT * FROM stocks WHERE lab_id=$lab_id ORDER BY id DESC");
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($lab["lab_name"]) ?> - SmartLab</title><link rel="stylesheet" href="style.css"></head><body>
<nav><div class="brand">SmartLab <span>Inventory</span></div><a href="index.php">Dashboard</a><a href="labs.php">Labs</a></nav>
<main><section class="hero compact"><div><p class="eyebrow">INVENTORY</p><h1><?= htmlspecialchars($lab["lab_name"]) ?></h1></div><a class="btn" href="labs.php">← Labs</a></section>
<section class="panel"><h2>Add Stock</h2><form method="post" class="inline-form"><input name="item_name" placeholder="Item name" required><input type="number" min="0" name="quantity" placeholder="Quantity" required><button class="btn" name="add">Add Stock</button></form></section>
<section class="panel"><div class="panel-head"><h2>Stock Items</h2><form><input type="hidden" name="lab_id" value="<?= $lab_id ?>"><input name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..."></form></div>
<table><tr><th>Item</th><th>Quantity</th><th>Status</th><th>Actions</th></tr>
<?php while($s=mysqli_fetch_assoc($stocks)): ?><tr><td><?= htmlspecialchars($s["item_name"]) ?></td><td><?= $s["quantity"] ?></td><td><span class="badge <?= $s["quantity"]<=5?"low":"ok" ?>"><?= $s["quantity"]<=5?"Low Stock":"Available" ?></span></td><td><a class="link" href="edit.php?id=<?= $s["id"] ?>&lab_id=<?= $lab_id ?>">Edit</a> · <a class="danger" onclick="return confirm('Delete this item?')" href="delete.php?id=<?= $s["id"] ?>&lab_id=<?= $lab_id ?>">Delete</a></td></tr><?php endwhile; ?>
</table></section></main></body></html>