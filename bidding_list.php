<?php
require_once "connect.php";

if (!isset($_SESSION['login'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try {

    $sql = "select title, price, a.auction_id, a.completed
from auctions a
  inner join (
    select auction_id, max(price) as price, customer_id from offers
      where customer_id = :id
    group by auction_id
    ) s on a.auction_id = s.auction_id
order by completed;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll();

} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Profil";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
    <h2>Licytuję:</h2>
      <?php
      if (count($rows) == 0) {
          echo "<h4>Brak Licytacji</h4>";
      } else {
          ?>
        <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tytuł aukcji</th>
            <th scope="col">Aktualna cena</th>
            <th scope="col">Status</th>
          </tr>
          </thead>
          <tbody>

          <?php
          $i = 1;
          foreach ($rows as $row) {
              ?>
            <tr>
              <th scope="row"><?= $i ?>.</th>
              <td><a href="auction_page.php?id=<?= $row['auction_id'] ?>"><?= $row['title'] ?></a></td>
              <td><?= $row['price'] ?> zł</td>
              <td><span class="badge badge-<?= $row['completed'] == 0 ? 'success' : 'danger' ?>">
                    <?= $row['completed'] == 0 ? 'w trakcie' : 'zakończona' ?></span></td>
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
