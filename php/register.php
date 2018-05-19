<?php
require_once "../connect.php";

if(!isset($_POST['login'])
    || !isset($_POST['email'])
    || !isset($_POST['pass'])
    || !isset($_POST['tel'])
){
    header('Location: ../index.php?error=Brak%20dostępu.');
}

$login = htmlentities($_POST['login']);
$email = htmlentities($_POST['email']);
$password = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$tel = htmlentities($_POST['tel']);

try {

    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE login=:login OR email=:email");
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header('Location: ../register_form.php?' .
            'error=Istnieje%20użytkownik%20o%20podanym%20loginie%20lub%20adresie%20email.');
        exit();
    }
    $stmt->closeCursor();

    $stmt = $pdo->prepare("insert into users (login, password, email, telephone) " .
        "VALUES (:login, :password, :email, :telephone);");
    $stmt->bindValue(':login', $login, PDO::PARAM_STR);
    $stmt->bindValue(':password', $password, PDO::PARAM_STR);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->bindValue(':telephone', $tel, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: ../index.php?success=Zarejestrowano.');

} catch (PDOException $e) {
    header('Location: ../register_form.php?' .
        'error=Błąd%20bazy%20danych.');
}
