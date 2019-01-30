<?php

require_once "../connect.php";

if (!isset($_POST['id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'error' => 'Brak dostępu.']);
    exit();
}

$id = $_POST['id'];

try {
    $sql = "select is_admin from users where user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() != 1) {
        echo json_encode(['success' => false]);
        exit();
    }

    $admin = $stmt->fetch()['is_admin'] ? true : false;
    $stmt->closeCursor();

    if ($admin) {
        $sql = "select user_id from users where is_admin = 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            echo json_encode(['success' => false, 'error' => 'Musi być przynajmniej jeden admin.']);
            exit();
        }
        $stmt->closeCursor();
    }

    if ($admin) {
        $w = false;
        $sql = "update users set is_admin=false where user_id=:id";
    } else {
        $w = true;
        $sql = "update users set is_admin=true where user_id=:id";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $respone = ['success' => true, 'admin' => $w];
    echo json_encode($respone);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Błąd bazy.']);
}

