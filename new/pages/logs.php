<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {
	$qry="DELETE FROM `School` WHERE SchoolId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	if($_SESSION['utype']==1) add_log($db,$_SESSION['userid'],9,$lqry);
	header("Location: index.php?pid=102&ewn=210");
	exit();		
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {		
		$qry="DELETE FROM `School` WHERE SchoolId='".$fcheck[$i]."'";
		$lqry.=$qry."\n\r";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	}
	if($_SESSION['utype']==1) add_log($db,$_SESSION['userid'],9,$lqry);
	header("Location: index.php?pid=102&ewn=211");
	exit();
} 

$c.="<font class=\"title\">Logs</font><br><Br>
";
//show existing addresses

$qry = "
SELECT * 
FROM logs LEFT JOIN logs_types USING(TypeId) WHERE 1
";

$order=-1;
if(!isset($_GET['order'])) $order=1; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY Id ";break;
	case 1 : $qry.="ORDER BY Time DESC";break;
	case 2 : $qry.="ORDER BY UserName ";break;
	case 3 : $qry.="ORDER BY Type ";break;
}

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";

$selord[$order]=" SELECTED";

$c.="
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">ID</option>
<option value=\"1\"".$selord[1].">Date|Time</option>
<option value=\"2\"".$selord[2].">User name</option>
<option value=\"3\"".$selord[3].">Log type</option>
</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=3')\" class=\"button\"><br />
</form>

<form method=\"post\" action=\"?pid=3&a=2\">
* <b>Bold name</b> indicates Administrator<br />
* <font style=\"color: red\">Red color</font> indicates that Doe Officer / Admin has been already deleted<br />

<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>User name*</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Date/Time</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Log type</b>
</td>
<td bgcolor=\"whitesmoke\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";
$i=0;
while ($line = mysqli_fetch_array($rs)) {
	$datetime=date("Y-m-d", $line['Time']) . "&nbsp;&nbsp;" . date("g:i a", $line['Time']);
	$i++;
	
	$uname = $line['UserName'];
	if($line['UserType']==0) $uname="<b>$uname</b>";
	
	if($utype==0) {
		$utable = 'Admins';
		$idfield= 'AdminId';
	} else if($utype==1) {
		$utable = 'DoeOfficer';
		$idfield= 'DoeOfficerId';		
	}
	
	$qry="SELECT Name FROM $utable WHERE $idfield=".$line['UserId']."";
	$rs2 = mysql_query($qry,$db);
	$cnt = mysqli_num_rows($rs2);
	
	if($cnt==0) $uname="<font style=\"color: red\">$uname</font>";	
		
	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['Id']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['Id']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	$uname
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$datetime."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Type']."
	</td>
	
	<td bgcolor=\"#FAFAFA\">
	<a href=\"javascript:void(0)\" onclick=\"showQuery('$i')\">SQL query</a>
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=102&a=1&id=".$line['SchoolId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete school\" /></a>
	</td>
	</tr>
	
	
	<tr><td></td><td colspan=\"6\">
	<div name=\"sqlquery$i\" style=\"DISPLAY: none\" id=\"sqlquery$i\">
	<div align=\"right\">
	<a href=\"javascript:void(0)\" onclick=\"hideQuery('$i')\">hide SQL query</a>
	</div>
	".$line['Query']."
	</div>
	</td></tr>
		
	";
}

$c.="</table><br>
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td width=\"50\">
<img src=\"images/arrow_ltr.png\" width=\"38\" height=\"22\">
</td>
<td align=\"left\">
<input type=\"submit\" value=\"Delete selected\" class=\"button\" />
</td>
</tr>
</table>

</form>";

?>