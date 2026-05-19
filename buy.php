<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['user_id']) && isset($_POST['id'])) {
    $produkt_id = intval($_POST['id']);
    $kupujacy_id = $_SESSION['user_id'];

    $conn->begin_transaction();

    try {
        // Blokada race condition: zmiana statusu tylko gdy produkt jest 'dostepny' i nie należy do kupującego
        $stmt = $conn->prepare("UPDATE produkty SET status = 'sprzedany' WHERE id = ? AND status = 'dostepny' AND user_id != ?");
        $stmt->bind_param("ii", $produkt_id, $kupujacy_id);
        $stmt->execute();

        if ($conn->affected_rows > 0) {
            $stmt = $conn->prepare("INSERT INTO zakupy (produkt_id, kupujacy_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $produkt_id, $kupujacy_id);
            $stmt->execute();
            
            $conn->commit();
        } else {
            $conn->rollback();
        }
    } catch (Exception $e) {
        $conn->rollback();
    }
}
header("location: index.php");
exit;
?>