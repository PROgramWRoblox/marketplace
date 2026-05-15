<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['user_id']) && isset($_POST['id'])) {
    $produkt_id = $_POST['id'];
    $kupujacy_id = $_SESSION['user_id'];

    // Sprawdź czy to nie produkt usera
    $check = $conn->prepare("SELECT user_id FROM produkty WHERE id = ?");
    $check->bind_param("i", $produkt_id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();

    if ($res['user_id'] != $kupujacy_id) {
        // Transakcja
        $conn->query("UPDATE produkty SET status = 'sprzedany' WHERE id = $produkt_id");
        $stmt = $conn->prepare("INSERT INTO zakupy (produkt_id, kupujacy_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $produkt_id, $kupujacy_id);
        $stmt->execute();
    }
}
header("location: index.php");