<?php
require_once 'db.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = trim($_POST['login']);
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = $_POST['haslo'];

    if (empty($login) || strlen($login) < 3) {
        $error = "Login musi mieć minimum 3 znaki.";
    } elseif (!$email) {
        $error = "Podaj poprawny adres e-mail.";
    } elseif (strlen($password) < 6) {
        $error = "Hasło musi mieć minimum 6 znaków.";
    } else {
        // Walidacja unikalności w bazie
        $stmt = $conn->prepare("SELECT id FROM uzytkownicy WHERE login = ? OR email = ?");
        $stmt->bind_param("ss", $login, $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = "Ten login lub email jest już zajęty.";
        } else {
            $haslo_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO uzytkownicy (login, email, haslo) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $login, $email, $haslo_hash);
            
            if ($stmt->execute()) {
                header("location: login.php");
                exit;
            } else {
                $error = "Wystąpił błąd podczas rejestracji.";
            }
        }
    }
}
?>
<head>
    <meta charset="UTF-8">
    <title>Rejestracja - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<div class="form-container" style="max-width: 400px; margin: 50px auto;">
    <h3>Zarejestruj się</h3>
    <?php if($error): ?>
        <div style="color: #c62828; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="text" name="login" placeholder="Login" value="<?php echo isset($login) ? htmlspecialchars($login) : ''; ?>" required>
        <input type="email" name="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
        <input type="password" name="haslo" placeholder="Hasło" required>
        <button type="submit">Zarejestruj się</button>
    </form>
</div>