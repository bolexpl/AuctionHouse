<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['id']) || !isset($_POST['name'])) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$id = $_POST['id'];
$name = htmlentities($_POST['name']);

try {
    $sql = "update products set name=:name where product_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    echo json_encode(['success' => true]);


} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy danych.']);
}
