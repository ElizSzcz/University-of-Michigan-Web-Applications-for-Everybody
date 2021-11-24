<?php require_once "pdo.php"; 
    
session_start();

$stmt=$pdo->prepare("SELECT profile_id, first_name, last_name, email, headline, summary FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<html>
<head>
<title>Elizabeth Szczesny Resume Registry</title>

</head>

<body>
<div class="container">

<h1>Profile Information</h1>

<?php

    foreach ($rows as $row) {
        echo ('<p>First Name:  '. $row['first_name'].'</p>');
        echo ('<p>Last Name:  '. $row['last_name'].'</p>');
        echo ('<p>Email:  '. $row['email'].'</p>');
        echo ('<p>Headline: </p>');
        echo ('<p>'.$row['headline'].'</p>');
        echo ('<p>Summary:  </p>');
        echo ('<p>'.$row['summary'].'</p>');
    }

?>

<a href="index.php">Done</a>

</div>
</body>