<?php require_once "pdo.php"; 
    
session_start();

if ( ! isset($_SESSION['email']) ) {
        die('Not Logged In');
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

    if (  (strlen($_POST['first_name']) < 1 ) || (strlen($_POST['last_name']) < 1) || 
            (strlen($_POST['email']) < 1) || (strlen($_POST['headline']) < 1 ) || 
            (strlen($_POST['summary']) < 1)   ){
            $_SESSION['error'] = "All fields are required";
            header("Location: add.php");
            return;

    } else {

        if (stripos($_POST['email'],'@') === false){
            $_SESSION['error'] = "Email address must contain @";
            header("Location: add.php");
            return;     

        } else {

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
            $_SESSION['add'] = "Added";
            header("Location: index.php");
            return;

        }
    } 
}




?>


<html>
<head>
<title>Elizabeth Szczesny Resume Registry</title>

</head>

<body>
<div class="container">

<?php

echo ("<h1>Adding Profile for ".htmlentities($_SESSION['name'])."</h1>");

if (isset($_SESSION['error'])) {
    echo ('<p style="color: red;">'.($_SESSION['error']).'</p>');
    unset($_SESSION['error']);
}

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

<p>
<label for="sum">Summary:</label>
<input type="text" name="summary" id="sum"><br/>
</p>

<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>


</div>
</body>