<?php require_once "pdo.php"; 
      require_once "UtilityCode.php";
    
//session_start();

if ( ! isset($_SESSION['email']) ) {
        die('ACCESS DENIED');
    }

if ( isset($_POST['cancel']) ){

    //$_SESSION['cancel'] = $_POST['cancel'];
    header("Location:index.php");
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
    isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ){

    //$_SESSION['first_name'] = $_POST['first_name'];
    //$_SESSION['last_name'] = $_POST['last_name'];
    //$_SESSION['email'] = $_POST['email'];
    //$_SESSION['headline'] = $_POST['headline'];
    //$_SESSION['summary'] = $_POST['summary'];

       $msg = validateProfile();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        } 
        
        $msg = validatePosition();
        if (is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: add.php");
            return;
        } 
        
        $stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST ['last_name'],
            ':em' => $_POST ['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
            );

        $profile_id = $pdo->lastInsertId();

        $rank = 1;
        for  ($i=0; $i <=9; $i++)  {
            if(!isset($_POST['year'.$i]))  { continue; }
            if(!isset($_POST['desc'.$i]))  { continue; }
            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
                $stmt = $pdo->prepare('INSERT INTO Position
                    (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
                $stmt->execute(array(
                        ':pid' => $profile_id,
                        ':rank' => $rank,
                        ':year' => $year,
                        ':desc' => $desc)
                    );
                $rank++;
        }

        $_SESSION['success'] = "Added";
        header("Location: index.php");
        return;

};




?>


<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<title>Elizabeth Szczesny's Resume Registry</title>
</head>

<body>
<div class="container">

<?php

echo ("<h1>Adding Profile for ".htmlentities($_SESSION['name'])."</h1>");

flashMessage();

?>

<form method="POST">
<p>
<label for="firstname">First Name:</label>
<input type="text" name="first_name" id="firstname"><br/>
</p>

<p>
<label for="lastname">Last Name:</label>
<input type="text" name="last_name" id="lastname"><br/>
</p>

<p>
<label for="em">Email:</label>
<input type="text" name="email" id="em"><br/>
</p>

<p>
<label for="head">Headline:</label>
<input type="text" name="headline" id="head"><br/>
</p>

<p>Summary: 
<textarea name="summary" rows="8" cols="80"></textarea>
</p>

<p>Position: 
<input type="submit" id="addPos" value="+">
</p>
<div id="position_fields"></div>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<script>

var countPos = 0;

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
</html>