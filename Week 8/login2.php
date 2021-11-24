<?php

if (isset ($_POST['cancel'])){
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123

$failure = false;

if ( isset($_POST['email']) && isset($_POST['pass']) ){
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
         $failure = "Username and password are required";
    } else {
        $check = hash('md5', $salt.$_POST['pass'] );
            if ( $check == $stored_hash ){
                header("Location: game.php?name=".urlencode($_POST['who']));
                return;
            } else {
            $failure = "Incorrect password";
            }
    }
}


if( ! isset($_POST['name']) || strlen($_POST['name']) < 1 ){
    $failure = "Username and password are required";
}
else if ( ! isset($_POST['pass']) || strlen($_POST['pass'] < 1)){
    $failure = "Username and password are required";
} 
else if ($_POST['pass'] == $password){
    header("Location: game.php");
} 
else if {
    $failure= "Incorrect Password";
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once "bootstrap.php";?>
    <title>By Myself</title>
</head>
<body>

<div class="container">

<h1>Please Log In</h1>

<?php
    if ($failure !== false){
    echo "$failure";
    }
?>


<form method="post">
    <label for ="name"> Name: </label>
    <input type="text" name="who" id = "name"> <br>
    <label for = "123"> Password: </label>
    <input type="text" name="pass" id= "123"> <br>
    <input type= "submit" value= "Log In">
    <input type= "submit" name="cancel" value="Cancel">

</form>

</div>
</body>
</html>