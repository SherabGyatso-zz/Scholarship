<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {
	$qry="DELETE FROM `Student` WHERE StudentId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry."\n\r";
	$qry="DELETE FROM `StudentSchool` WHERE StudentId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	registration_mail($_GET['id'],"delete");
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],17,$lqry);
	header("Location: index.php?pid=114&ewn=228");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==3) {
	$qry="UPDATE Student SET isApproved=1 WHERE StudentId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	registration_mail($_GET['id'],"approve");
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],16,$qry);
	header("Location: index.php?pid=114&ewn=229");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$sub_action=$_POST['sub_action'];
	if($sub_action=="Approve selected") {
		$fcheck=$_POST['fcheck'];
		for($i=0;$i<count($fcheck);$i++) {
			$qry="UPDATE Student SET isApproved=1 WHERE StudentId='".$fcheck[$i]."'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
			$lqry.=$qry."\n\r";
			registration_mail($fcheck[$i],"approve");
		}
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],16,$lqry);
		header("Location: index.php?pid=114&ewn=226");
		exit();
	} else if($sub_action=="Delete selected") {
		$fcheck=$_POST['fcheck'];
		for($i=0;$i<count($fcheck);$i++) {
			$qry="DELETE FROM `Student` WHERE StudentId='".$fcheck[$i]."'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
			$lqry.=$qry."\n\r";
			$qry="DELETE FROM `StudentSchool` WHERE StudentId='".$fcheck[$i]."'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
			$lqry.=$qry."\n\r";
			registration_mail($fcheck[$i],"delete");
		}
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],17,$lqry);
		header("Location: index.php?pid=114&ewn=227");
		exit();
	}
}

$c.="<h4 class=\"title\">Registrations - new students</h4>";
//show existing addresses

$qry = "
SELECT Student.StudentId, Student.Name, Student.Surname, Student.DateOfBirth, School.Name AS SchoolName, SchoolCategory.SchoolCategoryName
FROM (
Student
LEFT JOIN StudentSchool ON ( StudentSchool.StudentId = Student.StudentId )
)
LEFT JOIN School ON ( School.SchoolId = StudentSchool.SchoolId ) , SchoolCategory
WHERE SchoolCategory.SchoolCategoryId = School.SchoolCategoryId AND Student.isApproved=0
";

$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY Student.StudentId ";break;
	case 1 : $qry.="ORDER BY Student.Surname ";break;
	case 2 : $qry.="ORDER BY Student.DateOfBirth ";break;
	case 3 : $qry.="ORDER BY SchoolName ";break;
	case 4 : $qry.="ORDER BY SchoolCategory.SchoolCategoryName ";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_nstudents']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_GET['sno'])) $i=0; else $i=$_GET['sno'];

if(!isset($_SESSION['p_nstudents'])) $_SESSION['p_nstudents']=10;

$paginator_select=get_paginator_select("nstudents",$pid,$order);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"nstudents"),$page,$pid,$order,"nstudents");

$limit=get_limit("nstudents",$page);

$qry.=$limit;

$rs = mysqli_query($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";
$selord[4]="";

$selord[$order]=" SELECTED";

$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
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
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=114')\" class=\"button\"><br />
</form>
</td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>

$paginator_pages

<form method=\"post\" action=\"?pid=114&a=2\">
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
	".$line['StudentId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	<b>".$line['Name']."</b><br>
	<b>".$line['Surname']."</b><br>
	".$line['DateOfBirth']."
	</td>
	<td bgcolor=\"#FAFAFA\" valign=\"top\">
	<b>".$line['SchoolName']."</b><br>
	<i>".$line['SchoolCategoryName']."</i>
	</td>
	<td bgcolor=\"whitesmoke\" align=\"center\" valign=\"middle\">
	<a href=\"index.php?pid=200&id=".$line['StudentId']."&a=reg\"><font size=\"-1\">view&nbsp;details&nbsp;&raquo;</font></a>
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=114&a=3&id=".$line['StudentId']."\"><img src=\"images/accept.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Approve student\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=114&a=1&id=".$line['StudentId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete student\" /></a>
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
<input type=\"submit\" name=\"sub_action\" value=\"Approve selected\" class=\"button\" />
&nbsp;&nbsp;<input type=\"submit\" name=\"sub_action\" value=\"Delete selected\" class=\"button\" />
</td>
</tr>
</table>

</form>
$paginator_pages";

?>
