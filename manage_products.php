<?php
require_once "connect.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

$id = $_GET['id'];

try {

    $sql = "select title from auctions where auction_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() != 1) {
        header("Location: index.php?error=Nie%20ma%20takiej%20aukcji.");
        exit();
    }

    $title = $stmt->fetch()['title'];
    $stmt->closeCursor();

    $sql = "select product_id, name from products where auction_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $products = $stmt->fetchAll();


} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}


$PAGE = "Zarządzanie produktami: ".$title;
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">

    <h3>Produkty w aukcji: <a href="auction_page.php?id=<?=$id?>"><?= $title ?></a></h3>

    <table class="table">
      <thead>
      <tr>
        <th scope="col">Nazwa</th>
      </tr>
      </thead>
      <tbody id="tbody">

      <?php
      foreach ($products as $row) {
          ?>
        <tr>
          <td>
            <input type="hidden" name="id" value="<?= $row['product_id'] ?>">
            <input title="Nazwa" name="nazwa" type="text" value="<?= $row['name'] ?>" style="width: 50%">
            <button onclick="productSave(this);" class="btn btn-success">Zapisz</button>
            <button onclick="productDelete(this);" class="btn btn-danger">Usuń</button>
          </td>
        </tr>
          <?php
      }
      ?>

      <tr>
        <td>
          <input type="hidden" name="id" value="0">
          <input title="Nazwa" name="nazwa" type="text" style="width: 50%">
          <button onclick="productSave(this);" class="btn btn-success">Dodaj</button>
        </td>
      </tr>
      </tbody>
    </table>

    <button onclick="productSaveAll();" class="btn btn-success">Zapisz wszystko</button>

  </div>
</div>

<script>
  let auction_id = <?=$id?>;
</script>

<?php
require_once "parts/footer.php";
?>
