<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title><?= $PAGE ?></title>
  <link type="text/css" rel="stylesheet" href="css/bootstrap.min.css">
  <link type="text/css" rel="stylesheet" href="css/bootstrap-grid.min.css">
  <link type="text/css" rel="stylesheet" href="css/bootstrap-reboot.min.css">
  <link type="text/css" rel="stylesheet" href="css/style.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="index.php">Tablica aukcyjna</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02"
          aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarColor02">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item <?= ($PAGE == 'Strona główna') ? 'active' : '' ?>">
        <a class="nav-link" href="index.php">Główna</a>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto">
      <li class="mr-3">
        <form class="form-inline" action="index.php">
          <input class="form-control mr-sm-2" type="search"
                 <?php
                 if(isset($_GET['s'])){
                   echo "value='{$_GET['s']}'";
                 }
                 ?>
                 placeholder="Wszystkie kategorie" name="s" aria-label="Search">
          <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Szukaj</button>
        </form>
      </li>

        <?php
        if (isset($_SESSION['login'])):
            ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle btn btn-secondary text-light" href="http://example.com"
               id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= $_SESSION['login'] ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="user_profile.php?id=<?=$_SESSION['user_id']?>">Profil</a>
              <a class="dropdown-item" href="transactions_list.php">Moje Transakcje</a>
              <a class="dropdown-item" href="bidding_list.php">Moje Licytacje</a>
              <a class="dropdown-item" href="auctions_list.php">Moje Aukcje</a>
                <?php
                if ($_SESSION['is_admin']) {
                    echo '<a class="dropdown-item" href="report.php">Raporty</a>';
                    echo '<a class="dropdown-item" href="manage_users.php">Zarządzanie użytkownikami</a>';
                }
                ?>
              <a class="dropdown-item" href="user_settings.php">Ustawienia</a>
              <a class="dropdown-item" href="php/logout.php">Wyloguj</a>
            </div>
          </li>
        <?php
        else:
            ?>
          <li class="nav-item">
            <a class="nav-link <?= ($PAGE == 'Rejestracja') ? 'active' : '' ?>" href="register_form.php">
              Zarejestruj
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($PAGE == 'Logowanie') ? 'active' : '' ?>" href="login_form.php">
              Zaloguj
            </a>
          </li>
        <?php
        endif;
        ?>

    </ul>
  </div>
</nav>

<div class="container">

    <?php
    if (isset($_GET['success'])):
        ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= $_GET['success'] ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    endif;
    ?>

    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
    }
    if (isset($error)):
        ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= $error ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <?php
    endif;
    ?>
