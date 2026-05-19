<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { 
    header("location: login.php"); exit; 
}

$uid = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT p.*, k.nazwa as kat FROM produkty p LEFT JOIN kategorie k ON p.kategoria_id = k.id WHERE p.user_id = ? ORDER BY data_dodania DESC");
$stmt->bind_param("i", $uid);
$stmt->execute();
$produkty = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Moje Produkty</title>
    <style>
        .actions-cell { display: flex; gap: 10px; align-items: center; }
        .btn-link-delete { background: none; color: #c62828; padding: 0; border: none; font-weight: normal; font-family: inherit; font-size: inherit; cursor: pointer; text-decoration: underline; }
        .btn-link-delete:hover { color: #b71c1c; background: none; }
    </style>
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
                <td><?php echo htmlspecialchars($p['kat'] ?? 'Inne'); ?></td>
                <td><?php echo $p['cena']; ?> zł</td>
                <td><span class="tag status-<?php echo htmlspecialchars($p['status']); ?>"><?php echo htmlspecialchars($p['status']); ?></span></td>
                <td>
                    <div class="actions-cell">
                        <a href="edit_product.php?id=<?php echo $p['id']; ?>">Edytuj</a> | 
                        <form action="delete_product.php" method="post" onsubmit="return confirm('Usunąć?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="btn-link-delete">Usuń</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>