<?php
$c="";

$c.="<font class=\"title\">Your scholarships</font><br><Br>
";
//show existing addresses
if(isset($_GET['id']))
	$studentID=$_GET['id'];
else
	$studentID=$_SESSION['userid'];

$qry = "
SELECT StudentScholarship.StudentScholarshipId, StudentScholarship.ScholarshipId, ScholarshipTypes.Name, StudentScholarship.Status, StudentScholarship.AppTime, Student.StudentId, Student.Name as SName, Student.Surname 
FROM (`StudentScholarship` LEFT JOIN `Student` USING(StudentId)) LEFT JOIN `ScholarshipTypes` ON(ScholarshipTypes.ScholarshipId=StudentScholarship.ScholarshipId) WHERE StudentScholarship.StudentId = $studentID";


$rs = mysqli_query ($db,$qry) or die ("error<bR>$qry");
//echo mysqli_num_rows($rs);
if(mysqli_num_rows($rs)<1) {
	header("Location: index.php?ewn=230");
	exit();
}
$c.="

<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Scholarship</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Status</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Applied time</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";

while ($line = mysqli_fetch_array($rs)) {
	
	$when=date("m.d.y",$line['AppTime'])."&nbsp;&nbsp;".date("H:i",$line['AppTime']);
	
	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['StudentScholarshipId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['StudentScholarshipId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Name']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Status']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	$when
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=203&id=".$line['StudentScholarshipId']."\"><img src=\"images/page_go.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Proceed to scholarship\" /></a>
	</td>
	</tr>
	";
}

$c.="</table><br>
";

?>