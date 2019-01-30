<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['name']) || !isset($_POST['auction_id'])) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$name = htmlentities($_POST['name']);
$auction = $_POST['auction_id'];

try {
    $sql = "insert into products (name, auction_id) values (:name,:auction_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':auction_id', $auction, PDO::PARAM_INT);
    $stmt->execute();

    $stmt->closeCursor();

    $sql = "select product_id from products where name = :name and auction_id = :auction_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':auction_id', $auction, PDO::PARAM_INT);
    $stmt->execute();

    $id = $stmt->fetch()['product_id'];

    echo json_encode(['success' => true, 'id' => $id]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy danych.']);
}

