<?php
require_once "../connect.php";

if (
    !isset($_SESSION['user_id'])
    || !isset($_POST['seller_id'])
    || !isset($_POST['content'])
) {
    header('Location: ../index.php?error=Brak%20dostępu.');
    exit();
}

$seller_id = $_POST['seller_id'];
$content = htmlentities($_POST['content']);

try {

    $sql = "insert into comments (seller_id, user_id, content) VALUES (:seller_id, :user_id, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':seller_id', $seller_id, PDO::PARAM_INT);
    $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->bindValue(':content', $content, PDO::PARAM_STR);
    $stmt->execute();

    header("Location: ../user_profile.php?id={$seller_id}&success=Dodano%20komentarz.");

} catch (PDOException $e) {
    header("Location: ../index.php?error=Błąd%20bazy%20danych.");
}
