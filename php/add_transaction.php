<?php
require_once "../connect.php";

if (!isset($_SESSION['user_id']) || !isset($_POST['offer_id'])) {
    header('Location: ../index.php?' .
        'error=Brak%20dostępu.');
    exit();
}

$id = $_POST['offer_id'];

try {
    $pdo->beginTransaction();

    $sql = "insert into transactions (offer_id) VALUES (:id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->closeCursor();

    $sql = "select auction_id from offers where offer_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $auction = $stmt->fetch()['auction_id'];
    $stmt->closeCursor();

    $sql = "update auctions set completed=1 where auction_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $auction, PDO::PARAM_INT);
    $stmt->execute();

    $pdo->commit();

    header("Location: ../transactions_list.php?&success=Transakcja%20udana.");

} catch (PDOException $e) {
    echo $e->getMessage();
    header("Location: ../index.php?error=Błąd%20bazy%20danych.");
}

