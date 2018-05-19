<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id'])
    || !isset($_POST['price'])
    || !isset($_POST['customer_id'])
    || !isset($_POST['auction_id'])) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$price = htmlentities($_POST['price']);
$customer = $_POST['customer_id'];
$auction = $_POST['auction_id'];

try {

    $sql = "insert into offers (auction_id, price, customer_id) values (:auction, :price, :customer)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':auction', $auction, PDO::PARAM_INT);
    $stmt->bindValue(':price', $price, PDO::PARAM_INT);
    $stmt->bindValue(':customer', $customer, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy danych.']);
}

