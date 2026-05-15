<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $haslo = $_POST['haslo'];

    $sql = "SELECT id, login, haslo FROM uzytkownicy WHERE login = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($haslo, $user['haslo'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            header("location: index.php");
        } else { echo "Błędne hasło."; }
    } else { echo "Nie ma takiego użytkownika."; }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Logowanie - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<form method="post">
    <input type="text" name="login" placeholder="Login" required>
    <input type="password" name="haslo" placeholder="Hasło" required>
    <button type="submit">Zaloguj się</button>
</form>