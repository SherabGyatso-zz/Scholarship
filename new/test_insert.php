<pre>
<?php
include("includes/globals.inc.php");
include("includes/functions.inc.php");

$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);
$qry="SELECT * FROM studentscholarship  where StudentScholarshipId=155 order by StudentScholarshipId ";
echo $qry;
echo '<br />';
$rs = mysqli_query ($db,$qry) or die (mysql_error()."DB Error selecting!!!");
$line=mysqli_fetch_array($rs);
$r=unserialize($line['ReportData']);

	if($r['1i_y1_amt']=="") $r['1i_y1_amt']=0; 
	
	$r['1i_y1_amt']=str_replace(",","",$r['1i_y1_amt']);
	
	if($r['21i_y1_amt']=="") $r['21i_y1_amt']=0;
		$r['21i_y1_amt']=str_replace(",","",$r['21i_y1_amt']);
	if($r['fi_y1_amt']=="") $r['fi_y1_amt']=0;
		$r['fi_y1_amt']=str_replace("need","",$r['fi_y1_amt']);
	if($r['1i_y2_amt']=="") $r['1i_y2_amt']=0;
		$r['1i_y2_amt']=str_replace(",","",$r['1i_y2_amt']);
	if($r['21i_y2_amt']=="") $r['21i_y2_amt']=0;
		 $r['21i_y2_amt']=str_replace(",","",$r['21i_y2_amt']);	
	if($r['fi_y2_amt']=="") $r['fi_y2_amt']=0;
		$r['fi_y2_amt']=str_replace(",","",$r['fi_y2_amt']);
	if($r['1i_y3_amt']=="") $r['1i_y3_amt']=0;
		$r['1i_y3_amt']=str_replace(",","",$r['1i_y3_amt']);	
	if($r['21i_y3_amt']=="") $r['21i_y3_amt']=0;
		$r['21i_y3_amt']=str_replace(",","",$r['21i_y3_amt']);	 
	if($r['fi_y3_amt']=="") $r['fi_y3_amt']=0;
		$r['fi_y3_amt']=str_replace(",","",$r['fi_y3_amt']);	
	if($r['1i_y4_amt']=="") $r['1i_y4_amt']=0;
		$r['1i_y4_amt']=str_replace(",","",$r['1i_y4_amt']);	
	if($r['21i_y4_amt']=="") $r['21i_y4_amt']=0;
		$r['21i_y4_amt']=str_replace(",","",$r['21i_y4_amt']);	
	if($r['fi_y4_amt']=="") $r['fi_y4_amt']=0;
		 $r['fi_y4_amt']=str_replace(",","",$r['fi_y4_amt']);	
	if($r['1i_y5_amt']=="") $r['1i_y5_amt']=0;
		$r['1i_y5_amt']=str_replace(",","",$r['1i_y5_amt']);	
	if($r['21i_y5_amt']=="") $r['21i_y5_amt']=0;
		$r['21i_y5_amt']=str_replace(",","",$r['21i_y5_amt']);	
	if($r['fi_y5_amt']=="") $r['fi_y5_amt']=0;
		$r['fi_y5_amt']=str_replace(",","",$r['fi_y5_amt']);
		
		
