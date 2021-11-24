<?php require_once "head.php";
      require_once "pdo.php"; 
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

/*if ($_SESSION['user_id'] !== $row['user_id']) {
    $_SESSION["error"] = "Invalid ID";
    header('Location:index.php');
    return;
}*/


$row = loadProfile($pdo);

if ($row === false) {
    $_SESSION["error"] = "Bad value";
    header('Location:index.php');
    return;
}

$profile_id = $_GET['profile_id'];

$positions = loadPos($pdo,$profile_id);

$education = loadEducation($pdo, $profile_id);

$names = getInstitutionName($pdo, $education);  

for($i = 0; $i <= 9; $i++){
    if(!isset ($names[$i])) { continue; }
    $education[$i] ['name'] = $names[$i];
    };


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

        $msg = validateEducation();
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

        $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

        for ($i = 0; $i<= 9; $i++){
            if(!isset($_POST['edu_school'.$i]))  { continue; }
                $name = $POST['edu_school'.$i];
                $stmt = $pdo->prepare('DELETE FROM Institution WHERE name=:ne');
                $stmt->execute(array( ':ne' => $name));
        }

        for  ($j=0; $j<=9; $j++)  {
            if(!isset($_POST['edu_school'.$j]))  { continue; }
            $school = $_POST['edu_school'.$j];
                $stmt = $pdo->prepare('INSERT INTO Institution
                    (name) VALUES (:sch)');
                $stmt->execute(array(
                        ':sch' => $school
                    ));

            $institution_id = $pdo->lastInsertId();

            $rank2 = 1;
          
                    if(!isset($_POST['edu_year'.$j]))  { continue; }
                    $edyear = $_POST['edu_year'.$j];
                        $stmt = $pdo->prepare('INSERT INTO Education
                        (profile_id, institution_id, rank, year) VALUES ( :pid, :iid, :rk, :edy )');
                    $stmt->execute(array(
                            ':pid' => $profile_id,
                            'iid' => $institution_id,
                            ':rk' => $rank2,
                            ':edy' => $edyear));
                    $rank2++;
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
    echo ('<div id="education'.$pos.'">'."\n");
    echo ('<p>Year: <input type = "text" name= "year'.$pos.'" value= "'.$position['year'].'" >'."\n"); 
    echo ('<input type = "button" value = "-" ');
    echo ('onclick = "$(\'#position'.$pos.'\').remove();return false;"></p>');
    echo ('<textarea name= "desc'.$pos.'" rows = "8" cols = "80">'."\n");
    echo (htmlentities($position['description'])."\n");
    echo ('</textarea></div>'."\n");
    }   
}

echo ('</div>');  

$pos2 = 0;
echo ('<p>Education: <input type="submit" id="addEdu" value="+"></p>'."\n");

if ($education) {
    foreach($education as $e){
    echo ('<p>Year: <input type = "text" name= "year'.$pos2.'" value= "'.$e['year'].'" >'."\n"); 
    echo ('<input type = "button" value = "-" ');
    echo ('onclick = "$(\'#position'.$pos2.'\').remove();return false;"></p>');
    echo ('<p><input type= "text" size="80" name= "school'.$pos2.'" value="'.htmlentities($e['name']).'"></p>');
    }
    $pos2++;
}
echo('<div id="edu_fields"></div>');

?> 


<input type="submit" name = "save" value="Save"> 
<a href="index.php">Cancel</a>

</form>

</div>
</body>

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

var countEdu = <?= $pos2 ?>;

    $(document).ready(function(){
        $('#addEdu').click(function(e){
            e.preventDefault();
                if(countEdu >= 9){
                    alert("Maximum of 9 education entries");
                    return;
                }
            countEdu ++;
            console.log(countEdu);
            var source = $('#edu-template').html();
            $('#edu_fields').append(source.replace(/@COUNT@/g, countEdu));

            $('.school').autocomplete({ source: "school.php" });
        });
    });


</script>

<script id="edu-template" type="text/html">
<div id="EDU@COUNT@">
    <p>Year:
        <input type="text" name="edu_year@COUNT@" value= "">
        <input type="button" value= "-" onclick="$('#EDU@COUNT@').remove();return false;">
    </p>
    <p>School: 
        <input type="text" size="80" name="edu_school@COUNT@" class="school" value="">
    </p>
</div>
</script>