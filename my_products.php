<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { 
    header("location: login.php"); exit; 
    }

$uid = $_SESSION['user_id'];
$produkty = $conn->query("SELECT p.*, k.nazwa as kat FROM produkty p LEFT JOIN kategorie k ON p.kategoria_id = k.id WHERE p.user_id = $uid ORDER BY data_dodania DESC");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Moje Produkty</title>
</head>
<body>
    <div class="nav">
        <div class="logo">Bazar<span>PL</span></div>
        <a href="index.php">Strona główna</a>
    </div>

    <h3>Twoje ogłoszenia</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nazwa</th>
                <th>Kategoria</th>
                <th>Cena</th>
                <th>Status</th>
                <th>Opcje</th>
            </tr>
        </thead>
        <tbody>
            <?php while($p = $produkty->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo htmlspecialchars($p['nazwa']); ?></b></td>
                <td><?php echo $p['kat'] ?? 'Inne'; ?></td>
                <td><?php echo $p['cena']; ?> zł</td>
                <td><span class="tag status-<?php echo $p['status']; ?>"><?php echo $p['status']; ?></span></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $p['id']; ?>">Edytuj</a> | 
                    <a href="delete_product.php?id=<?php echo $p['id']; ?>" onclick="return confirm('Usunąć?')">Usuń</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>