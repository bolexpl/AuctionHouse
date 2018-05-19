<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$id = $_POST['id'];

try {

    //sprawdzenie transakcji
    $sql = "select count(*) as 'count'
from transactions t
where offer_id in (
  select offer_id
  from offers
  where auction_id = :id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $count = $stmt->fetch()['count'];
    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'Nie można usunąć, gdy są transakcje.']);
        exit();
    }
    $stmt->closeCursor();

    //sprawdzenie produktów
    $sql = "select count(*) as 'count'
from products t
where auction_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $count = $stmt->fetch()['count'];
    if ($count > 0) {
        echo json_encode(['success' => false, 'error' => 'Nie można usunąć, gdy są produkty.']);
        exit();
    }
    $stmt->closeCursor();

    //usuwanie ofert
    $sql = "delete from offers where auction_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();

    //usuwanie aukcji
    $sql = "delete from auctions where auction_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
//    echo $e->getMessage();
    echo json_encode(['success' => false, 'error' => 'Błąd bazy danych.']);
}

