<?php
require_once "../connect.php";

if(
    !isset($_SESSION['user_id'])
    || !isset($_POST['email'])
    || !isset($_POST['pass'])
    || !isset($_POST['tel'])
    || !isset($_POST['old_pass'])
){
    header('Location: ../index.php?' .
        'error=Brak%20dostępu.');
}

$email = htmlentities($_POST['email']);
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$tel = htmlentities($_POST['tel']);
$old_pass = htmlentities($_POST['old_pass']);

$sql = "SELECT password from users where user_id=:id";

if ($_POST['pass'] != "") {
    $sql2 = "update users set password=:pass, email=:email, telephone=:tel where user_id=:id";
} else {
    $sql2 = "update users set email=:email, telephone=:tel where user_id=:id";
}

try {

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        header('Location: ../user_settings.php?error=Błąd.');
        exit();
    }

    $row = $stmt->fetch();
    if (!password_verify($old_pass, $row['password'])) {
        header('Location: ../user_settings.php?error=Złe hasło.');
        exit();
    }
    $stmt->closeCursor();

    $stmt = $pdo->prepare($sql2);
    if ($_POST['pass'] != "") {
        $stmt->bindValue(':pass', $pass, PDO::PARAM_STR);
    }
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../user_settings.php?success=Zapisano.');

} catch (PDOException $e) {
    header('Location: ../user_settings.php?error=Błąd%20bazy%20danych.');
}

