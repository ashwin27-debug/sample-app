<?php
include "db.php";
if (isset($_POST["add_lab"])) {
    $name = trim($_POST["lab_name"]);
    if ($name !== "") { $stmt = mysqli_prepare($conn, "INSERT INTO labs (lab_name) VALUES (?)"); mysqli_stmt_bind_param($stmt, "s", $name); mysqli_stmt_execute($stmt); }
}
$labs = mysqli_query($conn, "SELECT * FROM labs ORDER BY id DESC");
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Labs - SmartLab</title><link rel="stylesheet" href="style.css"></head><body>
<nav><div class="brand">SmartLab <span>Inventory</span></div><a href="index.php">Dashboard</a><a class="active" href="labs.php">Labs</a></nav>
<main><section class="hero compact"><div><p class="eyebrow">LABORATORIES</p><h1>Manage Labs</h1></div></section>
<section class="panel"><form method="post" class="inline-form"><input name="lab_name" placeholder="Enter lab name" required><button name="add_lab" class="btn">Add Lab</button></form></section>
<section class="panel"><h2>Available Labs</h2><table><tr><th>Lab</th><th>Action</th></tr>
<?php while($lab=mysqli_fetch_assoc($labs)): ?><tr><td><?= htmlspecialchars($lab["lab_name"]) ?></td><td><a class="link" href="stocks.php?lab_id=<?= $lab["id"] ?>">View Stock →</a></td></tr><?php endwhile; ?>
</table></section></main></body></html>