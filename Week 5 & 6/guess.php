<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elizabeth Szczesny c27e4db1</title>
</head>
<body>
    <h1>Welcome to my Guessing Game</h1>

    <p>
        <?php 
            if (! isset($_GET['guess'])){
            echo ('Missing Guess Parameter');
            } else if (strlen($_GET['guess']) < 1){
            echo ('Your guess is too short');
            } else if (! is_numeric($_GET['guess'])){
            echo ("Your guess is not a number");
            } else if ($_GET['guess'] > 82){
            echo ('Your guess is too high');
            } else if ($_GET['guess'] < 82){
            echo ('Your guess is too low');
            } else { 
            echo ('Congratulations- You are Right');
            }
        ?>
    </p>

</body>
</html>