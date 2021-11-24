<?php require_once "pdo.php";
session_start();

if ( ! isset($_SESSION['email']) ) {
    die('ACCESS DENIED');
}

$stmt = $pdo->prepare("SELECT profile_id, first_name FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false){
    $_SESSION["error"] = "Bad value";
    header('Location:index.php');
    return;
}

if (  isset ($_POST['delete']) && isset($_POST['profile_id'])  ){
$stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id = :zip");
$stmt->execute(array(":zip" => $_POST['profile_id']));
    $_SESSION['success'] = "Record Deleted";
    header ('Location: index.php');
    return;
}

?>

<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

<title>Elizabeth Szczesny's Resume Page</title>
</head>

<body>
<div class="container">

<p>Confirm Deleting <?= $row['first_name'] ?>?</p>

<form method="POST">
    <input type="hidden" name="profile_id" value= "<?= $row['profile_id']?>">
    <input type= "submit" name= "delete" value="Delete">
    <a href="index.php">Cancel</a>


</form>

</div>
</body>