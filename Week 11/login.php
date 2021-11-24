<?php // Do not put any HTML above this line

session_start();



$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123


// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {

    $_SESSION['email'] = $_POST['email'];
    $_SESSION['pass'] = $_POST['pass'];
    
    if ( strlen($_SESSION['email']) < 1 || strlen($_SESSION['pass']) < 1 ) {
        $_SESSION['error'] = "E-mail and password are required"; 
        header("Location: login.php");
        return;
    
    }
    
    else {
        
        if (stripos($_SESSION['email'],'@') === false) {
        $_SESSION['error'] = "E-mail must have an at-sign (@)";
        header("Location: login.php");
        return;
        }

        else {
        $check = hash('md5', $salt.$_SESSION['pass']);
            if ( $check == $stored_hash ) {
            // Redirect the browser to view.php
            $_SESSION['success'] = "Logged in";
            error_log("Login success ".$_SESSION['email']);
            header("Location: index.php");
            return;
            } else {
            $_SESSION['error'] = "Incorrect password";
            error_log("Login fail ".$_SESSION['email']."  $check");
            header("Location: login.php");
            return;
            }
        }

    }
}
   

?>


<!DOCTYPE html>
<html>
<head>

<title>Elizabeth Szczesny Login Page</title>
</head>

<body>
<div class="container">
<h1>Please Log In</h1>
<?php

if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
  }

?>
<form method="POST">
<label for="name">User Name</label>
<input type="text" name="email" id="name"><br/>


<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
</form>

<p><a href="index.php">Cancel</a></p>

</div>
</body>