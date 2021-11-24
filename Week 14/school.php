<?php require_once "pdo.php";

header("Content-type:application/JSON;charset=utf-8");

$term = $_GET['term'];

$stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
$stmt->execute(array(':prefix'=> $term."%"));

//His
$retvalue = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $retval [] = $row['name'];
}

echo(json_encode($retval, JSON_PRETTY_PRINT));

//Mine
/*$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$names = array();
for($i = 0; $i < count($rows); $i++ ){
$names [] = $rows[$i]['name'];
}

json_encode($names);


?>

