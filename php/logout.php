<?php
require_once "../connect.php";

if(!isset($_SESSION['user_id'])){
    header('Location: ../login_form.php?' .
        'error=Błąd%20bazy%20danych.');
}

unset($_SESSION['user_id']);
unset($_SESSION['login']);
unset($_SESSION['is_admin']);
header('Location: ../index.php?' .
    'success=Wylogowano.');
