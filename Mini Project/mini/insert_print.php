<?php

$conn = new mysqli("localhost","root","","printer_monitor");

if(isset($_GET['doc']))
{
    $doc = $_GET['doc'];
}
else
{
    $doc = "Unknown";
}

$conn->query("INSERT INTO print_logs (document_name,user_name,pages)
VALUES ('$doc','LabUser',1)");

echo "Inserted";

?>