<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) { 
    header("location: login.php"); exit; 
    }

$uid = $_SESSION['user_id'];
$sql = "SELECT p.nazwa, p.cena, z.data_zakupu, u.login as sprzedawca 
        FROM zakupy z 
        JOIN produkty p ON z.produkt_id = p.id 
        JOIN uzytkownicy u ON p.user_id = u.id 
        WHERE z.kupujacy_id = $uid ORDER BY z.data_zakupu DESC";
$historia = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Historia Zakupów - BazarPL</title>
    <style>
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; border: 1px solid #eee; }
        th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; }
    </style>
</head>
<body>
    <div class="nav">
        <strong>Bazar<span>PL</span></strong>
        <a href="index.php">Powrót</a>
    </div>

    <h3>Twoje zakupy</h3>
    <?php if($historia->num_rows > 0): ?>
    <table>
        <tr>
            <th>Produkt</th>
            <th>Cena</th>
            <th>Sprzedawca</th>
            <th>Data zakupu</th>
        </tr>
        <?php while($h = $historia->fetch_assoc()): ?>
        <tr>
            <td><b><?php echo htmlspecialchars($h['nazwa']); ?></b></td>
            <td><?php echo $h['cena']; ?> zł</td>
            <td><?php echo $h['sprzedawca']; ?></td>
            <td><?php echo $h['data_zakupu']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
        <p>Nie dokonałeś jeszcze żadnych zakupów.</p>
    <?php endif; ?>
</body>
</html>