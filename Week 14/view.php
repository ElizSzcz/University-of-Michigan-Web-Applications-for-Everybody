<?php require_once "pdo.php"; 
      require_once "UtilityCode.php";
    
//session_start();

$row = loadProfile($pdo);

$profile_id = $row['profile_id'];

$positions = loadPos($pdo,$profile_id);

$education = loadEducation($pdo, $profile_id);

$names = getInstitutionName($pdo, $education);  

for($i = 0; $i <= 9; $i++){
  if(!isset ($names[$i])) { continue; }
  $education[$i] ['name'] = $names[$i];
};



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

        echo ('<p>First Name:  '. $row['first_name'].'</p>');
        echo ('<p>Last Name:  '. $row['last_name'].'</p>');
        echo ('<p>Email:  '. $row['email'].'</p>');
        echo ('<p>Headline: </p>');
        echo ('<p>'.$row['headline'].'</p>');
        echo ('<p>Summary:  </p>');
        echo ('<p>'.$row['summary'].'</p>'); 

        echo ('<p> Position:');
        echo ('<ul>');
        foreach ($positions as $position){
        echo ('<li>'.$position['year'].': '.$position['description'].'</li>');
        }
        echo ('</ul>');

        echo ('<p> Education:');
        echo('<ul>');
        foreach ($education as $e){
        echo ('<li>'.$e['year'].': ');
        echo ($e['name'].'</li>');
        }
        echo('</ul><br>');
?>

<a href="index.php">Done</a>

</div>
</body>