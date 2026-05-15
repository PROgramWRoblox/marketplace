<?php
session_start();
require_once 'db.php';
if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $id = intval($_GET['id']);
    $uid = $_SESSION['user_id'];
    $conn->query("DELETE FROM produkty WHERE id = $id AND user_id = $uid");
}
header("location: my_products.php");