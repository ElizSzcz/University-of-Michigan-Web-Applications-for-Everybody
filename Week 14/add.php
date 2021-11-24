<?php   require_once "head.php";
        require_once "pdo.php"; 
        require_once "UtilityCode.php";
        
    
//session_start();

if ( ! isset($_SESSION['email']) ) {
        die('ACCESS DENIED');
    }

if ( isset($_POST['cancel']) ){
    header("Location:index.php");
    return;
}

/*if (isset($_POST['first_name']) && isset($_POST['last_name']) && 
    isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) )*/
if (isset($_POST['add'])){

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

        $msg = validateEducation();
        if (is_string($msg)){
            $_SESSION['error'] = $msg;
            header('Location:add.php');
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
            ':su' => $_POST['summary']));

        $profile_id = $pdo->lastInsertId();

        $rank = 1;
        for  ($i=0; $i<=9; $i++)  {
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
                        ':desc' => $desc));
                $rank++;
    
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
        

        $_SESSION['success'] = "Added";
        header("Location: index.php");
        return;

};




?>


<html>
<head>


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
<input type="text" name="first_name" id="firstname" value="<?php echo isset($_POST["first_name"])? $_POST["first_name"]:""; ?>"><br/>
</p>

<p>
<label for="lastname">Last Name:</label>
<input type="text" name="last_name" id="lastname" value="<?php echo isset($_POST["last_name"])? $_POST["last_name"]:""; ?>"><br/>
</p>

<p>
<label for="em">Email:</label>
<input type="text" name="email" id="em" value="<?php echo isset($_POST["email"])? $_POST["email"]:""; ?>"><br/>
</p>

<p>
<label for="head">Headline:</label>
<input type="text" name="headline" id="head" value="<?php echo isset($_POST["headline"])? $_POST["headline"]:""; ?>"><br/>
</p>

<p>Summary: 
<textarea name="summary" rows="8" cols="80"><?php echo isset($_POST["first_name"])? $_POST["first_name"]:""; ?></textarea>
</p>

<p>Position: 
<input type="submit" id="addPos" value="+">
</p>
<div id="position_fields"></div>

<p>Education: 
<input type="submit" id="addEdu" value="+">
</p>
<div id="edu_fields"></div>


<!--<script id="edu-template" type="text/html">

<div id="EDU@COUNT@">
    <p>Year:
        <input type="text" name="edu_year@COUNT@" value= "">
        <input type="button" value= "-" onclick="$('#EDU@COUNT@').remove();return false;">
    </p>
    <p>School: 
        <input type="text" size="80" name="edu_school@COUNT@" class="school" value="">
    </p>
</div>

</script>-->

<p>
<input type="submit" name="add" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>

</form>
</div>
</body>
</html>

<script>
//Position fields append
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
                         <textarea name= "desc' + countPos + '" rows = "8" cols = "80"><?php echo isset($_POST["first_name"])? $_POST["first_name"]:""; ?></textarea> \
                         </div>');
        });
    });

//Education fields append
var countEdu = 0;

    $(document).ready(function(){
        $('#addEdu').click(function(e){
            e.preventDefault();
                if(countEdu >= 9){
                    alert("Maximum of 9 education entries");
                    return;
                }
            countEdu ++;
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



<!--$('#edu_fields').append('<div id="edu' + countEdu + '"> \
        <p>Year: <input type = "text" name= "edu_year'+ countEdu +'" value= "" > \
        <input type = "button" value = "-" \
        onclick = "$(\'#edu' + countEdu +'\').remove();return false;"></p> \
        <p>School: <input type="text" size="80" name = "edu_school'+ countEdu + '" class= "school" value = ""></p> \
        </div>');
            
        $('.school').autocomplete({ source: "school.php" });-->
    
