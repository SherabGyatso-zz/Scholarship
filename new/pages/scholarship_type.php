<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {

	$qry="SELECT * FROM `studentscholarship` WHERE ScholarshipId=".$_GET['id']."";
	//echo $qry;
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	if(mysqli_num_rows($rs)>0) {
		header("Location: index.php?pid=120&ewn=102");
		exit();
	}

	$qry="DELETE FROM `scholarshiptypes` WHERE scholarshipid='".$_GET['id']."'";
	//echo $qry;
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],9,$lqry);
	header("Location: index.php?pid=120&ewn=210");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {

		$qry="SELECT * FROM `scholarshiptypes` WHERE scholarshipid=".$fcheck[$i]."";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		if(mysqli_num_rows($rs)>0) {
			header("Location: index.php?pid=120&ewn=102");
			exit();
		}

		$qry="DELETE FROM `scholarshiptypes` WHERE scholarshipid='".$fcheck[$i]."'";
		$lqry.=$qry."\n\r";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],9,$lqry);
	header("Location: index.php?pid=120&ewn=211");
	exit();
}

$c.="<h4 class=\"title\">Scholarship Types</h4>
<a href=\"?pid=121&a=a\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add new Scholarship</a><br><Br>
";
//show existing addresses

$qry = "select * from scholarshiptypes WHERE 1 ";

$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY scholarshiptypes.scholarshipid ";break;
	case 1 : $qry.="ORDER BY scholarshiptypes.Name ";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_course']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_GET['sno'])) $i=0; else $i=$_GET['sno'];

//if(!isset($_SESSION['p_course'])) $_SESSION['p_course']=10;

//$paginator_select=get_paginator_select("scholarshiptypes",$pid,$order);

//$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"scholarshiptypes"),$page,$pid,$order,"scholarshiptypes");

//$limit=get_limit("scholarshiptypes",$page);

//$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";

$selord[$order]=" SELECTED";
$paginator_select="";
$paginator_pages="";
$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\">
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">CourseID</option>
<option value=\"1\"".$selord[1].">Course</option>
</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=120')\" class=\"button\"><br />
</form>
</td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>

$paginator_pages

<form method=\"post\" action=\"?pid=120&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>S.No</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Scholarship Type</b><br>
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
	".$line['ScholarshipId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	<b>".$line['Name']."</b><br>
	</td>

	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=121&a=e&id=".$line['ScholarshipId']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit school\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=120&a=1&id=".$line['ScholarshipId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete school\" /></a>
	</td>
	</tr>
	";
}
$paginator_pages="";
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