$qry1 ="update `reporting` set 
	`1i_y1_ddno`='969346_test',
	`1i_y1_amt`=".$r['1i_y1_amt'].",
	`2i_y1_ddno`='".$r['2i_y1_ddno']."',
	`21i_y1_amt`=".$r['21i_y1_amt'].",
	`fi_y1_ddno`='".$r['fi_y1_ddno']."',
	`fi_y1_amt`=".$r['fi_y1_amt'].",
	`1i_y2_ddno`='".$r['1i_y2_ddno']."',
	`1i_y2_amt`=".$r['1i_y2_amt'].",
	`2i_y2_ddno`='".$r['2i_y2_ddno']."',
	`21i_y2_amt`=".$r['21i_y2_amt'].",
	`fi_y2_ddno`='".$r['fi_y2_ddno']."',
	`fi_y2_amt`=".$r['fi_y2_amt'].",
	`1i_y3_ddno`='".$r['1i_y3_ddno']."',
	`1i_y3_amt`=".$r['1i_y3_amt'].",
	`2i_y3_ddno`='".$r['2i_y3_ddno']."',
	`21i_y3_amt`=".$r['21i_y3_amt'].",
	`fi_y3_ddno`='".$r['fi_y3_ddno']."',
	`fi_y3_amt`=".$r['fi_y3_amt'].",
	`1i_y4_ddno`='".$r['1i_y4_ddno']."',
	`1i_y4_amt`=".$r['1i_y4_amt'].",
	`2i_y4_ddno`='".$r['2i_y4_ddno']."',
	`21i_y4_amt`=".$r['21i_y4_amt'].",
	`fi_y4_ddno`='".$r['fi_y4_ddno']."',
	`fi_y4_amt`=".$r['fi_y4_amt'].",
	`1i_y5_ddno`='".$r['1i_y5_ddno']."',
	`1i_y5_amt`=".$r['1i_y5_amt'].",
	`2i_y5_ddno`='".$r['2i_y5_ddno']."',
	`21i_y5_amt`=".$r['21i_y5_amt'].",
	`fi_y5_ddno`='".$r['fi_y5_ddno']."',
	`fi_y5_amt`=".$r['fi_y5_amt']." 
	 Where `StudentScholarshipId`=155"; 
echo $qry1;
mysql_query ($qry1,$db) or die (mysql_error() ." DB Error updateing!!!"); 


