<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {

	$qry="SELECT * FROM `School` WHERE SchoolCategoryId=".$_GET['id']."";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	if(mysqli_num_rows($rs)>0) {
		header("Location: index.php?pid=100&ewn=104");
		exit();
	}

	$qry="DELETE FROM `SchoolCategory` WHERE SchoolCategoryId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],6,$lqry);
	header("Location: index.php?pid=100&ewn=200");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {

		$qry="SELECT * FROM `School` WHERE SchoolCategoryId=".$fcheck[$i]."";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		if(mysqli_num_rows($rs)>0) {
			header("Location: index.php?pid=100&ewn=104");
			exit();
		}

		$qry="DELETE FROM `SchoolCategory` WHERE SchoolCategoryId='".$fcheck[$i]."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$lqry.=$qry."\n\r";
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],6,$lqry);
	header("Location: index.php?pid=100&ewn=203");
	exit();
}

$c.="<h4 class=\"title\">School categories</h4>
<a href=\"javascript:void(0)\" onclick=\"popup('schoolcategories_add_edit.php?a=a',500,200)\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add new category</a><br><Br>
";
//show existing categories

$qry = "SELECT * FROM SchoolCategory WHERE 1";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$c.="
<form method=\"post\" action=\"?pid=100&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Category</b>
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
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['SchoolCategoryId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['SchoolCategoryId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['SchoolCategoryName']."
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"javascript:void(0)\" onclick=\"popup('schoolcategories_add_edit.php?a=e&id=".$line['SchoolCategoryId']."',500,200)\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=100&a=1&id=".$line['SchoolCategoryId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete category\" /></a>
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
