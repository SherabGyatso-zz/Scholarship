<?php

if(!isset($_GET['id']) && $_SESSION['utype']==2) $studentID=$_SESSION['userid']; else $studentID=$_GET['id'];

$a="normal";
if(isset($_GET['a']) && $_GET['a']=="reg") $a="reg";

$qry = "SELECT * FROM Student WHERE StudentId='$studentID'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);
$name_value=$line['Name'];
$surname_value=$line['Surname'];
$dob_value=$line['DateOfBirth'];
$gender = $line['Gender'];
$ssfy_value=$line['SecondarySchoolFinishYear'];
$pass_value=$line['Password'];

$email_value=$line['email'];
$remarks_value=isset($line['remarks']);

$address_value=$line['Address'];
$qry = "SELECT * FROM StudentSchool WHERE StudentId='$studentID'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);
$sid_value=$line['SchoolId'];
$yos_value=$line['YearOfStart'];
$gos_value=$line['GradeOfStart'];
$qry = "
SELECT School.SchoolId, School.Name, School.Address, School.SchoolCategoryId, SchoolCategory.SchoolCategoryName
FROM School
LEFT JOIN SchoolCategory ON ( SchoolCategory.SchoolCategoryId = School.SchoolCategoryId ) 
WHERE School.SchoolId='$sid_value'
";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);
$schoolname_value=$line['Name'];
$schoolcat_value=$line['SchoolCategoryName'];
$schooladdress_value=$line['Address'];

$c="";

$title="Student details";
if(isset($_SESSION['utype']) && $_SESSION['utype']==2) $title="Your profile";
$c.="<font class=\"title\">$title</font><br><Br>";

$add_info="";
if($a=="reg") {
	$add_info="
	<tr>
	<td bgcolor=\"#FAFAFA\" width=\"30%\" valign=\"top\">
	<b>
	Email:<br />
	Remarks:<br />
	<br />
	<br />
	</td>
	<td bgcolor=\"#FAFAFA\" valign=\"top\">
	$email_value<br />
	$remarks_value
	</td>
	</tr>
	<tr><td colspan=\"2\" bgcolor=\"whitesmoke\" style=\"font-size: 2px\">&nbsp;</td></tr>
	";
	
	$add_info2="
		<tr><td colspan=\"2\" bgcolor=\"whitesmoke\"><b>Profile has not been approved yet</b><br>
		<a href=\"?pid=114&a=3&id=$studentID\"><img src=\"images/accept.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Approve student\" align=\"absmiddle\" />&nbsp;&nbsp;Clik here to approve the student</a></td></tr>
	";
}

$c.="
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
$add_info2
<tr>
<td bgcolor=\"#FAFAFA\" width=\"30%\">
<b><u>ID</u><br>
Name<br />
Surname<br />
Password<br />
<br />
Date of Birth<br />
Gender<br />
E-mail</b>
</td>
<td bgcolor=\"#FAFAFA\">
$studentID<br />
$name_value<br />
$surname_value<br />
$pass_value<br /><br />
$dob_value<br />
$gender<br />
$email_value
</td>
</tr>
<tr><td colspan=\"2\" bgcolor=\"whitesmoke\" style=\"font-size: 2px\">&nbsp;</td></tr>
$add_info
<tr>
<td bgcolor=\"#FAFAFA\">
<b>Address</b>
</td>
<td bgcolor=\"#FAFAFA\">
".nl2br($address_value)."
</td>
</tr>

<tr>
<td bgcolor=\"#FAFAFA\">
<b>Secondary school finish year</b>
</td>
<td bgcolor=\"#FAFAFA\">
$ssfy_value
</td>
</tr>
<tr><td colspan=\"2\" bgcolor=\"whitesmoke\" style=\"font-size: 2px\">&nbsp;</td></tr>
<tr>
<td bgcolor=\"#FAFAFA\">
<b>School name<br />
School category<br />
<br />
Year of start<br />
Grade of start
<br />
School address</b>
</td>
<td bgcolor=\"#FAFAFA\">
$schoolname_value<br />
$schoolcat_value<br /><br />
$yos_value<br />
$gos_value<br />
".nl2br($schooladdress_value)."
</td>
</tr>

</table><br>
<br>";

if(isset($_SESSION['utype']) && $_SESSION['utype']!=2) $c.="<a href=\"index.php?pid=105&a=e&id=$studentID\">&laquo;&nbsp;&nbsp;Edit student</a><br>
<a href=\"index.php?pid=104\">&laquo;&nbsp;&nbsp;back to the students list</a>
"; else if($a=="reg") $c.="<a href=\"index.php?pid=114\">&laquo;&nbsp;&nbsp;back to the students list</a>
";

?>