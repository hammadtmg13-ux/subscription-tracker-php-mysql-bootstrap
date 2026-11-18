<?php
require 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE subscriptions SET active = NOT active WHERE id = ?");
    $stmt->execute([$id]);
}

header('Location: index.php');
exit;
?>
