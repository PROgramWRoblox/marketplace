<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']))  { 
        header("location: login.php"); exit; 
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("INSERT INTO produkty (nazwa, opis, cena, user_id, kategoria_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdii", $_POST['nazwa'], $_POST['opis'], $_POST['cena'], $_SESSION['user_id'], $_POST['kategoria_id']);
    $stmt->execute();
    header("location: index.php");
}
$kategorie = $conn->query("SELECT * FROM kategorie");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Dodaj produkt</title>
</head>
<body>
    <div class="nav">
        <div class="logo">Bazar<span>PL</span></div>
        <a href="index.php">Powrót</a>
    </div>

    <div class="form-container">
        <h3>Wystaw nowy przedmiot</h3>
        <form method="post">
            <div class="form-group">
                <label>Nazwa:</label>
                <input type="text" name="nazwa" required>
            </div>
            <div class="form-group">
                <label>Opis:</label>
                <textarea name="opis" required></textarea>
            </div>
            <div class="form-group">
                <label>Cena (PLN):</label>
                <input type="number" step="0.01" name="cena" required>
            </div>
            <div class="form-group">
                <label>Kategoria:</label>
                <select name="kategoria_id">
                    <?php while($k = $kategorie->fetch_assoc()): ?>
                        <option value="<?php echo $k['id']; ?>"><?php echo $k['nazwa']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Dodaj ogłoszenie</button>
        </form>
    </div>
</body>
</html>