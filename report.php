<?php
require_once "connect.php";

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] == 0) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

if (isset($_GET['type'])) {
    $typ = $_GET['type'];
} else {
    $typ = 'day';
}

try {
    if ($typ == 'day') {
        $sql = "select * from day_report";
    } elseif ($typ == 'month') {
        $sql = "select * from month_report";
    } else {
        $sql = "select * from year_report";
    }
    $stmt = $pdo->query($sql);
    $response = $stmt->fetchAll();
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

    Wystawione aukcje: <?= $response[0]['count'] ?><br>
    Zakończone aukcje: <?= $response[1]['count'] ?><br>
    Łączna kwota transakcji: <?= $response[2]['count'] == null ? '0' : $response[2]['count'] ?> zł<br>
    Wystawione komentarze: <?= $response[3]['count'] ?><br>
    Złożone oferty: <?= $response[4]['count'] ?><br>

  </div>
</div>

<?php
require_once "parts/footer.php"
?>
