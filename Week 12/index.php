<?php require_once "pdo.php"; 

session_start();


$stmt=$pdo->query("SELECT profile_id, first_name, last_name, headline from Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

//var_dump($count);
//var_dump ($rows);

?>


<html>
<head>
<title>Elizabeth Szczesny Resume Registry</title>

</head>

<body>
<div class="container">

<h1>Elizabeth Szczesny's Resume Registry</h1>

<?php

if (isset($_SESSION['update'])){
    echo ('<p style= "color:green">'.$_SESSION['update']."</p>\n");
    unset($_SESSION['update']);
}

if (isset($_SESSION['add'])){
    echo ('<p style= "color:green">'.$_SESSION['add']."</p>\n");
    unset($_SESSION['add']);
}

if (isset($_SESSION['delete'])){
    echo ('<p style= "color:green">'.$_SESSION['delete']."</p>\n");
    unset($_SESSION['delete']);
}

if (isset($_SESSION['error'])){
    echo ('<p style= "color:red">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']);
}

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

    if ( isset($_SESSION['email']) && isset($_SESSION['pass'])  ) { 

    echo ('<p><a href='."add.php".'>Add New Entry</a></p>'."\n");
    echo ('<p><a href='."logout.php".'>Logout</a></p>'."\n");

    }

}

?>


</div>
</body>