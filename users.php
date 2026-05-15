<?php
require_once 'db.php';
$uzytkownicy = $conn->query("SELECT login, email FROM uzytkownicy");
?>
<head>
    <meta charset="UTF-8">
    <title>Użytkownicy - BazarPL</title>
    <link rel="stylesheet" href="style.css">
</head>
<h3>Zarejestrowani użytkownicy</h3>
<ul>
    <?php while($u = $uzytkownicy->fetch_assoc()): ?>
        <li><strong><?php echo $u['login']; ?></strong> (<?php echo $u['email']; ?>)</li>
    <?php endwhile; ?>
</ul>
<a href="index.php">Powrót</a>