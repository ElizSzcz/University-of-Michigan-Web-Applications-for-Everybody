<?php

require_once "pdo.php";

$red = true;
$green = true;

//if (isset($_POST['add'])){

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){

    if (strlen($_POST['make']) < 1 ){
        $failure = "Make is required";
        $red = false;

    }else {
     
        if((is_numeric($_POST['mileage']) && is_numeric($_POST['year'])) === false) {
            $failure = "Mileage and year must be numeric";
            $red = false;

        } else {

                $stmt = $pdo->prepare('INSERT INTO autos
                (make, year, mileage) VALUES ( :make, :year, :mileage)');
                $stmt->execute(array(
                ':make' => $_POST['make'],
                ':year' => $_POST['year'],
                ':mileage' => $_POST['mileage'])
                );
                $failure = "Record Inserted";
                $green = false;
        }
    
   }
}
//}



?>

<!DOCTYPE html>
<html>
<head>
 
<?php require_once "pdo.php" ?>

<title>Elizabeth Szczesny's Auto Page</title>
</head>

<body>
<div class="container">
<h1>
<?php

if ( isset($_REQUEST['name']) ) {
    echo "<p>Tracking Autos for: ".htmlentities($_REQUEST['name']);
    echo "</p>\n";
}

if ( isset($_POST['logout']) ) {
    header("Location: index.php");
}

if ($green === false){
    echo('<p style="color: green;">'.$failure."</p>\n");
}

if ($red === false){
    echo('<p style="color: red;">'.$failure."</p>\n");
}

?>
</h1>

<form method="POST">
<p>
<label for="make">Make:</label>
<input type="text" name="make" id="make"><br/>
</p>

<p>
<label for="year">Year:</label>
<input type="text" name="year" id="year"><br/>
</p>

<p>
<label for="mileage">Mileage:</label>
<input type="text" name="mileage" id="mileage"><br/>
</p>

<p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Log out">
</p>
</form>


<h1>Automobiles</h1>

<?php 

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
    echo "<p>";
    echo ($row['year'])." ";
    echo htmlentities($row['make']). " / ";
    echo ($row['mileage']. " ");
    echo ("</p>\n");
}
?>


</div>
</body>