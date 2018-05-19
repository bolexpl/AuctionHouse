<?php
require_once "connect.php";

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: index.php?error=Brak%20dostępu.");
    exit();
}

try {
    $sql = "select user_id, login, is_admin, email, telephone from users;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

} catch (PDOException $e) {
    header("Location: index.php?error=Błąd%20bazy%20danych.");
    exit();
}

$PAGE = "Zarządzanie użytkownikami";
require_once "parts/header.php";
?>

<div class="row">
  <div class="col-md-12">

    <table class="table">
      <thead>
      <tr>
        <th scope="col">Id</th>
        <th scope="col">Login</th>
        <th scope="col">Email</th>
        <th scope="col">Telefon</th>
        <th scope="col">Admin</th>
        <th scope="col"></th>
      </tr>
      </thead>
      <tbody>
      <?php while ($row = $stmt->fetch()) { ?>
        <tr>
          <th scope="row"><?= $row['user_id'] ?></th>
          <td><?= $row['login'] ?></td>
          <td><?= $row['email'] ?></td>
          <td><?= $row['telephone'] ?></td>
          <td><?= $row['is_admin'] == 1 ?
                  '<button type="button" onclick="setAdmin(' . $row['user_id'] . ',this);" class="btn btn-danger">Tak</button>'
                  : '<button type="button" onclick="setAdmin(' . $row['user_id'] . ',this);" class="btn btn-success">Nie</button>' ?></td>
          <td><button type="button" onclick="deleteUser(<?=$row['user_id']?>, this);" class="btn btn-danger">Usuń</button></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>

  </div>
</div>

<?php
require_once "parts/footer.php"
?>
