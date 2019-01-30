<?php
require_once "connect.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['sub'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

if (isset($_GET['id'])) {
    try {
        $sql = "select title, description, auction_id from auctions where auction_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
        }


    } catch (PDOException $e) {
        $error = "Błąd bazy danych.";
    }
}

$PAGE = "Tworzenie aukcji";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">

    <form method="post" action="<?= isset($row) ? 'php/update_auction.php' : 'php/add_auction.php' ?>">

      <input type="hidden" name="subcat" value="<?= $_GET['sub'] ?>">
        <?php
        if (isset($row)) {
            echo "<input type=\"hidden\" name=\"id\" value=\"{$_GET['id']}\">";
        }
        ?>

      <div class="form-group">
        <label for="title">Tytuł</label>
        <input type="text" class="form-control" value="<?= isset($row) ? $row['title'] : '' ?>"
               id="title" name="title" placeholder="Tytuł aukcji">
      </div>

      <div class="form-group">
        <label for="opis">Opis</label>
        <textarea class="form-control" rows="5" id="opis"
                  name="description"><?= isset($row) ? $row['description'] : '' ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">Wyślij</button>

    </form>

  </div>
</div>

<?php
require_once "parts/footer.php";
?>
