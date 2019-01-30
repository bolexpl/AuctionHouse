<?php
require_once "connect.php";

if(isset($_SESSION['login'])){
    header("Location: index.php?error=Musisz%20się%20wylogować.");
    exit();
}

$PAGE = "Rejestracja";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
    <form method="post" action="php/register.php">

      <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" id="login" name="login"
               placeholder="Wpisz login" required>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
               placeholder="Wpisz adres email" required>
      </div>

      <fieldset>
        <div class="form-group">
          <label for="pass">Hasło</label>
          <input type="password" class="form-control" id="pass" name="pass" placeholder="Hasło" required>
        </div>
        <div class="form-group">
          <label for="confirm_pass">Powtórz hasło</label>
          <input type="password" class="form-control" id="confirm_pass" placeholder="Powtórz hasło" required>
        </div>
      </fieldset>

      <div class="form-group">
        <label for="tel">Numer telefonu</label>
        <input type="tel" class="form-control" id="tel" name="tel" placeholder="Telefon" required>
      </div>
      <button type="submit" class="btn btn-primary">Zarejestruj</button>
    </form>
  </div>
</div>

<?php
require_once "parts/footer.php"
?>
