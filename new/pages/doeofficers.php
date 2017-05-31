<?php
$c="";

if(isset($_GET['a']) && $_GET['a']==1) {
	$qry="DELETE FROM `DoeOfficer` WHERE DoeOfficerId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],15,$qry);
	header("Location: index.php?pid=1&ewn=218");
	exit();
}

$lqry="";
if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {
		$qry="DELETE FROM `DoeOfficer` WHERE DoeOfficerId='".$fcheck[$i]."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$lqry.=$qry."\n\r";
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],15,$lqry);
	header("Location: index.php?pid=1&ewn=219");
	exit();
}

$c.="<h4 class=\"title\">Doe Officers</h4>
<a href=\"?pid=2&a=a\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Add new doe officer\" align=\"left\" />&nbsp;&nbsp;Add new DOE Officer</a><br><Br>
";
//show existing addresses

$qry = "
SELECT * FROM `DoeOfficer` WHERE 1
";

$rs = mysqli_query ($db, $qry) or die ("DB Error!!!");


$c.="

<form method=\"post\" action=\"?pid=1&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Name</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Contact</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";

while ($line = mysqli_fetch_array($rs)) {

	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['DoeOfficerId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['DoeOfficerId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Name']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Contact']."
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=2&a=e&id=".$line['DoeOfficerId']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit DOE officer\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=1&a=1&id=".$line['DoeOfficerId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete DOE officer\" /></a>
	</td>
	</tr>
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
