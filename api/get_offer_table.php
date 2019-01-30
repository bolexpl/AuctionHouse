<?php
require_once "../connect.php";

$id = htmlentities($_GET['id']);

try {
    $sql = "select completed, user_id from auctions where auction_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $auction = $stmt->fetch();
    $stmt->closeCursor();


    $sql = "select login, price, offer_id
from offers o
inner join users u on o.customer_id = u.user_id
where auction_id=:id
order by price desc";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $offers = $stmt->fetchAll();

} catch (PDOException $e) {
    $error = "Błąd bazy danych.";
}

if (count($offers) > 0) {
    ?>
  <h3>Oferty
      <?php
      if ($auction['completed'] == 0
          && isset($_SESSION['user_id'])
          && $_SESSION['user_id'] == $auction['user_id']) {
          ?>
        <form action="php/add_transaction.php" method="post" style="display: inline;">
          <input type="hidden" name="offer_id" value="<?= $offers[0]['offer_id'] ?>">
          <button type="submit" class="btn btn-primary">Akceptuj transakcję</button>
        </form>
          <?php
      }
      ?>
  </h3>

  <table class="table">
    <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Użytkownik</th>
      <th scope="col">Oferta</th>
    </tr>
    </thead>
    <tbody>

    <?php
    $i = 1;
    foreach ($offers as $offer) {
        ?>
      <tr>
        <th scope="row"><?= $i ?>.</th>
        <td><?= $offer['login'] ?></td>
        <td><?= $offer['price'] ?> zł</td>
      </tr>
        <?php
        $i++;
    }
    ?>

    </tbody>
  </table>

  <script>
      max_price = <?=$offers[0]['price']?>;
  </script>

    <?php
} else {
    echo "<h3>Brak ofert</h3>";
}
?>