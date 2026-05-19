<?php
session_start();
require_once 'db.php';

// Zmiana z GET na POST ze względów bezpieczeństwa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_SESSION['user_id'])) {
    $id = intval($_POST['id']);
    $uid = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("DELETE FROM produkty WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $uid);
    $stmt->execute();
}
header("location: my_products.php");
exit;
?>