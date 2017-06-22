<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {

	$qry="SELECT * FROM `StudentSchool` WHERE SchoolId=".$_GET['id']."";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	if(mysqli_num_rows($rs)>0) {
		header("Location: index.php?pid=102&ewn=102");
		exit();
	}

	$qry="DELETE FROM `School` WHERE SchoolId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],9,$lqry);
	header("Location: index.php?pid=102&ewn=210");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {

		$qry="SELECT * FROM `StudentSchool` WHERE SchoolId=".$fcheck[$i]."";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		if(mysqli_num_rows($rs)>0) {
			header("Location: index.php?pid=102&ewn=102");
			exit();
		}

		$qry="DELETE FROM `School` WHERE SchoolId='".$fcheck[$i]."'";
		$lqry.=$qry."\n\r";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],9,$lqry);
	header("Location: index.php?pid=102&ewn=211");
	exit();
}

$c.="<h4 class=\"title\">Schools</h4>
<a href=\"?pid=103&a=a\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add new school</a><br><Br>
";
//show existing addresses

$qry = "
SELECT School.SchoolId, School.Name, School.Address, SchoolCategory.SchoolCategoryName
FROM
School
LEFT JOIN SchoolCategory ON ( SchoolCategory.SchoolCategoryId = School.SchoolCategoryId )
WHERE 1
";

$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY School.SchoolId ";break;
	case 1 : $qry.="ORDER BY School.Name ";break;
	case 2 : $qry.="ORDER BY School.Address ";break;
	case 3 : $qry.="ORDER BY SchoolCategory.SchoolCategoryName ";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_schools']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_GET['sno'])) $i=0; else $i=$_GET['sno'];

if(!isset($_SESSION['p_schools'])) $_SESSION['p_schools']=10;

$paginator_select=get_paginator_select("schools",$pid,$order);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"schools"),$page,$pid,$order,"schools");

$limit=get_limit("schools",$page);

$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";

$selord[$order]=" SELECTED";

$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\">
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">ID</option>
<option value=\"1\"".$selord[1].">School name</option>
<option value=\"2\"".$selord[2].">Address</option>
<option value=\"3\"".$selord[3].">School category</option>
</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=102')\" class=\"button\"><br />
</form>
</td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>

$paginator_pages

<form method=\"post\" action=\"?pid=102&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>S.No</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Name</b><br>
<b>SchoolCategory</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Address</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";

while ($line = mysqli_fetch_array($rs)) {
	++$i;
	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$i."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['SchoolId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	<b>".$line['Name']."</b><br>
	".$line['SchoolCategoryName']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".nl2br($line['Address'])."<br>
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=103&a=e&id=".$line['SchoolId']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit school\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=102&a=1&id=".$line['SchoolId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete school\" /></a>
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

</form>
$paginator_pages";

?>
