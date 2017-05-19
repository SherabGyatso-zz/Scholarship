<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="104";
	$lqry="";
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$dob=$_POST['birth_y']."-".$_POST['birth_m']."-".$_POST['birth_d'];
		$qry = "
		INSERT INTO `Student` ( `StudentId` , `Name` , `Surname` , `DateOfBirth` , `SecondarySchoolFinishYear` , `Password` , `Address` ) 
		VALUES (
		'', '".$_POST['sname']."', '".$_POST['ssurname']."', '$dob', '".$_POST['sec_s_f_y']."', '".$_POST['spassword']."', '".$_POST['saddress']."'
		)";
		$rs=mysql_query($qry,$db) or die("Database error 1 - $qry");
		$lqry.=$qry."\n\r";
		$qry="SELECT StudentId FROM Student 
		WHERE Name='".$_POST['sname']."' AND Surname='".$_POST['ssurname']."' AND DateOfBirth='$dob'
		";
		$rs=mysql_query($qry,$db) or die("Database error 2");
		$line=mysqli_fetch_array($rs);
		$student_id=$line['StudentId'];
		$qry="INSERT StudentSchool ( `Id` , `StudentId` , `SchoolId` , `YearOfStart` , `GradeOfStart` ) 
		VALUES (
		'', '$student_id', '".$_POST['sschoolname']."', '".$_POST['syos']."', '".$_POST['sgos']."'
		)";
		$rs=mysql_query($qry,$db) or die("Database error 3");
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],10,$lqry);
		$ewnid=212;
		if($_POST['next_opt']==0) $next_pid="104";
		if($_POST['next_opt']==1) $next_pid="105&a=a";	
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$dob=$_POST['birth_y']."-".$_POST['birth_m']."-".$_POST['birth_d'];
		$qry = "
		UPDATE `Student` 
		SET 
		`Name` = '".$_POST['sname']."', 
		`Surname` = '".$_POST['ssurname']."', 
		`Password` = '".$_POST['spassword']."', 
		`email` = '".$_POST['e-mail']."', 
		`DateOfBirth` = '".$dob."',
		`SecondarySchoolFinishYear` = '".$_POST['sec_s_f_y']."', 
		`Address` = '".$_POST['saddress']."' 
		WHERE `StudentId`='".$_POST['id']."'";
		$rs=mysqli_query($db,$qry) or die("Database error 4");
		$lqry.=$qry."\n\r";
		$qry = "
		UPDATE `StudentSchool` 
		SET 
		`SchoolId` = '".$_POST['sschoolname']."', 
		`YearOfStart` = '".$_POST['syos']."', 
		`GradeOfStart` = '".$_POST['sgos']."' 
		WHERE `StudentId`='".$_POST['id']."'";
		$rs=mysqli_query($db,$qry) or die("Database error 5");
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],11,$lqry);
		$ewnid=213;	
	}
	header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
	exit();
}
$title="Add new students";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit Student";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=105&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=105&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM Student WHERE StudentId='".$_GET['id']."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$name_value=$line['Name'];
		$surname_value=$line['Surname'];
		$dob_value=$line['DateOfBirth'];
		$email_value = $line['email'];
		$ssfy_value=$line['SecondarySchoolFinishYear'];
		$pass_value=$line['Password'];
		$address_value=$line['Address'];
		$qry = "SELECT * FROM StudentSchool WHERE StudentId='".$_GET['id']."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$sid_value=$line['SchoolId'];
		$yos_value=$line['YearOfStart'];
		$gos_value=$line['GradeOfStart'];
	}
}

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
<b>Name:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"sname\" maxlength=\"255\" class=\"inputbox\" value=\"$name_value\" /><br>
<b>Surname (login):</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"ssurname\" maxlength=\"255\" class=\"inputbox\" value=\"$surname_value\" /><br>
<b>Password:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"spassword\" maxlength=\"255\" class=\"inputbox\" value=\"$pass_value\" /><br><br />


<b>Email:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"e-mail\" maxlength=\"255\" class=\"inputbox\" value=\"$email_value\" /><br><br />
<b>Date of birth:</b>&nbsp;<font class=\"info\">Year/Month/Day</font>&nbsp;&nbsp;<br />";
$dob_array=explode("-",$dob_value);

$val=0;
$form.=year_select("birth_y",1970, date("Y"),$dob_array[0]);
$form.="&nbsp;";
$form.=month_select("birth_m",$dob_array[1]);
$form.="&nbsp;";
$form.=day_select("birth_d",$dob_array[2]);
$form.="
<br>
<b>Secondary School Finish year:</b>&nbsp;&nbsp;";
$form.=year_select("sec_s_f_y",1970,date("Y"),$ssfy_value);
$form.="<br><br />

<b>Addres:</b><br />
<textarea name=\"saddress\">$address_value</textarea>
<br /><br />

<b>School:</b>&nbsp;&nbsp;
<select name=\"sschoolname\" size=\"1\">
";

$qry2 = "
SELECT School.SchoolId, School.Name, School.SchoolCategoryId, SchoolCategory.SchoolCategoryName
FROM School
LEFT JOIN SchoolCategory ON ( SchoolCategory.SchoolCategoryId = School.SchoolCategoryId ) 
WHERE 1 
ORDER BY SchoolCategory.SchoolCategoryName, School.Name
";
$rs2 = mysqli_query ($db,$qry2) or die ("DB Error!!!");
$c_sCID="";
$sel="";
while($line2=mysqli_fetch_array($rs2)) {
	//if($line2['SchoolCategoryId']==$scid_value) $sel=" selected"; else $sel="";
	if($line2['SchoolCategoryId']!=$c_sCID) {
		$form.="</optgroup><optgroup label=\"".$line2['SchoolCategoryName']."\">";
		$c_sCID=$line2['SchoolCategoryId'];
	}
	if($line2['SchoolId']==$sid_value) $sel=" selected"; else $sel="";
	$form.="<option value=\"".$line2['SchoolId']."\"$sel>".$line2['Name']."</option>\n";
}

$form.="
</select><br />
<b>Year of start:</b>&nbsp;&nbsp;";
$form.=year_select("syos",1970,date("Y"),$yos_value);
$form.="<br>
<b>Grade of start:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"10\" name=\"sgos\" maxlength=\"4\" class=\"inputbox\" value=\"$gos_value\" /><br>
<br>
";

$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add new student<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=104\">&laquo;&nbsp;&nbsp;back to the students list</a>
";

?>