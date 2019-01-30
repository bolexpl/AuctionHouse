<?php
require_once "connect.php";

if(isset($_SESSION['login'])){
    header("Location: index.php?error=Musisz%20się%20wylogować.");
    exit();
}

$PAGE = "Logowanie";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
    <form method="post" action="php/login.php">
      <div class="form-group">
        <label for="login">Login lub email</label>
        <input type="text" class="form-control" id="login"
               name="login" placeholder="Login lub email" required>
      </div>
      <div class="form-group">
        <label for="pass">Hasło</label>
        <input type="password" class="form-control" id="password"
               name="password" placeholder="Hasło" required>
      </div>
      <button type="submit" class="btn btn-primary">Zaloguj</button>
    </form>
  </div>
</div>

<?php
require_once "parts/footer.php"
?>
