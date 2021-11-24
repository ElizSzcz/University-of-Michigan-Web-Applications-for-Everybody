<?php // Do not put any HTML above this line

require_once "pdo.php";
session_start();

$salt = 'XyZzy12*_';


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

        $stmt = $pdo->prepare('SELECT user_id, name FROM users
           
        WHERE email = :em AND password = :pw');

        $stmt->execute(array( ':em' => $_SESSION['email'], ':pw' => $check));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ( $row !== false) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['user_id'] = $row['user_id'];

                $_SESSION['success'] = "Logged in";
                error_log("Login success ".$_SESSION['email']);
                // Redirect the browser to index.php
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

<input type="submit" onclick="return doValidate();" value="Log In">
</form>

<p><a href="index.php">Cancel</a></p>

</div>




<script type="text/javascript">
function doValidate() {

console.log('Validating...');

try {

    pw = document.getElementById('id_1723').value;
    name = document.getElementById('name').value;

    console.log("Validating pw="+pw);

    if (  (pw == null || pw == "") || (name == null || name == "")  ) {
        alert("Both fields must be filled out");
        return false;
    }
    return true;
} catch(e) {
    return false;
}
return false;
}



</script>
</body>