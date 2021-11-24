<?php require_once "pdo.php"; 
    
session_start();

$stmt=$pdo->prepare("SELECT profile_id, first_name, last_name, email, headline, summary FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt=$pdo->prepare("SELECT year, description, rank, profile_id FROM Position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$rows2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>

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

        echo ('<p> Position:');
        echo ('<ul>');
        foreach ($rows2 as $row2){
        echo ('<li>'.$row2['year'].': '.$row2['description'].'</li>');
        }
        echo ('</ul>');
    }

?>

<a href="index.php">Done</a>

</div>
</body>