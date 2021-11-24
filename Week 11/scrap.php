echo '<p style="color=green">'.$_SESSION['success']."</p>\n";
unset($_SESSION['success']);


if   (   isset($_SESSION['success']) && $row === true  ) {

    //(isset($_SESSION['make'])) && (isset($_SESSION['model'])) && (isset($_SESSION['year'])) && (isset($_SESSION['mileage']))  ) {
        echo ('<table border="1">'."\n");
        echo "<tr><td>";
        echo "Make";
        echo ("</td><td>");
        echo "Model";
        echo ("</td><td>");
        echo "Year";
        echo ("</td><td>");
        echo "Mileage";
        echo ("</td><td>");
        echo "Action";
        echo ("</td></tr>");
    
    $stmt=$pdo->query("SELECT make, model, year, mileage from autos");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        echo "<tr><td>";
        echo (htmlentities($row['make']));
        echo ("</td><td>");
        echo (htmlentities($row['model']));
        echo ("</td><td>");
        echo (htmlentities($row['year']));
        echo ("</td><td>");
        echo (htmlentities($row['mileage']));
        echo ("</td><td>");
        echo ('<a href="edit.php?user_id='.$row['user_id'].'">Edit</a>'.'/'.'<a href="delete".php?user_id='.$row['user_id'].'>Delete</a>');
        echo "</td></tr>";
        echo '<p><a href="add.php">Add New Entry</a></p>';
        echo '<p><a href="logout.php">Logout</a></p>';
    }

} elseif ( isset($_SESSION['success']) ) {
    echo "No rows found";
    echo ('<p><a href="add.php">Add New Entry</a></p>');
    echo ('<p><a href="logout.php">Logout</a></p>');

} else {
    echo ('<p><a href="login.php">Please Log In</a></p>');
    echo ('<p>Attempt to <a href="add.php">add data</a> without logging in</p>');
};

?>

<p><a href="logout.php">Logout for debugging</a></p>

</div>
</body>