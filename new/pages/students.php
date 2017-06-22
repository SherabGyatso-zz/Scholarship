<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {
	$qry="SELECT * FROM `StudentScholarship` WHERE StudentId=".$_GET['id']."";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	if(mysqli_num_rows($rs)>0) {
		header("Location: index.php?pid=104&ewn=101");
		exit();
	}

	$qry="DELETE FROM `Student` WHERE StudentId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry."\n\r";
	$qry="DELETE FROM `StudentSchool` WHERE StudentId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],12,$lqry);
	header("Location: index.php?pid=104&ewn=214");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {

		$qry="SELECT * FROM `StudentScholarship` WHERE StudentId=".$fcheck[$i]."";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		if(mysqli_num_rows($rs)>0) {
			header("Location: index.php?pid=104&ewn=101");
			exit();
		}

		$qry="DELETE FROM `Student` WHERE StudentId='".$fcheck[$i]."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$lqry.=$qry."\n\r";
		$qry="DELETE FROM `StudentSchool` WHERE StudentId='".$fcheck[$i]."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$lqry.=$qry."\n\r";
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],12,$lqry);
	header("Location: index.php?pid=104&ewn=215");
	exit();
}

$c.="<h4 class=\"title\">Students</h4>
<a href=\"?pid=105&a=a\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add new student</a><br><Br>
";
//show existing addresses
//if name textbox is not empty then search for student

if(isset($_POST['studname'])!="") { $wh = " and student.Name like '%" . trim($_POST['studname']) . "%'"; } else { $wh = ""; }

$qry = "SELECT Student.StudentId, Student.Name, Student.Surname, Student.DateOfBirth, School.Name AS SchoolName, SchoolCategory.SchoolCategoryName
FROM (
Student
LEFT JOIN StudentSchool ON ( StudentSchool.StudentId = Student.StudentId )
)
LEFT JOIN School ON ( School.SchoolId = StudentSchool.SchoolId ) , SchoolCategory
WHERE SchoolCategory.SchoolCategoryId = School.SchoolCategoryId AND Student.isApproved=1 ".$wh;


$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY Student.StudentId desc";break;
	case 1 : $qry.="ORDER BY Student.Surname ";break;
	case 2 : $qry.="ORDER BY Student.DateOfBirth ";break;
	case 3 : $qry.="ORDER BY SchoolName ";break;
	case 4 : $qry.="ORDER BY SchoolCategory.SchoolCategoryName ";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_students']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_GET['sno'])) $i=0; else $i=$_GET['sno'];
if(!isset($_SESSION['p_students'])) $_SESSION['p_students']=10;

$paginator_select=get_paginator_select("students",$pid,$order);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"students"),$page,$pid,$order,"students");

$limit=get_limit("students",$page);

$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";
$selord[4]="";

$selord[$order]=" SELECTED";

$c.="
<table width=\"100%\" position=\"relative\" cellpadding=\"0\" cellspacing=\"0\" >
<tr>
<td align=\"left\">
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">ID</option>
<option value=\"1\"".$selord[1].">Surname</option>
<option value=\"2\"".$selord[2].">Date of birth</option>
<option value=\"3\"".$selord[3].">School name</option>
<option value=\"4\"".$selord[4].">School category</option>
</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=104')\" class=\"button\"><br />
</form>
</td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>
<br />
<form method =\"POST\" action=\"index.php?pid=104\">
<b>Enter the Name:</b> <input type=\"text\" name=\"studname\" />
<input type=\"submit\" name=\"Submit\" value=\"Submit\" />
</form>
$paginator_pages

<form method=\"post\" action=\"?pid=104&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\" border=\"1\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>S.No</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Name</b><br>
<b>Surname</b><br>
Date of birth
</td>
<td bgcolor=\"whitesmoke\" valign=\"top\">
<b>School name</b><br>
<i>School Category</i>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\" valign=\"top\">
&nbsp;
</td>
<td></td>
<td></td>
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
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['StudentId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$i."
	</td>
	<td bgcolor=\"#FAFAFA\">


	<b>".$line['Name']."</b><br>
	".$line['DateOfBirth']."
	</td>
	<td bgcolor=\"#FAFAFA\" valign=\"top\">
	<b>".$line['SchoolName']."</b><br>
	<i>".$line['SchoolCategoryName']."</i>
	</td>
	<td><a href=\"?pid=201&id=".$line['StudentId']."\">Apply for Scholarship</td>
	<td><a href=\"?pid=202&id=".$line['StudentId']."\">Applied Scholarship</td>
	<td bgcolor=\"whitesmoke\" align=\"center\" valign=\"middle\">
	<a href=\"index.php?pid=200&id=".$line['StudentId']."\"><font size=\"-1\">view&nbsp;details&nbsp;&raquo;</font></a>
	</td>

	<td bgcolor=\"#FAFAFA\" align=\"center\" >
	<a href=\"?pid=105&a=e&id=".$line['StudentId']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit student\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=104&a=1&id=".$line['StudentId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete student\" /></a>
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
