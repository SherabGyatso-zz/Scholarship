
<?php
//Converting db values into json data
header('content-type:application/json');

$con=mysqli_connect('localhost', 'root', '123', 'scholarship') or die(mysqli_error());


$qry = "SELECT FormData  FROM `studentscholarship";
$result=mysqli_query($con,$qry);
$rows=array();

while($row = mysqli_fetch_array($result))
{
		$rows[] = $row;
}

$jsonfile = json_encode($rows);
//wrote to json file
$fp = fopen('events.json', 'w');
fwrite($fp, $jsonfile);
fclose($fp);

echo "Form Data is Saved ";

?>



