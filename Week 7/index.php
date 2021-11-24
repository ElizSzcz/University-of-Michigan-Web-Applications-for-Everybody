<!DOCTYPE html>
<head><title>Elizabeth Szczesny MD5 Cracker</title></head>
<body>
<h1>MD5 cracker</h1>
<p>This application takes an MD5 hash
of a four-character string of numbers and 
attempts to hash all four-character combinations
to determine the original four characters.</p>
<pre>
Debug Output:
<?php
$outcometext = "Not found";
// If there is no parameter, this code is all skipped
if (isset($_GET['md5']) ) {
    $time_pre = microtime(true);
    $md5 = $_GET['md5'];

    // This is our string of numbers
    $numbers = "1234567890";
    $show = 15;

    // Outer loop go through the numbers for the
    // first position in our "possible" pre-hash
    // text
    for($i=0; $i<strlen($numbers); $i++ ) {
        $ch1 = $numbers[$i];   // The first of two characters

        // Our inner loop, Note the use of new variables
        // $j and $ch2 
        for($j=0; $j<strlen($numbers); $j++ ) {
            $ch2 = $numbers[$j];  // Our second character

            for($k=0; $k<strlen($numbers); $k++ ) {
                $ch3 = $numbers[$k];  // Our third character

                for($l=0; $l<strlen($numbers); $l++ ) {
                    $ch4 = $numbers[$l];  // Our fourth character

            // Concatenate the two characters together to 
            // form the "possible" pre-hash text
            $try = $ch1.$ch2.$ch3.$ch4;

            // Run the hash and then check to see if we match
            $check = hash('md5', $try);
            if ( $check == $md5 ) {
                $outcometext = $try;
                break;   // Exit the inner loop
            }

            // Debug output until $show hits 0
            if ( $show > 0 ) {
                print "$check $try\n";
                $show = $show - 1;
            }
                }
            }
        }
    }
    // Compute elapsed time
    $time_post = microtime(true);
    print "Elapsed time: ";
    print $time_post-$time_pre;
    print "\n";
}
?>
</pre>
<!-- Use the very short syntax (and call htmlentities()?) -->
<p>Original Text: <?php echo $outcometext ?></p>
<form>
<input type="text" name="md5" size="60" />
<input type="submit" value="Crack MD5"/>
</form>
<ul>
<li><a href="index.php">Reset</a></li>
<li><a href="make.php">MD5 Encoder</a></li>
</ul>
</body>
</html>
