<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'bazar_db';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}
?>