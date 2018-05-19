<?php

require_once "../connect.php";

if (!isset($_POST['id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$id = htmlentities($_POST['id']);

try {

    $sql = "select count(*) as 'count'
            from auctions a
            inner join offers o on a.auction_id = o.auction_id
            where user_id = :user or customer_id = :customer;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user', $id, PDO::PARAM_INT);
    $stmt->bindValue(':customer', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetch()['count'] != 0) {
        echo json_encode(['success' => false, 'error' => 'Nie można usunąć.']);
        exit();
    }

    $stmt->closeCursor();

    $sql = "delete from users where user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy.']);
}
