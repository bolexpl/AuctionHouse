<?php
require_once "connect.php";

if(!isset($_SESSION['login'])){
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try{

  $sql = "SELECT email, telephone from users where user_id=:id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
  $stmt->execute();

  if($stmt->rowCount() == 0){
      header("Location: index.php?error=Błąd.");
      exit();
  }

  $row = $stmt->fetch();

}catch (PDOException $e){
  header("Location: index.php?error=Błąd%20bazy%20danych.");
  exit();
}

$PAGE = "Ustawienia";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
    <form method="post" action="php/settings.php">

      <div class="form-group">
        <label for="old_pass">Stare hasło</label>
        <input type="password" class="form-control" id="old_pass"
               name="old_pass" placeholder="Stare Hasło" required>
      </div>

      <fieldset>
        <div class="form-group">
          <label for="password">Nowe hasło</label>
          <input type="password" class="form-control" id="pass" name="pass" placeholder="Nowe hasło">
        </div>
        <div class="form-group">
          <label for="confirm_pass">Powtórz nowe hasło</label>
          <input type="password" class="form-control" id="confirm_pass" placeholder="Powtórz nowe hasło">
        </div>
      </fieldset>

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" value="<?=$row['email']?>"
               name="email" placeholder="Email">
      </div>

      <div class="form-group">
        <label for="tel">Numer telefonu</label>
        <input type="tel" class="form-control" id="tel" value="<?=$row['telephone']?>"
               name="tel" placeholder="Numer telefonu">
      </div>

      <button type="submit" class="btn btn-primary">Zapisz</button>
    </form>
  </div>
</div>

<?php
require_once "parts/footer.php"
?>
