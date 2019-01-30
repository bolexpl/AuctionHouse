<?php
require_once "connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try {

    $sql = "select
  o.offer_id,
  date(o.date) as 'date',
  u.user_id,
  u.login,
  u.email,
  u.telephone,
  a.auction_id,
  a.title,
  o.price
from transactions t
  inner join offers o on t.offer_id = o.offer_id
  inner join users u on o.customer_id = u.user_id
  inner join auctions a on o.auction_id = a.auction_id
where o.customer_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $buyed = $stmt->fetchAll();
    $stmt->closeCursor();


    $sql = "select
  o.offer_id,
  date(o.date) as 'date',
  u.user_id,
  u.login,
  u.email,
  u.telephone,
  a.auction_id,
  a.title,
  o.price
from transactions t
  inner join offers o on t.offer_id = o.offer_id
  inner join users u on o.customer_id = u.user_id
  inner join auctions a on o.auction_id = a.auction_id
where a.user_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $selled = $stmt->fetchAll();


} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Profil";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
    <h2>Kupno:</h2>
      <?php
      if (count($buyed) == 0) {
          echo "<h4>Brak transakcji</h4>";
      } else {
          ?>
        <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tytuł aukcji</th>
            <th scope="col">Data</th>
            <th scope="col">Cena</th>
            <th scope="col">Sprzedawca</th>
            <th scope="col">Email</th>
            <th scope="col">Numer tel.</th>
          </tr>
          </thead>
          <tbody>

          <?php
          $i = 1;
          foreach ($buyed as $row) {
              ?>
            <tr>
              <th scope="row"><?= $i ?>.</th>
              <td><a href="auction_page.php?id=<?= $row['auction_id'] ?>"><?= $row['title'] ?></a></td>
              <td><?= $row['date'] ?></td>
              <td><?= $row['price'] ?> zł</td>
              <td><?= $row['login'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['telephone'] ?></td>
            </tr>
              <?php
              $i++;
          }
          ?>

          </tbody>
        </table>
          <?php
      }
      ?>
  </div>
</div>
<hr>
<div class="row">
  <div class="col-md-12">
    <h2>Sprzedaż:</h2>
      <?php
      if (count($selled) == 0) {
          echo "<h4>Brak transakcji</h4>";
      } else {
          ?>
        <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tytuł aukcji</th>
            <th scope="col">Data</th>
            <th scope="col">Cena</th>
            <th scope="col">Kupujący</th>
            <th scope="col">Email</th>
            <th scope="col">Numer tel.</th>
          </tr>
          </thead>
          <tbody>

          <?php
          $i = 1;
          foreach ($selled as $row) {
              ?>
            <tr>
              <th scope="row"><?= $i ?>.</th>
              <td><?= $row['title'] ?></td>
              <td><?= $row['date'] ?></td>
              <td><?= $row['price'] ?> zł</td>
              <td><?= $row['login'] ?></td>
              <td><?= $row['email'] ?></td>
              <td><?= $row['telephone'] ?></td>
            </tr>
              <?php
              $i++;
          }
          ?>

          </tbody>
        </table>
          <?php
      }
      ?>
  </div>
</div>

<?php
require_once "parts/footer.php"
?>
