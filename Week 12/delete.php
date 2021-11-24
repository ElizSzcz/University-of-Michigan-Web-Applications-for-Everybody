<?php require_once "pdo.php";
session_start();

$stmt = $pdo->prepare("SELECT profile_id, first_name FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false){
    $_SESSION["delete"] = "Bad value";
    header('Location:index.php');
    return;
}

if (  isset ($_POST['delete']) && isset($_POST['profile_id'])  ){
$stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id = :zip");
$stmt->execute(array(":zip" => $_POST['profile_id']));
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

<p>Confirm Deleting <?= $row['first_name'] ?>?</p>

<form method="POST">
    <input type="hidden" name="profile_id" value= "<?= $row['profile_id']?>">
    <input type= "submit" name= "delete" value="Delete">
    <a href="index.php">Cancel</a>


</form>

</div>
</body>