/*while($line=mysqli_fetch_array($rs))
{
$r=unserialize($line['ReportData']);
//$str_rep=str_replace(",","",$r['fi_y5_amt']);
//$str_rep=substr($r['fi_y5_amt'],0,5);

//$chartoremove = array(".","/","+");
//$chartoinsert = array("","","");

//$str_rep=str_replace($chartoremove,$chartoinsert,$r['fi_y5_amt']);
//echo $str_rep;
//echo '<br />';
//echo $r['ssid'];
$form_data_array = array();

if($r['ssid']!="" and $r['ssid']>1968)
  {

	if($r['1i_y1_amt']=="") $r['1i_y1_amt']=0; 
	
	$r['1i_y1_amt']=str_replace(",","",$r['1i_y1_amt']);
	
	if($r['21i_y1_amt']=="") $r['21i_y1_amt']=0;
		$r['21i_y1_amt']=str_replace(",","",$r['21i_y1_amt']);
	if($r['fi_y1_amt']=="") $r['fi_y1_amt']=0;
		$r['fi_y1_amt']=str_replace("need","",$r['fi_y1_amt']);
	if($r['1i_y2_amt']=="") $r['1i_y2_amt']=0;
		$r['1i_y2_amt']=str_replace(",","",$r['1i_y2_amt']);
	if($r['21i_y2_amt']=="") $r['21i_y2_amt']=0;
		 $r['21i_y2_amt']=str_replace(",","",$r['21i_y2_amt']);	
	if($r['fi_y2_amt']=="") $r['fi_y2_amt']=0;
		$r['fi_y2_amt']=str_replace(",","",$r['fi_y2_amt']);
	if($r['1i_y3_amt']=="") $r['1i_y3_amt']=0;
		$r['1i_y3_amt']=str_replace(",","",$r['1i_y3_amt']);	
	if($r['21i_y3_amt']=="") $r['21i_y3_amt']=0;
		$r['21i_y3_amt']=str_replace(",","",$r['21i_y3_amt']);	 
	if($r['fi_y3_amt']=="") $r['fi_y3_amt']=0;
		$r['fi_y3_amt']=str_replace(",","",$r['fi_y3_amt']);	
	if($r['1i_y4_amt']=="") $r['1i_y4_amt']=0;
		$r['1i_y4_amt']=str_replace(",","",$r['1i_y4_amt']);	
	if($r['21i_y4_amt']=="") $r['21i_y4_amt']=0;
		$r['21i_y4_amt']=str_replace(",","",$r['21i_y4_amt']);	
	if($r['fi_y4_amt']=="") $r['fi_y4_amt']=0;
		 $r['fi_y4_amt']=str_replace(",","",$r['fi_y4_amt']);	
	if($r['1i_y5_amt']=="") $r['1i_y5_amt']=0;
		$r['1i_y5_amt']=str_replace(",","",$r['1i_y5_amt']);	
	if($r['21i_y5_amt']=="") $r['21i_y5_amt']=0;
		$r['21i_y5_amt']=str_replace(",","",$r['21i_y5_amt']);	
	if($r['fi_y5_amt']=="") $r['fi_y5_amt']=0;
		$r['fi_y5_amt']=str_replace(",","",$r['fi_y5_amt']);
//print_r($form_data_array);
//$report_data=serialize($form_data_array);
$qry1 ="INSERT INTO reporting 
	(`StudentScholarshipId`,
	`1i_y1_ddno`,
	`1i_y1_amt`,
	`2i_y1_ddno`,
	`21i_y1_amt`,
	`fi_y1_ddno`,
	`fi_y1_amt`,
	`1i_y2_ddno`,
	`1i_y2_amt`,
	`2i_y2_ddno`,
	`21i_y2_amt`,
	`fi_y2_ddno`,
	`fi_y2_amt`,
	`1i_y3_ddno`,
	`1i_y3_amt`,
	`2i_y3_ddno`,
	`21i_y3_amt`,
	`fi_y3_ddno`,
	`fi_y3_amt`,
	`1i_y4_ddno`,
	`1i_y4_amt`,
	`2i_y4_ddno`,
	`21i_y4_amt`,
	`fi_y4_ddno`,
	`fi_y4_amt`,
	`1i_y5_ddno`,
	`1i_y5_amt`,
	`2i_y5_ddno`,
	`21i_y5_amt`,
	`fi_y5_ddno`,
	`fi_y5_amt`) 
VALUES ("
	.$r['ssid'].
	",'".$r['1i_y1_ddno']."',"
	.$r['1i_y1_amt'].
	",'".$r['2i_y1_ddno']."',"
	.$r['21i_y1_amt'].
	",'".$r['fi_y1_ddno']."',"
	.$r['fi_y1_amt'].
	",'".$r['1i_y2_ddno']."',"
	.$r['1i_y2_amt'].
	",'".$r['2i_y2_ddno']."',"
	.$r['21i_y2_amt'].
	",'".$r['fi_y2_ddno']."',"
	.$r['fi_y2_amt'].
	",'".$r['1i_y3_ddno']."',"
	.$r['1i_y3_amt'].
	",'".$r['2i_y3_ddno']."',"
	.$r['21i_y3_amt'].
	",'".$r['fi_y3_ddno']."',"
	.$r['fi_y3_amt'].
	",'".$r['1i_y4_ddno']."',"
	.$r['1i_y4_amt'].
	",'".$r['2i_y4_ddno']."',"
	.$r['21i_y4_amt'].
	",'".$r['fi_y4_ddno']."',"
	.$r['fi_y4_amt'].
	",'".$r['1i_y5_ddno']."',"
	.$r['1i_y5_amt'].
	",'".$r['2i_y5_ddno']."',"
	.$r['21i_y5_amt'].
	",'".$r['fi_y5_ddno']."',"
	.$r['fi_y5_amt'].")";

//$qry1 = "UPDATE `StudentScholarship` SET 	`ReportData` = '".$report_data."' where StudentScholarshipId=".$r['ssid'];
//echo $qry1;
echo '<br />';
//mysql_query ($qry1,$db) or die (mysql_error() ." DB Error updateing!!!"); 
//exit;

}
/*print('<br />');
print_r($r);
print('<br />');*/
 //} end of while


/*echo $str_rep.'<br />';
$substr = substr($r['1i_y1_amt'],0,5);
echo $substr;*/
/*  $qr = "update `studentscholarship` set SponsorName = '".$r['sponsor_name']."' where StudentScholarshipId = 623 and sponsorname!=''";*/

0// $rst = mysql_query ($qr,$db) or die ("DB Error!!!");


 
/*
// Serialize an array
$serialized_data = serialize (array ('\no', 'dos234', 'tres'));

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
*/