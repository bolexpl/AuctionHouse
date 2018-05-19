<?php
require_once "connect.php";

if (isset($_GET['sub'])) {
    $cat_id = $_GET['sub'];
} else {
    $cat_id = 0;
}

if (isset($_GET['show'])) {
    $show = $_GET['show'];
} else {
    $show = 1;
}

$cats = [];

try {
    //kategorie
    $stmt = $pdo->query("select * from categories");
    foreach ($stmt as $row) {
        $cats[$row['category_id']] = ['nazwa' => $row['nazwa'], 'sub' => []];
    }
    $stmt->closeCursor();

    //podkategorie
    $stmt = $pdo->query("select * from subcategories");
    foreach ($stmt as $row) {
        $cats[$row['parent_category_id']]['sub'][] = $row;
    }
    $stmt->closeCursor();

    //aukcje
    if ($cat_id != 0) {
        $sql = "select auction_id, description, title, date, completed, login 
from auctions inner join users u on auctions.user_id = u.user_id 
where subcategory_id = :id";
        if ($show == 0) {
            $sql .= " and completed = 0";
        }
    } else {
        $sql = "select auction_id, description, title, date, completed, login 
from auctions inner join users u on auctions.user_id = u.user_id";
        if ($show == 0) {
            $sql .= " where completed = 0";
        }
    }
    $sql .= " order by date desc";
    $stmt = $pdo->prepare($sql);
    if ($cat_id != 0) {
        $stmt->bindValue(':id', $cat_id, PDO::PARAM_INT);
    }
    $stmt->execute();
    $auctions = $stmt->fetchAll();
    $stmt->closeCursor();

    //maksymalne ceny
    $sql = "select auction_id, max(price) as 'max'
from offers group by auction_id
union
select auction_id, 0
from auctions
where auction_id not in (select auction_id from offers)";
    $stmt = $pdo->query($sql);
    $max_prices = [];
    foreach ($stmt->fetchAll() as $r) {
        $max_prices[$r['auction_id']] = $r['max'];
    }
    $stmt->closeCursor();

} catch (PDOException $e) {
    $error = "Błąd bazy";
}

$PAGE = "Strona główna";
require_once "parts/header.php";
?>

<div class="row">

  <!--    Kategorie-->
  <div class="col-md-3">
    <div class="list-group">
        <?php if ($cat_id == 0) { ?>
          <button class="list-group-item list-group-item-action active">Wszystko</button>
        <?php } else { ?>
          <a href="?sub=0&show=<?= $show ?>" class="list-group-item list-group-item-action">Wszystko</a>
        <?php
        }
        foreach ($cats as $cat) { ?>
          <div class="list-group-item"><?= $cat['nazwa'] ?>
              <?php if (count($cat['sub'])) { ?>
                <div class="list-group">
                    <?php foreach ($cat['sub'] as $sub) {
                        if ($cat_id == $sub['subcategory_id']) { ?>
                          <button class="list-group-item list-group-item-action active">
                              <?= $sub['nazwa'] ?>
                          </button>
                        <?php } else { ?>
                          <a href="?sub=<?= $sub['subcategory_id'] ?>&show=<?= $show ?>"
                             class="list-group-item list-group-item-action">
                              <?= $sub['nazwa'] ?>
                          </a>
                        <?php
                        }
                    } ?>
                </div>
              <?php } ?>
          </div>
        <?php } ?>
    </div>
  </div>


  <!--  Aukcje-->
  <div class="col-md-9">

    Pokaż zakończone: <a href="?sub=<?= $cat_id ?>&show=<?= $show == 0 ? '1' : '0' ?>">
      <button class="btn btn-secondary"><?= $show == 1 ? 'Tak' : 'Nie' ?></button>
    </a>

      <?php
      if ($cat_id != 0 && isset($_SESSION['user_id'])) {
          ?>
        <a href="auction_create_form.php?sub=<?= $cat_id ?>" class="float-right">
          <button class="btn btn-secondary">Twórz aukcję</button>
        </a>
          <?php
      } else if (isset($_SESSION['login'])) {
          ?>
        <span class="float-right">Aby utworzyć aukcje musisz wybrać kategorie</span>
          <?php
      } else {
          ?>
        <span class="float-right">Aby utworzyć aukcje musisz być zalogowany</span>
          <?php
      }
      ?>
      <?php
      if (count($auctions) == 0) {
          echo "<h4>Brak aukcji.</h4>";
      } else {
          foreach ($auctions as $row):
              ?>
            <div class="card">
              <a href="auction_page.php?id=<?= $row['auction_id'] ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= $row['title'] ?></h5>
                  <p class="card-text"><?= $row['description'] ?></p>
                  <h6>
                    <span class="badge badge-secondary">Dodano: </span> <?= $row['date'] ?>
                  </h6>
                  <h6>
                    <span class="badge badge-secondary">Przez: </span> <?= $row['login'] ?>
                  </h6>
                  <h6>
                    <span class="badge badge-success">Aktualna cena: </span><?= $max_prices[$row['auction_id']] ?> zł
                  </h6>
                  <h6>
                    Status: <span class="badge badge-<?= $row['completed'] == 0 ? 'success' : 'danger' ?>">
                    <?= $row['completed'] == 0 ? 'w trakcie' : 'zakończona' ?></span>
                  </h6>
                </div>
              </a>
            </div>
          <?php
          endforeach;
      }
      ?>

  </div>
</div>

<?php
require_once "parts/footer.php"
?>
