<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { 
    header("location: login.php"); exit; 
    }

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT * FROM produkty WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) { 
    header("location: my_products.php"); exit; 
    }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $upd = $conn->prepare("UPDATE produkty SET nazwa=?, opis=?, cena=?, kategoria_id=? WHERE id=? AND user_id=?");
    $upd->bind_param("ssdiii", $_POST['nazwa'], $_POST['opis'], $_POST['cena'], $_POST['kategoria_id'], $id, $user_id);
    $upd->execute();
    header("location: my_products.php");
    exit;
}
$kategorie = $conn->query("SELECT * FROM kategorie");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Edytuj produkt</title>
</head>
<body>
    <div class="nav">
        <div class="logo">Bazar<span>PL</span></div>
        <a href="my_products.php">Anuluj</a>
    </div>

    <div class="form-container">
        <h3>Edytuj ofertę</h3>
        <form method="post">
            <div class="form-group">
                <label>Nazwa:</label>
                <input type="text" name="nazwa" value="<?php echo htmlspecialchars($product['nazwa']); ?>" required>
            </div>
            <div class="form-group">
                <label>Opis:</label>
                <textarea name="opis" required><?php echo htmlspecialchars($product['opis']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Cena (PLN):</label>
                <input type="number" step="0.01" name="cena" value="<?php echo $product['cena']; ?>" required>
            </div>
            <div class="form-group">
                <label>Kategoria:</label>
                <select name="kategoria_id">
                    <?php while($k = $kategorie->fetch_assoc()): ?>
                        <option value="<?php echo $k['id']; ?>" <?php echo ($k['id'] == $product['kategoria_id']) ? 'selected' : ''; ?>>
                            <?php echo $k['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit">Zapisz zmiany</button>
        </form>
    </div>
</body>
</html>