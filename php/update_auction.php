<?php
require_once "../connect.php";

if (
    !isset($_SESSION['user_id'])
    || !isset($_POST['title'])
    || !isset($_POST['description'])
    || !isset($_POST['id'])
) {
    header('Location: ../index.php?' .
        'error=Brak%20dostępu.');
    exit();
}

$id = $_POST['id'];
$title = htmlentities($_POST['title']);
$description = htmlentities($_POST['description']);

try {
    $sql = "update auctions set title = :title, description = :des where auction_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':des', $description, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../auction_page.php?id={$id}&success=Zapisano.");

} catch (PDOException $e) {
    header("Location: ../index.php?error=Błąd%20bazy%20danych.");
}
