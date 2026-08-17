<?php

$conn=new mysqli("localhost","root","","printer_monitor");

$result=$conn->query("SELECT * FROM print_logs ORDER BY created_at DESC");

while($row=$result->fetch_assoc())
{

echo "<tr>";

echo "<td>".$row['document_name']."</td>";

echo "<td>".$row['user_name']."</td>";

echo "<td>".$row['pages']."</td>";

echo "<td>".$row['created_at']."</td>";

echo "</tr>";

}

?>