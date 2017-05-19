<pre>
<?php
include("includes/globals.inc.php");
include("includes/functions.inc.php");

$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);
$qry="SELECT * FROM studentscholarship  where studentscholarship.Status ='Selected' order by StudentScholarshipId ";
echo $qry;
echo '<br />';
$rs = mysqli_query ($db,$qry) or die (mysqli_error()."DB Error selecting!!!");
$line=mysqli_fetch_array($rs);


while($line=mysqli_fetch_array($rs))
{
$r=unserialize($line['ReportData']);
$str_rep=str_replace(",","",$r['fi_y5_amt']);
//$str_rep=substr($r['fi_y5_amt'],0,5);

$chartoremove = array(".","/","+");
$chartoinsert = array("","","");

//$str_rep=str_replace($chartoremove,$chartoinsert,$r['fi_y5_amt']);
echo $str_rep;
echo '<br />';
//echo $r['ssid'];
$form_data_array = array();
echo $r['fi_y5_amt'];
if($r['ssid']!="")
  {
	foreach($r as $key => $value) {
			if($key == 'fi_y5_amt') 
				$form_data_array[$key] = $str_rep;
			else
				$form_data_array[$key] = $value;
		}
 
//print_r($form_data_array);
$form_data=serialize($form_data_array);

$qry1 = "UPDATE `StudentScholarship` SET 
	`ReportData` = '".$form_data."' where StudentScholarshipId=".$r['ssid'];
//echo $qry;
echo '<br />';
//mysql_query ($qry1,$db) or die (mysql_error() ." DB Error updateing!!!"); 
}
/*print('<br />');
print_r($r);
print('<br />');*/
 }


/*echo $str_rep.'<br />';
$substr = substr($r['1i_y1_amt'],0,5);
echo $substr;*/
/*  $qr = "update `studentscholarship` set SponsorName = '".$r['sponsor_name']."' where StudentScholarshipId = 623 and sponsorname!=''";*/

// $rst = mysql_query ($qr,$db) or die ("DB Error!!!");


 
/*
// Serialize an array
$serialized_data = serialize (array ('uno', 'dos234', 'tres'));

// Show what the serialized data looks like
//echo "serialized";
print $serialized_data . "\n\n";

// Unserialize the data
$var = unserialize ($serialized_data);

//echo "unserialise";
// Show what the unserialized data looks like.
var_dump ($var);*/
?>
</pre>
<form action="" method="get">
<INPUT type="text" value="Search.." onfocus="if
(this.value==this.defaultValue) this.value='';" onblur="if(this.value==''){this.value='Search..';}">
<input type="text" />
</form>
