<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']))  { 
    header("location: login.php"); exit; 
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nazwa = trim($_POST['nazwa']);
    $opis = trim($_POST['opis']);
    $cena = floatval($_POST['cena']);
    $kategoria_id = intval($_POST['kategoria_id']);
    $user_id = $_SESSION['user_id'];

    if (empty($nazwa) || empty($opis)) {
        $error = "Nazwa i opis nie mogą być puste.";
    } elseif ($cena <= 0) {
        $error = "Cena musi być większa niż 0 zł.";
    } else {
        $stmt = $conn->prepare("INSERT INTO produkty (nazwa, opis, cena, user_id, kategoria_id, status) VALUES (?, ?, ?, ?, ?, 'dostepny')");
        $stmt->bind_param("ssdii", $nazwa, $opis, $cena, $user_id, $kategoria_id);
        $stmt->execute();
        header("location: index.php");
        exit;
    }
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
        <?php if($error): ?>
            <div style="color: #c62828; margin-bottom: 15px;"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
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
                <input type="number" step="0.01" name="cena" min="0.01" required>
            </div>
            <div class="form-group">
                <label>Kategoria:</label>
                <select name="kategoria_id">
                    <?php while($k = $kategorie->fetch_assoc()): ?>
                        <option value="<?php echo $k['id']; ?>"><?php echo htmlspecialchars($k['nazwa']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Dodaj ogłoszenie</button>
        </form>
    </div>
</body>
</html>