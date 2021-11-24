<?php require_once "pdo.php"; 
      require_once "UtilityCode.php";
    
//session_start();

if ( ! isset($_SESSION['email']) ) {
        die('ACCESS DENIED');
    }

if ( ! isset($_REQUEST['profile_id']) ) {
        $_SESSION['error'] = "Missing Profile Id";
        header("Location:index.php");
        return;
    }

$stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_REQUEST['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION["error"] = "Bad value";
    header('Location:index.php');
    return;
}

/*if ($_SESSION['user_id'] !== $row['user_id']) {
    $_SESSION["error"] = "Invalid ID";
    header('Location:index.php');
    return;
}*/

$stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :prof ORDER BY rank');
       $stmt->execute(array(':prof' => $_REQUEST['profile_id']));
       $positions = array();
       while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)){
           $positions [] = $row2;
       }; 
              
       //var_dump($positions);


if (isset ($_POST['save'])) {

       $msg = validateProfile();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location:edit.php?profile_id=".$_POST['profile_id']);
            return;
        } 
        
        $msg = validatePosition();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location:edit.php?profile_id=".$_POST['profile_id']);
            return;
        } 
        
        $stmt = $pdo->prepare('UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em , 
        headline = :he , summary = :su, profile_id = :prid WHERE profile_id = :prid');
        
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST ['last_name'],
            ':em' => $_POST ['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'],
            ':prid' => $_POST['profile_id'])
            );


        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
  
        $rank = 1;
        for($i=0; $i<=9; $i++){
            if(!isset($_POST['year'.$i]))  { continue; }
            if(!isset($_POST['desc'.$i]))  { continue; }
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
                $stmt = $pdo->prepare('INSERT INTO Position
                    (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
                //$stmt = $pdo->prepare('UPDATE Position SET
                        //profile_id = :pid, rank = :rank, year = :year, description = :desc');
                $stmt->execute(array(
                        ':pid' => $_REQUEST['profile_id'],
                        ':rank' => $rank,
                        ':year' => $year,
                        ':desc' => $desc)
                    );
                $rank++;

        }

        $_SESSION['success'] = "Profile Updated";
        header("Location: index.php");
        return;

}; 


              


$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$head = htmlentities($row['headline']);
$sum = htmlentities($row['summary']);
$proid = $row['profile_id'];


?>

<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<title>Elizabeth Szczesny's Resume Page</title>
</head>
<body>
<div class="container">

<?php 

flashMessage();

?>

<form method="POST">
<p>
<label for="firstname">First Name:</label>
<input type="text" name="first_name" id="firstname" value= "<?= $fn ?>"><br/>
</p>

<p>
<label for="lastname">Last Name:</label>
<input type="text" name="last_name" id="lastname" value= "<?= $ln ?>"><br/>
</p>

<p>
<label for="em">Email:</label>
<input type="text" name="email" id="em" value= "<?= $em ?>"><br/>
</p>

<p>
<label for="head">Headline:</label>
<input type="text" name="headline" id="head" value= "<?= $head ?>"><br/>
</p>

<p>
<label for="sum">Summary:</label>
<input type="text" name="summary" id="sum" value= "<?= $sum ?>"><br/>
</p>

<input type="hidden" name= "profile_id" value= "<?= $proid ?>">


<?php

$pos = 0;
echo ('<p>Position: <input type="submit" id="addPos" value="+"></p>'."\n");
echo ('<div id="position_fields">'."\n");

if ($positions){
    foreach ($positions as $position){
    $pos++;
    var_dump ($pos);
    echo ('<div id="position'.$pos.'">'."\n");
    echo ('<p>Year: <input type = "text" name= "year'.$pos.'" value= "'.$position['year'].'" >'."\n"); 
    echo ('<input type = "button" value = "-" ');
    echo ('onclick = "$(\'#position'.$pos.'\').remove();return false;"></p>');
    echo ('<textarea name= "desc'.$pos.'" rows = "8" cols = "80">'."\n");
    echo (htmlentities($position['description'])."\n");
    echo ('</textarea></div>'."\n");
    }   
}

echo ('</div>');  

?> 

<input type="submit" name = "save" value="Save"> 
<a href="index.php">Cancel</a>

</form>

<script>

var countPos = <?= $pos ?>;

    $(document).ready(function(){
        $('#addPos').click(function(e){
            e.preventDefault();
                if(countPos >= 9){
                    alert("Maximum of 9 position entries");
                    return;
                }
            countPos ++;
            $('#position_fields').append('<div id="position' + countPos + '"> \
                <p>Year: <input type = "text" name= "year'+ countPos +'" value= "" > \
                         <input type = "button" value = "-" \
                         onclick = "$(\'#position' + countPos +'\').remove();return false;"></p> \
                         <textarea name= "desc' + countPos + '" rows = "8" cols = "80"></textarea> \
                         </div>');
        });
    });

</script>


</div>
</body>