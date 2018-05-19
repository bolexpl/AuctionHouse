<?php
require_once "connect.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] == 0) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

if (isset($_GET['time'])) {
    $time = $_GET['time'];
} else {
    $time = 'current';
}

if (isset($_GET['type'])) {
    $typ = $_GET['type'];
} else {
    $typ = 'day';
}


if ($typ == 'day') {
    $arg = date("d");
} elseif ($typ == 'month') {
    $arg = date("m");
} elseif ($typ == 'year') {
    $arg = date("Y");
}

if ($time == 'past') {
    $arg--;
}

try {

    //aukcje
    $sql = "select count(*) as 'count' from auctions where {$typ}(date) = :arg";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':arg', $arg, PDO::PARAM_INT);
    $stmt->execute();

    $auctions = $stmt->fetch()['count'];
    $stmt->closeCursor();

    //transakcje
    $sql = "select count(*) as 'count' from transactions where {$typ}(date) = :arg";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':arg', $arg, PDO::PARAM_INT);
    $stmt->execute();

    $transactions = $stmt->fetch()['count'];
    $stmt->closeCursor();

    //łączna kwota
    $sql = "select sum(price) as 'price'
from transactions t
inner join offers o on t.offer_id = o.offer_id
where {$typ}(t.date) = :arg";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':arg', $arg, PDO::PARAM_INT);
    $stmt->execute();
    $money = $stmt->fetch()['price'];
    if($money == null){
      $money = 0;
    }
    $stmt->closeCursor();

    //komentarze
    $sql = "select count(*) as 'count' from comments where {$typ}(date) = :arg";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':arg', $arg, PDO::PARAM_INT);
    $stmt->execute();

    $comments = $stmt->fetch()['count'];
    $stmt->closeCursor();

    //oferty
    $sql = "select count(*) as 'count' from offers where {$typ}(date) = :arg";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':arg', $arg, PDO::PARAM_INT);
    $stmt->execute();

    $offers = $stmt->fetch()['count'];
    $stmt->closeCursor();

} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Raport ";
if ($typ == 'day') {
    $PAGE .= "dzienny";
} else if ($typ == 'month') {
    $PAGE .= "miesięczny";
} else {
    $PAGE .= "roczny";
}
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">
      <?php

      if ($time == 'current') { ?>
        <a href="?type=<?= $typ ?>&time=past">
          <button class="btn btn-secondary">Aktualny</button>
        </a>
      <?php } else { ?>
        <a href="?type=<?= $typ ?>&time=current">
          <button class="btn btn-secondary">Poprzedni</button>
        </a>
      <?php }

      if ($typ != 'day') { ?>
        <a href="?type=day">
          <button class="btn btn-primary">Dzień</button>
        </a>
      <?php }
      if ($typ != 'month') { ?>
        <a href="?type=month">
          <button class="btn btn-primary">Miesiąc</button>
        </a>
      <?php }
      if ($typ != 'year') { ?>
        <a href="?type=year">
          <button class="btn btn-primary">Rok</button>
        </a>
          <?php
      }
      ?>

    <h3>Raport <?php
        if ($typ == 'day') {
            echo "dzienny";
        } else if ($typ == 'month') {
            echo "miesięczny";
        } else {
            echo "roczny";
        }
        ?>:</h3>

    Wystawione aukcje: <?= $auctions ?><br>
    Zakończone aukcje: <?= $transactions ?><br>
    Łączna kwota transakcji: <?= $money ?> zł<br>
    Wystawione komentarze: <?= $comments ?><br>
    Złożone oferty: <?= $offers ?><br>

  </div>
</div>

<?php
require_once "parts/footer.php"
?>
