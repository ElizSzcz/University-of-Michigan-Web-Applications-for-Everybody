<?php require_once "pdo.php"; 
    
session_start();

if ( ! isset($_SESSION['email']) ) {
        die('Not Logged In');
    }

$stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION["error"] = "Bad value";
    header('Location:index.php');
    return;
}

if ($_SESSION['user_id'] !== $row['user_id']) {
    $_SESSION["error"] = "Invalid ID";
    header('Location:index.php');
    return;
}



//if (isset($_POST['firstname2']) && isset($_POST['lastname2']) && isset($_POST['email2']) 
    //&& isset($_POST['headline2']) && isset($_POST['summary2'])  && isset($_POST['profile_id']) ){


if (isset ($_POST['save'])) {
    var_dump($_POST);
    
    if (   (strlen($_POST['first_name']) > 1) && (strlen($_POST['last_name']) > 1) && (strlen($_POST['email']) > 1) 
    && (strlen($_POST['headline']) > 1) && (strlen($_POST['summary']) > 1) ){
    
            $sql = "UPDATE Profile SET first_name = :fn, last_name = :ln, email = :em , 
            headline = :he , summary = :sum, profile_id = :prid WHERE profile_id = :prid";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':sum' => $_POST['summary'],
            ':prid' => $_POST['profile_id']  
            ));
            $_SESSION['update'] = "Record updated";
            header('Location:index.php');
            return;
        } else {
            $_SESSION['error'] = "All fields must be filled in";
            header("Location:edit.php?profile_id=".$_POST['profile_id']);
            return;
        }

}


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

<title>Elizabeth Szczesny's Auto Page</title>
</head>
<body>
<div class="container">

<?php 

if (isset($_SESSION['error'])){
    echo ('<p style= "color:red">'.$_SESSION['error'].'</p>');
    unset($_SESSION['error']);
}

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

<input type="submit" name = "save" value="Save"> <a href="index.php">Cancel</a>

</form>

</div>
</body>