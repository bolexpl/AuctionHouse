<?php
require_once "connect.php";

if (!isset($_GET['id'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try {

    $sql = "select auction_id, a.user_id, login, description, subcategory_id,
title, date(date) as 'date', completed 
from auctions a
 inner join users u on a.user_id = u.user_id
 where auction_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        header("Location: index.php?error=Brak%20takiej%20aukcji.");
        exit();
    }

    $auction = $stmt->fetch();
    $stmt->closeCursor();

    $sql = "select name 
from products 
inner join auctions a on products.auction_id = a.auction_id
where products.auction_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Błąd bazy";
}

$PAGE = $auction['title'];
require_once "parts/header.php";
?>

<div class="row">

  <div class="col-md-12">

    <h2><?= $auction['title'] ?>
        <?php
        if (isset($_SESSION['user_id']) && $auction['user_id'] == $_SESSION['user_id'] && $auction['completed'] == 0) {
            ?>
          <a href="auction_create_form.php?sub=<?= $auction['subcategory_id'] ?>&id=<?= $auction['auction_id'] ?>">
            <button class="btn btn-primary">Edytuj</button>
          </a>
            <?php
        }
        ?>
    </h2>

    <h6 class="text-muted">Sprzedający: <a
              href="user_profile.php?id=<?= $auction['user_id'] ?>"><?= $auction['login'] ?></a></h6>
    <h6 class="text-muted">Data dodania: <?= $auction['date'] ?></h6>
    <h6 class="text-muted">
      Status: <span class="badge badge-<?= $auction['completed'] == 0 ? 'success' : 'danger' ?>">
                    <?= $auction['completed'] == 0 ? 'w trakcie' : 'zakończona' ?></span>
    </h6>


    <p><?= $auction['description'] ?></p>


  </div>

</div>

<div class="row">
  <div class="col-md-8">
      <?php
      if (count($products) == 0) {
          ?>
        <h3>Brak produktów</h3>

      <a href="manage_products.php?id=<?= $auction['auction_id'] ?>">
          <button class="btn btn-primary">Edytuj</button>
        </a><?php
      } else {
          ?>
        <h2>Produkty
            <?php
            if (isset($_SESSION['user_id']) && $auction['user_id'] == $_SESSION['user_id'] && $auction['completed'] == 0) {
                ?>
              <a href="manage_products.php?id=<?= $auction['auction_id'] ?>">
                <button class="btn btn-primary">Edytuj</button>
              </a>
                <?php
            }
            ?></h2>

          <?php
          foreach ($products as $row) {
              ?>

            <div class="card">
              <div class="card-body">
                <h5 class="card-title"><?= $row['name'] ?></h5>
              </div>
            </div>

              <?php
          }
      }
      ?>
  </div>

  <div class="col-md-4">

      <?php
      if (isset($_SESSION['login']) && $auction['login'] != $_SESSION['login'] && $auction['completed'] == 0) {
          ?>
        <form onsubmit="return false;" style="margin-bottom: 20px;">
          <div class="form-group">
            <label for="price">Kwota oferty (zł)</label>
            <input type="number" class="form-control" id="price" step="0.01" aria-describedby="emailHelp">
          </div>
          <button onclick="sendOffer(<?= $auction['auction_id'] ?>,<?= $_SESSION['user_id'] ?>);"
                  class="btn btn-primary">Wyślij
          </button>
        </form>
          <?php
      }
      ?>

    <div id="offers"></div>

  </div>

  <script>
      let auction_id = <?=$auction['auction_id']?>;
  </script>
</div>

<?php
require_once "parts/footer.php"
?>
