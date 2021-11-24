<?php require_once "pdo.php"; 
    
    session_start();

if ( ! isset($_SESSION['email']) ) {
        die('Not logged in');
    }

if ( isset($_POST['cancel']) ){

    $_SESSION['cancel'] = $_POST['cancel'];
    header("Location:add.php");
    return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])){

    $_SESSION['make'] = $_POST['make'];
    $_SESSION['year'] = $_POST['year'];
    $_SESSION['mileage'] = $_POST['mileage'];

    if (strlen($_SESSION['make']) < 1 ){
        $_SESSION['error'] = "Make is required";
        header("Location: add.php");
        return;

    }else {
     
        if((is_numeric($_SESSION['mileage']) && is_numeric($_SESSION['year'])) === false) {
            $_SESSION['error'] = "Mileage and year must be numeric";
            header("Location: add.php");
            return;

        } else {

                $stmt = $pdo->prepare('INSERT INTO autos
                (make, year, mileage) VALUES ( :make, :year, :mileage)');
                $stmt->execute(array(
                ':make' => $_SESSION['make'],
                ':year' => $_SESSION['year'],
                ':mileage' => $_SESSION['mileage'])
                );
                $_SESSION['success'] = "Record Inserted";
                header("Location: view.php");
                return;

        }
    
   }
}




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

if ( isset($_SESSION['email']) ) {
    echo "<p>Tracking Autos for: ".htmlentities($_SESSION['email']);
    echo "</p>\n";
}

if ( isset($_SESSION['cancel']) ) {
    header("Location: add.php");
}

if ( isset ($_SESSION['error']) ){
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
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
<input type="submit" name="cancel" value="Cancel">
</p>
</form>


</div>
</body>
