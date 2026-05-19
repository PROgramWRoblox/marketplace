<?php
session_start();
require_once 'db.php';

$search = $_GET['search'] ?? '';
$kat_filter = isset($_GET['kategoria']) && $_GET['kategoria'] !== '' ? intval($_GET['kategoria']) : null;

$stats = $conn->query("SELECT 
    (SELECT COUNT(*) FROM produkty) as wszystkie,
    (SELECT COUNT(*) FROM produkty WHERE status='dostepny') as dostepne,
    (SELECT COUNT(*) FROM produkty WHERE status='sprzedany') as sprzedane
")->fetch_assoc();

$sql = "SELECT p.*, u.login as sprzedawca, k.nazwa as kategoria_nazwa 
        FROM produkty p 
        JOIN uzytkownicy u ON p.user_id = u.id 
        LEFT JOIN kategorie k ON p.kategoria_id = k.id 
        WHERE p.nazwa LIKE ?";

if ($kat_filter !== null) { 
    $sql .= " AND p.kategoria_id = ?"; 
}
$sql .= " ORDER BY data_dodania DESC";

$stmt = $conn->prepare($sql);
$s = "%$search%";

if ($kat_filter !== null) {
    $stmt->bind_param("si", $s, $kat_filter);
} else {
    $stmt->bind_param("s", $s);
}

$stmt->execute();
$produkty = $stmt->get_result();
$kategorie = $conn->query("SELECT * FROM kategorie");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>BazarPL - Strona Główna</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="nav">
        <div class="logo">Bazar<span>PL</span></div>
        <div class="nav-links">
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="add_product.php">Dodaj produkt</a>
                <a href="my_products.php">Moje produkty</a>
                <a href="history.php">Historia</a>
                <span class="user-badge"><?php echo htmlspecialchars($_SESSION['login']); ?></span>
                <a href="logout.php">Wyloguj</a>
            <?php else: ?>
                <a href="login.php">Logowanie</a>
                <a href="register.php" class="btn-register">Rejestracja</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="stats">
        <div><h2><?php echo $stats['wszystkie']; ?></h2>Wszystkich</div>
        <div><h2><?php echo $stats['dostepne']; ?></h2>Dostępnych</div>
        <div><h2><?php echo $stats['sprzedane']; ?></h2>Sprzedanych</div>
    </div>

    <div class="filter-box">
        <h3>Filtruj ofertę</h3>
        <form method="get" class="filter-form">
            <input type="text" name="search" placeholder="Szukaj po nazwie..." value="<?php echo htmlspecialchars($search); ?>">
            <select name="kategoria">
                <option value="">Wszystkie kategorie</option>
                <?php while($k = $kategorie->fetch_assoc()): ?>
                    <option value="<?php echo $k['id']; ?>" <?php if($kat_filter == $k['id']) echo 'selected'; ?>><?php echo htmlspecialchars($k['nazwa']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Szukaj</button>
        </form>
    </div>

    <div class="grid">
        <?php while($p = $produkty->fetch_assoc()): ?>
            <div class="card">
                <span class="tag tag-category"><?php echo htmlspecialchars($p['kategoria_nazwa'] ?? 'Inne'); ?></span>
                <h4><?php echo htmlspecialchars($p['nazwa']); ?></h4>
                <p><?php echo htmlspecialchars(mb_strimwidth($p['opis'], 0, 80, "...")); ?></p>
                <strong><?php echo $p['cena']; ?> zł</strong>
                <small>Sprzedawca: <?php echo htmlspecialchars($p['sprzedawca']); ?></small>
                
                <div class="card-actions" style="margin-top: 15px;">
                    <?php if($p['status'] == 'sprzedany'): ?>
                        <button disabled>Sprzedany</button>
                    <?php elseif(!isset($_SESSION['user_id'])): ?>
                        <a href="login.php"><button class="btn-outline" style="width:100%;">Zaloguj się</button></a>
                    <?php elseif($p['user_id'] == $_SESSION['user_id']): ?>
                        <button disabled style="width:100%;">Twoja oferta</button>
                    <?php else: ?>
                        <form action="buy.php" method="post">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="btn-buy">Kup teraz</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>