<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['product_id'])) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$id = $_POST['product_id'];

try {
    $sql = "delete from products where product_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy danych.']);
}

