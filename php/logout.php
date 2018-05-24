<?php
require_once "../connect.php";

if(!isset($_SESSION['user_id'])){
    header('Location: ../login_form.php?' .
        'error=Błąd%20bazy%20danych.');
}

session_destroy();

header('Location: ../index.php?' .
    'success=Wylogowano.');
