<?php require_once "pdo.php";
session_start();

$stmt = $pdo->prepare("SELECT autos_id FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false){
    $_SESSION["delete"] = "Bad value";
    header('Location:index.php');
    return;
}

if (  isset ($_POST['delete']) && isset($_POST['autos_id'])  ){
$stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id = :zip");
$stmt->execute(array(":zip" => $_POST['autos_id']));
    $_SESSION['delete'] = "Record Deleted";
    header ('Location: index.php');
    return;
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Elizabeth Szczesny's Auto Page</title>
</head>

<body>
<div class="container">

<p>Confirm Deleting <?= $row['autos_id'] ?>?</p>

<form method="POST">
    <input type="hidden" name="autos_id" value= "<?= $row['autos_id']?>">
    <input type= "submit" name= "delete" value="Delete">
    <a href="index.php">Cancel</a>


</form>

</div>
</body>