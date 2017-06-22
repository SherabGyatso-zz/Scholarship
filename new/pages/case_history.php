<?php
$c="";
// for editing the appliation form
if(isset($_GET['ty']))
  $prm=$_GET['ty'];


$c.="<font class=\"title\">Scholarship</font><br><Br>
";

if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];

$qry="SELECT student.Name, student.DateOfBirth, student.Address, studentscholarship.StudyCourse, studentscholarship.From, studentscholarship.To, studentscholarship.NameOfInstitution, studentscholarship.FormData
FROM student INNER JOIN studentscholarship ON student.StudentId = studentscholarship.StudentId where studentId=".$gssid;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line=mysqli_fetch_array($rs);

$c.="";
	$d=unserialize($line['FormData']);
	$retval.="<br><fieldset><legend><u><b>&nbsp; <u>BIO-DATA</u> &nbsp;</b></u></legend><br>
	<table cellpadding=\"7\" cellspacing=\"0\">";
	  foreach($d as $val_name => $val) {
		if($val_name!="save" && $val_name!="studentid" && $val_name!="stype") {
			$qry="SELECT `Label` FROM `TemplateElements`
			WHERE (`TemplateId`='1' OR `TemplateId`='$andtemplate') AND `Name`='$val_name'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!! 2");
			$line=mysqli_fetch_array($rs);
			if($val_name=='gender')
			 $val=($d['gender']==0)?'Male':'Female';
			$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">".$line['Label']."</td><td width=\"65%\" style=\"border-bottom: 1px dashed #CCCCCC\"><b>$val</b></td></tr>";
	

header("Location:index.php?pid=1000&id=$gssid");
?>