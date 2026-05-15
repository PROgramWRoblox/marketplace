<?php
require_once 'db.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $haslo = password_hash($_POST['haslo'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO uzytkownicy (login, email, haslo) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $login, $email, $haslo);
    
    if ($stmt->execute()) {
        header("location: login.php");
    } else {
        echo "Błąd rejestracji.";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Rejestracja - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<form method="post">
    <input type="text" name="login" placeholder="Login" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="haslo" placeholder="Hasło" required>
    <button type="submit">Zarejestruj się</button>
</form>