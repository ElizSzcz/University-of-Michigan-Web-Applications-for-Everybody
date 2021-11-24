<?php require_once "pdo.php"; 
    
session_start();

if ( ! isset($_SESSION['email']) ) {
        die('ACCESS DENIED');
    }

$stmt = $pdo->prepare("SELECT make, model, year, mileage, autos_id FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false){
    $_SESSION["delete"] = "Bad value";
    header('Location:index.php');
    return;
}

if (isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) 
    && isset($_POST['mileage']) && isset($_POST['autosid'])){
    $sql = "UPDATE autos SET make = :make, model = :model, 
    year= :year, mileage= :mileage, autos_id= :autosid WHERE autos_id = :autosid" ;
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autosid' => $_POST['autosid']  
    ));
    $_SESSION['update'] = "Record updated";
    header('Location:index.php');
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$autosid = $row['autos_id'];

?>

<!DOCTYPE html>
<html>
<head>

<title>Elizabeth Szczesny's Auto Page</title>
</head>
<body>
<div class="container">

<form method="POST">
<p>
<label for="make">Make:</label>
<input type="text" name="make" id="make" value= "<?= $make ?>"><br/>
</p>

<p>
<label for="model">Model:</label>
<input type="text" name="model" id="model" value= "<?= $model ?>"><br/>
</p>

<p>
<label for="year">Year:</label>
<input type="text" name="year" id="year" value= "<?= $year ?>"><br/>
</p>

<p>
<label for="mileage">Mileage:</label>
<input type="text" name="mileage" id="mileage" value= "<?= $mileage ?>"><br/>
</p>

<input type="hidden" name= "autosid" value= "<?= $autosid ?>">

<input type="submit" value="Save"> <a href="index.php">Cancel</a>

</form>

</div>
</body>