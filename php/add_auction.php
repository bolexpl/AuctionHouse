<?php
require_once "../connect.php";

if (
    !isset($_SESSION['user_id'])
    || !isset($_POST['title'])
    || !isset($_POST['description'])
    || !isset($_POST['subcat'])
) {
    header('Location: ../index.php?' .
        'error=Brak%20dostępu.');
    exit();
}

$title = htmlentities($_POST['title']);
$description = htmlentities($_POST['description']);
$subcat = $_POST['subcat'];

try {

    $sql = "select auction_id from auctions 
where user_id=:user_id and description=:description and title=:title and subcategory_id=:subcategory_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':subcategory_id', $subcat, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() != 0) {
        header("Location: ../index.php?sub={$subcat}&error=Istnieje%20taka%20aukcja.");
        exit();
    }


    $sql = "insert into auctions (user_id, description, title, subcategory_id) 
VALUES (:user_id, :description, :title, :subcategory_id)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':subcategory_id', $subcat, PDO::PARAM_INT);
    $stmt->execute();

    $stmt->closeCursor();

    $sql = "select auction_id from auctions 
where user_id=:user_id and description=:description and title=:title and subcategory_id=:subcategory_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':description', $description, PDO::PARAM_STR);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':subcategory_id', $subcat, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../manage_products.php?id={$stmt->fetch()['auction_id']}&success=Utworzono%20aukcję.");

} catch (PDOException $e) {
    header("Location: ../index.php?error=Błąd%20bazy%20danych.");
}
