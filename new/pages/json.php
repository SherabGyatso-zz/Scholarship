
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
echo json_encode($rows);

?>