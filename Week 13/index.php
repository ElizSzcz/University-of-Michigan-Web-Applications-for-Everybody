<?php require_once "pdo.php"; 
      require_once "UtilityCode.php";

//session_start();


$stmt=$pdo->query("SELECT profile_id, first_name, last_name, headline from Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

//var_dump($count);
//var_dump ($rows);

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

<h1>Elizabeth Szczesny's Resume Registry</h1>

<?php


flashMessage();

if ( !isset($_SESSION['email']) && !isset($_SESSION['pass'])  ) { 

   // echo ('<p><a href='."login.php".'>Please log in</a></p>'."\n");
   echo ('<p><a href="login.php">Please log in</a></p>');
}

    echo('<table border="1">'."\n");
        echo ("<tr><td>");
        echo "Name";
        echo ("</td><td>");
        echo "Headline";
        echo ("</td><td>");
        echo "Action";
        echo ("</td></tr>");

if ($rows) {
    foreach ($rows as $row) {
    echo "<tr><td>";
        echo ('<a href = "view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>');
        echo ("</td><td>");
        echo (htmlentities($row['headline']));
        echo ("</td><td>");
        echo ('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a>  / ');
        echo ('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
        echo "</td></tr>";
    }
    echo ('</table>');
}

if ( isset($_SESSION['email']) && isset($_SESSION['pass'])  ) { 

    echo ('<p><a href='."add.php".'>Add New Entry</a></p>'."\n");
    echo ('<p><a href='."logout.php".'>Logout</a></p>'."\n");

}


?>


</div>
</body>