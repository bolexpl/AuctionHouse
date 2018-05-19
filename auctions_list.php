<?php
require_once "connect.php";

if (!isset($_SESSION['login'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try {

    $sql = "select title, date(date) as 'date', completed, auction_id from auctions where user_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $auctions = $stmt->fetchAll();
    $stmt->closeCursor();

    $sql = "select o.auction_id, max(price) as 'price'
from offers o
  inner join auctions a on o.auction_id = a.auction_id
where user_id = :id
group by auction_id
union
select auction_id, 0
from auctions
where auction_id not in (select distinct auction_id from offers)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":id", $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $prices = [];

    foreach ($stmt->fetchAll() as $price) {
        $prices[$price['auction_id']] = $price['price'];
    }

} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Profil";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
      <?php
      if (count($auctions) == 0) {
          echo "<h4>Brak aukcji</h4>";
      } else {
          ?>

        <h2>Aukcje (<span id="count"><?=count($auctions)?></span>):</h2>
        <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tytuł aukcji</th>
            <th scope="col">Aktualna cena</th>
            <th scope="col">Status</th>
            <th scope="col">Data utworzenia</th>
            <th scope="col"></th>
          </tr>
          </thead>
          <tbody>

          <?php
          $i = 1;
          foreach ($auctions as $row) {
              ?>
            <tr>
              <th scope="row"><?= $i ?>.</th>
              <td><a href="auction_page.php?id=<?= $row['auction_id'] ?>"><?= $row['title'] ?></a></td>
              <td><?= $prices[$row['auction_id']] ?> zł</td>
              <td><span class="badge badge-<?= $row['completed'] == 0 ? 'success' : 'danger' ?>">
                    <?= $row['completed'] == 0 ? 'w trakcie' : 'zakończona' ?></span></td>
              <td><?= $row['date'] ?></td>
              <td>
                <button class="btn btn-danger" onclick="deleteAuction(this, <?= $row['auction_id'] ?>)">
                  Usuń
                </button>
              </td>
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
