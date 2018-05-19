<?php
require_once "../connect.php";

if(!isset($_POST['login']) || !isset($_POST['password'])){
    header('Location: ../index.php?' .
        'error=Brak%20dostępu.');
}

$login = htmlentities($_POST['login']);
$password = $_POST['password'];

try {

    $stmt = $pdo->prepare("select user_id, login, is_admin, password from users 
        where login=:login or email=:email");
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':email', $login, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        echo "a";
        header('Location: ../login_form.php?error=Nieprawidłowe%20dane.');
        exit();
    }

    $row = $stmt->fetch();

    if (!password_verify($password, $row['password'])) {
        header('Location: ../login_form.php?error=Nieprawidłowe%20dane.');
        exit();
    }

    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['login'] = $row['login'];
    $_SESSION['is_admin'] = $row['is_admin'];

    header('Location: ../index.php?success=Zalogowano.');

} catch (PDOException $e) {
    header('Location: ../login_form.php?' .
        'error=Błąd%20bazy%20danych.');
}

