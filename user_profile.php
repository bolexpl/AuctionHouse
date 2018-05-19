<?php
require_once "connect.php";

if (!isset($_GET['id'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

$id = htmlentities($_GET['id']);

try {

    //użytkownik
    $sql = "select login, email, telephone, user_id from users where user_id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() != 1) {
        header("Location: index.php?error=Nie%20ma%20takiego%20użytkownika.");
        exit();
    }

    $user = $stmt->fetch();
    $stmt->closeCursor();


    //aukcje
    $sql = "select title, price, a.auction_id, a.completed, date(a.date) as 'date'
from auctions a
  inner join (
    select auction_id, max(price) as price, customer_id from offers
    group by auction_id
    ) s on a.auction_id = s.auction_id
    where user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $auctions = $stmt->fetchAll();
    $stmt->closeCursor();


    //transakcje
    $sql = "select count(*) as 'count'
from transactions
  inner join offers o on transactions.offer_id = o.offer_id
  inner join auctions a on o.auction_id = a.auction_id
where customer_id = :id1 or a.user_id = :id2";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id1', $id, PDO::PARAM_INT);
    $stmt->bindValue(':id2', $id, PDO::PARAM_INT);
    $stmt->execute();
    $transact = $stmt->fetch()['count'];
    $stmt->closeCursor();


    //napisane komentarze
    $sql = "select count(*) as 'count'
from comments
where user_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $comCount = $stmt->fetch()['count'];
    $stmt->closeCursor();


    //otrzymane komentarze
    $sql = "select u.user_id, login, content, date(date) as 'date'
from comments c
inner join users u on c.user_id = u.user_id
where seller_id = :id
order by c.date desc";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $comments = $stmt->fetchAll();
    $stmt->closeCursor();


} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Profil użytkownika: " . $user['login'];
require_once "parts/header.php";
?>

<div class="row">

  <div class="col-md-6">

    <h5>Login: <?= $user['login'] ?></h5>
    <h5>Email: <?= $user['email'] ?></h5>
    <h5>Telefon: <?= $user['telephone'] ?></h5>
    <h5>Wystawione aukcje: <?= count($auctions) ?></h5>
    <h5>Sfinalizowane transakcje: <?= $transact ?></h5>
    <h5>Napisane komentarze: <?= $comCount ?></h5>

  </div>

  <div class="col-md-6" style="overflow: auto; max-height: 250px;">

      <?php
      if (count($auctions) == 0) {
          echo "<h5>Brak aukcji</h5>";
      } else {
          ?>
        <h4>Aukcje</h4>
        <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Tytuł aukcji</th>
            <th scope="col">Aktualna cena</th>
            <th scope="col">Status</th>
            <th scope="col">Data utworzenia</th>
          </tr>
          </thead>
          <tbody>

          <?php
          $i = 1;
          foreach ($auctions as $row) {
              ?>
            <tr>
              <th scope="row"><?= $i++ ?>.</th>
              <td><a href="auction_page.php?id=<?= $row['auction_id'] ?>"><?= $row['title'] ?></a></td>
              <td><?= $row['price'] ?> zł</td>
              <td><span class="badge badge-<?= $row['completed'] == 0 ? 'success' : 'danger' ?>">
                    <?= $row['completed'] == 0 ? 'w trakcie' : 'zakończona' ?></span></td>
              <td><?= $row['date'] ?></td>
            </tr>
              <?php
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

<?php
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $id) {
    ?>
  <div class="row">
    <div class="col-md-12">
      <form method="post" action="php/add_comment.php">
        <input type="hidden" name="seller_id" value="<?= $user['user_id'] ?>">
        <div class="form-group">
          <label for="comment">Wystaw komentarz</label>
          <textarea type="text" class="form-control" id="comment" rows="2"
                    placeholder="Treść" name="content"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Wystaw</button>
      </form>
    </div>
  </div>
  <hr>
    <?php
}
?>

<div class="row">
  <div class="col-md-12">
      <?php
      if (count($comments) == 0) {
          echo "<h4>Brak komentarzy</h4>";
      } else {
          ?>
        <h3>Opinie o sprzedawcy (<?= count($comments) ?>):</h3>

          <?php
          foreach ($comments as $row) {
              ?>
            <div class="card">
              <div class="card-body">
                <h6 class="card-title">
                  <a href="user_profile.php?id=<?= $row['user_id'] ?>"><?= $row['login'] ?></a>
                </h6>
                <h6 class="card-subtitle mb-2 text-muted"><?= $row['date'] ?></h6>
                <h5 class="card-subtitle"><?= $row['content'] ?></h5>
              </div>
            </div>
              <?php
          }
      }
      ?>
  </div>
</div>

<?php
require_once "parts/footer.php"
?>
