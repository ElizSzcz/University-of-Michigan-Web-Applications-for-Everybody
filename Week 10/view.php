<?php require_once "pdo.php"; 
    
    session_start();

    if ( ! isset($_SESSION['email']) ) {
        die('Not logged in');
      }

?>

<title>Elizabeth Szczesny's Auto Page</title>
</head>

<body>
<div class="container">
<h1>
<?php

if ( isset($_SESSION['email']) ) {
    echo "<p>Tracking Autos for: ".($_SESSION['email']);
    echo "</p>\n";
} 
?>
</h1>
<p>
<?php 

if ( isset ($_SESSION['success']) ){
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}

?>
</p>


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

<ul>
<li> <a href="add.php">Add New</a> </li>
<li> <a href="logout.php">Logout</a> </li>
</ul>

</div>
</body>

