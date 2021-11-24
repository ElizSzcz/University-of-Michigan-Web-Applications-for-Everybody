<?php require_once "pdo.php"; 

session_start();

$stmt=$pdo->query("SELECT make, model, year, mileage, autos_id from autos");
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

?>


<html>
<head>
<title>Elizabeth Szczesny Autos Database</title>

</head>

<body>
<div class="container">

<h1>Welcome to the Automobiles Database</h1>



<?php

if (isset($_SESSION['add']) ){
echo '<p style="color=green">'.$_SESSION['add']."</p>\n";
unset($_SESSION['add']);
}

if (isset($_SESSION['delete']) ){
echo '<p style="color: red;">'.$_SESSION['delete']."</p>\n";
unset($_SESSION['delete']);
}

if (isset($_SESSION['update']) ){
    echo '<p style="color: green; ">'.$_SESSION['update']."</p>\n";
    unset($_SESSION['update']);
    }

if (   isset($_SESSION['success']) && $count > 1  ) {

    echo ('<table border="1">'."\n");
        echo "<tr><td>";
        echo "Make";
        echo ("</td><td>");
        echo "Model";
        echo ("</td><td>");
        echo "Year";
        echo ("</td><td>");
        echo "Mileage";
        echo ("</td><td>");
        echo "Action";
        echo ("</td></tr>");
        
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        echo "<tr><td>";
        echo (htmlentities($row['make']));
        echo ("</td><td>");
        echo (htmlentities($row['model']));
        echo ("</td><td>");
        echo (htmlentities($row['year']));
        echo ("</td><td>");
        echo (htmlentities($row['mileage']));
        echo ("</td><td>");
        echo ('<a href="edit.php?user_id='.$row['autos_id'].'">Edit</a>  / ');
        echo ('<a href="delete.php?user_id='.$row['autos_id'].'">Delete</a>');
        echo "</td></tr>";
    } 
 
    echo ('</table>');

    echo '<p><a href="add.php">Add New Entry</a></p>';
    echo '<p><a href="logout.php">Logout</a></p>';

} elseif (    isset($_SESSION['success']) && $count == 1  ){

    echo "No rows found";
    echo ('<p><a href="add.php">Add New Entry</a></p>');
    echo ('<p><a href="logout.php">Logout</a></p>');

} else {
    echo ('<p><a href="login.php">Please log in</a></p>');
    echo ('<p>Attempt to <a href="add.php">add data</a> without logging in</p>');

}



?>
<a href="logout.php">Logout</a>

</div>
</body>



