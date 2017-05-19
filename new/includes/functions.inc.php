<?php

$h = "localhost";
$d = "scholarship";
$u = "root";
$p = "123";
$DBADMIN = "root";
$ADMPASSWORD = "123";

function dbconnect($h,$u,$p,$d) {

	$db=mysqli_connect($h, $u, $p, $d) or die ("Can not connect to database");
	mysqli_select_db ($db, $d) or die ("Can not select db");

	return $db;

}

function display_ewn($id,$mode) {

	if($id<100) {
		$color="#C30F18";
		$img="error.gif";
		$t="ERROR:&nbsp;&nbsp;&nbsp;";
	} else if($id>=100 && $id<200) {
		$color="#FFCC33";
		$img="warning.gif";
		$t="WARNING:&nbsp;&nbsp;&nbsp;";
	} else if($id>=200) {
		$color="#0031a1";
		$img="notice.gif";
		$t="NOTICE:&nbsp;&nbsp;&nbsp;";
	}

	$src="";
	if($mode==1) $src="../";

	$ewns = array(
		1 => "Login/password/status is incorrect. Please try again",
		2 => "Access denied",
		3 => "Selected Scholarship has been already applied",
		4 => "Your profile has not been approved yet",
		5 => "Access to this scholarship is denied (Not submitted by logged user)",
		100 => "Scholarship type was not selected. Choose one and click Next",
		101 => "Can not delete student - please remove scholarships related with the student first",
		102 => "Can not delete school - please remove/modify students related with the school first",
		103 => "Can not delete address - please remove/modify students/schools related with the address first",
		104 => "Can not delete school category - please remove/modify schools related with the school category first",
		200 => "School category has beed successfully deleted",
		203 => "Selected school category (categories) has beed successfully deleted",
		201 => "New school category has been successfully added",
		202 => "School category has beed successfully modified",
		204 => "New address has been successfully added",
		205 => "Address has beed successfully modified",
		206 => "Address has beed deleted",
		207 => "Selected address(es) has beed successfully deleted",
		208 => "New school has been successfully added",
		209 => "School has been successfully edited",
		210 => "School has been deleted",
		211 => "Selected school(s) has been successfully deleted",
		212 => "New student has been successfully added",
		213 => "Student has been successfully edited",
		214 => "Student has been deleted",
		215 => "Selected student(s) has been successfully deleted",
		216 => "New DOE Officer has been successfully added",
		217 => "DOE Officer has been successfully edited",
		218 => "DOE Officer has been deleted",
		219 => "Selected DOE Officer(s) has been successfully deleted",
		220 => "New element has been sucessfully added",
		221 => "Element has been successfully deleted",
		222 => "Element has been successfully edited",
		223 => "Element order has been updated",
		224 => "Scholarship(s) has been successfully deleted",
		225 => "Scholarship has been successfully deleted",
		226 => "Selected student(s) was(were) approved and confirmation email has been sent",
		227 => "Selected student(s) was(were) deleted and suitable email has been sent",
		228 => "Selected student was deleted and suitable email has been sent",
		229 => "Selected student was approved and confirmation email has been sent",
		230 => "You have not applied for any of the scholarships yet",
		231 => "Your application form has been succesfully applied",
		232 => "News has been successfully added",
		233 => "News has been successfully edited",
		234 => "News has been deleted",
		235 => "Selected news(es) has been successfully deleted",
		236 => "Course has been successfully edited",
		237 => "Course has been deleted"
	);

	echo("<div align=\"center\">
	<table width=\"80%\" cellpadding=\"5\" cellspacing=\"0\" style=\"border: 2px solid $color\">
	<tr>
	<td width=\"26\" valign=\"top\"><img src=\"".$src."images/$img\" width=\"16\" height\"16\" border=\"0\"></td>
	<td><b>$t</b>&nbsp;&nbsp;<font color=\"$color\"><b>".$ewns[$id]."</b></td>
	<td width=\"26\" valign=\"top\"><img src=\"".$src."images/$img\" width=\"16\" height\"16\" border=\"0\"></td>
	</tr></table><br /></div>
	");

}

function display_content($leftcontent,$content) {
	echo($leftcontent."<div class=\"proper-content\">".$content."</div>");
}

function get_left_content($user,$utype) {
	global $utypes;
	$ustatus = $utypes[$utype];

	$login_box = "
	<div style=\"text-align:left\">
	Welcome<br />
	<font class=\"title2\"><b>$user</b></font><br /><br />

	Your status is:<br />
	<font class=\"title2\"><b>$ustatus</b></font><br /><br />

	<input type=\"button\" onclick=\"window.location='index.php?logout=1'\" value=\"LogOut\" class=\"button\">
	</div>";

	if($utype==1) $menu_box = "

	<a href=\"?pid=300\">Registrations</a><br><br />
	
	<a href=\"?pid=114\">Accept Registrations</a><br><br />
	
	<a href=\"?pid=100\">Manage school categories</a><Br>
	<a href=\"?pid=102\">Manage schools</a><br><br />
     <a href=\"?pid=118\">Manage Course</a><Br>
	<a href=\"?pid=104\">Manage students</a><br><br />

	<a href=\"?pid=108&clk=0\"><b>SCHOLARSHIPS</b></a><br><br />
	
	 <a href=\"?pid=120\">Manage Scholarship Types</a><Br><br />
	 
	<a href=\"?pid=117\"><b>Export</b></a><br><br /><br />
	<a href=\"?pid=106\">Edit templates</a><br>

	<a href=\"?pid=115\">Manage news</a><br>


	"; else if($utype==0) $menu_box = "

	<a href=\"?pid=1\">Manage DOE officers</a><Br>
	<a href=\"?pid=3\">Logs</a><Br><br />

	<a href=\"?pid=300\">Registrations</a><br><br />
	
	<a href=\"?pid=114\">Accept Registrations</a><br><br />

	
	<a href=\"?pid=100\">Manage school categories</a><Br>
	<a href=\"?pid=102\">Manage schools</a><br><br />
    <a href=\"?pid=118\">Manage Course</a><Br>
	<a href=\"?pid=104\">Manage students</a><br><br />

	<a href=\"?pid=108&clk=0\"><b>SCHOLARSHIPS</b></a><br><br />
	
	 <a href=\"?pid=120\">Manage Scholarship type</a><Br><br />
	 
	<a href=\"?pid=117\"><b>Export</b></a><br><br />
	<a href=\"?pid=106\">Edit templates</a><br>
     
	<a href=\"?pid=115\">Manage news</a><br>

	"; else if($utype==2) $menu_box ="

	<a href=\"?pid=200\">Your profile</a><Br><br>

	<a href=\"?pid=201\">Apply for scholarship</a><Br>
	<a href=\"?pid=202\">Applied scholarships</a><Br>

	";

	$retval = "
			<div style=\"float: left;width:210px\">
				<div class=\"login-box\">$login_box</div>
				<div class=\"menu-box\"><h3>MENU</h3>$menu_box</div>
			</div>
	";

	return $retval;
}

function get_content($db,$pid,$utype) {
	$retval = "fail";

	if($pid!=0 && $pid!=300) {
		if($utype==2 && $pid<200) return 0;
		if($utype==1 && $pid<100) return 0;
	}

	switch($pid) {
		case 0 : $incfile="pages/home.php"; break;
		case 1 : $incfile="pages/doeofficers.php"; break;
		case 2 : $incfile="pages/doeofficers_add_edit.php"; break;
		case 3 : $incfile="pages/logs.php"; break;
		case 100 : $incfile="pages/schoolcategories.php"; break;
		case 101 : $incfile="pages/addresses.php"; break;
		case 102 : $incfile="pages/schools.php"; break;
		case 103 : $incfile="pages/schools_add_edit.php"; break;
		case 104 : $incfile="pages/students.php"; break;
		case 105 : $incfile="pages/students_add_edit.php"; break;
		case 106 : $incfile="pages/s_manage_templates.php"; break;
		case 107 : $incfile="pages/s_preview_template.php"; break;
		case 108 : $incfile="pages/s_scholarships.php"; break;
		case 109 : $incfile="pages/s_proceed.php"; break;
		case 110 : $incfile="pages/s_report.php"; break;
		case 111 : $incfile="pages/s_edit_template.php"; break;
		case 112 : $incfile="pages/s_add_element.php"; break;
		case 113 : $incfile="pages/s_edit_element.php"; break;
		case 114 : $incfile="pages/new_students.php"; break;
		case 115 : $incfile="pages/news.php"; break;
		case 116 : $incfile="pages/news_add_edit.php"; break;
		case 117 : $incfile="pages/export.php";break;
		case 118 : $incfile="pages/course.php";break;
		case 119 : $incfile="pages/course_add_edit.php"; break;
		case 120 : $incfile="pages/scholarship_type.php";break;
		case 121 : $incfile="pages/scholarship_type_add_edit.php"; break;
		case 200 : $incfile="pages/student_details.php"; break;
		case 201 : $incfile="pages/student_scholarship_apply.php"; break;
		case 202 : $incfile="pages/student_view_scholarships.php"; break;
		case 203 : $incfile="pages/student_proceed_scholarship.php"; break;
		case 204 : $incfile="pages/student_report_scholarship.php"; break;
		case 205 : $incfile="pages/view_full_news.php"; break;
		case 300 : $incfile="pages/registration.php"; break;
		case 1000: $incfile="pages/edit_application_frm.php"; break;
	}

	include($incfile);

	$retval=$c;

	return $retval;
}

function loguser($db,$login,$pass,$utype) {
	$retval=0;

	switch($utype) {
		case 0 : $table="Admins"; $field="AdminId"; $logfield="Name"; break;
		case 1 : $table="DoeOfficer"; $field="DoeOfficerId"; $logfield="Name"; break;
		case 2 : $table="Student"; $field="StudentId"; $field2="isApproved"; $logfield="Surname"; break;
	}

	$fields=$field;
	if(isset($field2))
	  $fields.=", $field2";

	$qry = "SELECT $fields FROM $table WHERE $logfield='$login' AND Password='$pass'";
  	$rs = mysqli_query($db,$qry);
	$count = mysqli_num_rows($rs);

	if($count>0) {
		$line=mysqli_fetch_array($rs);
		$retval=$line[$field];
		if($utype==2 && $line['isApproved']==0) $retval = -1;
	}

	return $retval;
}

function get_reg_info() {
	return "<fieldset style=\"
	margin-bottom:20px;
	margin-top:25px;
	padding-bottom:15px;
	padding-left:20px;
	padding-right:8px;
	padding-top:3px;
	text-align:left;
	width:165px;\">
	<legend><b>REGISTRATION</b></legend>
	<br><font class=\"green\">Don't have an account yet?</font><br><br>
	<a href=\"?pid=300\"><img src=\"images/user_add.gif\" border=\"0\" align=\"absmiddle\" />&nbsp;&nbsp;<b>Register now</b><br></a><br>
	<b>You will be given acces to:</b><br /><br />

	<li>View your profile</li><br />
	<li>Apply for scholarships</li><br />
	<li>Check status of your application</li>

	</fieldset>";
}

function get_login_form($db) {

	return "
	<div style=\"float: left;width:210px\">
	<div class=\"login-box\">

	<font class=\"title2\"><b>SIGN IN</b><br /></font>
<br />
	<form action=\"index.php\" method=\"post\" id=\"loginform\">

	<input type=\"text\" name=\"login\" id=\"login\" class=\"inputbox\" value=\"login\" onclick=\"this.value=''\" /><br />
	<input type=\"password\" name=\"pass\" id=\"pass\" class=\"inputbox\" value=\"pass\" onclick=\"this.value=''\" />
	<br />
	<br />
	<div style=\"text-align: left;margin:0px 10px;\">
	<b>Choose your status:</b><br>
	<img src=\"images/user_gray.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" />&nbsp;<input type=\"radio\" name=\"utype\" id=\"ut1\" value=\"0\" />&nbsp;&nbsp;Administrator<br />
	<img src=\"images/user_suit.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" />&nbsp;<input type=\"radio\" name=\"utype\" id=\"ut2\" value=\"1\" />&nbsp;&nbsp;DoE Manager<br />
	<img src=\"images/user_green.gif\" width=\"16\" height=\"16\" align=\"absmiddle\" />&nbsp;<input type=\"radio\" name=\"utype\" id=\"ut3\" value=\"2\" />&nbsp;&nbsp;Student
	</div>
	<input type=\"button\" value=\"Log In\" class=\"button\" onclick=\"check_submit()\" />
	<input type=\"hidden\" name=\"logging\" value=\"1\" />
	</form>

	</div>
	".get_reg_info()."
	</div>
	";
}

function get_news_list($db,$count) {

	$retval ="";

	$qry = "SELECT id FROM news WHERE 1";
	//echo $qry;
	$rs = mysqli_query($db,$qry);
	while($line = mysqli_fetch_array($rs)) {
		$retval .= get_single_news_short($db,$line['id']);
	}

	return $retval;
}

function get_single_news_short($db,$id) {
	$retval="";
	$qry = "SELECT * FROM news WHERE id=$id";
	$rs = mysqli_query($db,$qry);
	$line = mysqli_fetch_array($rs);
	$retval .="<b>".$line['title']."</b><br><font style=\"color: grey;font-size: 10px\">".date('d.m.Y',$line['dateadded'])."</font><br />".$line['short']."<br />
<a href=\"?pid=205&id=$id\">read more &raquo;</a><br><Br>";

	return $retval;
}

function year_select($iname,$start,$end,$sel_value) {
	$retval="";
	$retval.="<select name=\"$iname\" size=\"1\">";
	$sel="";
	for($i=$start;$i<=$end;$i++) {
		if($i==$sel_value) $sel=" SELECTED"; else $sel="";
		$retval.="<option value=\"$i\"$sel>$i</option>";
	}
	$retval.="</select>";

	return $retval;
}

function get_months_table() {
	global $months;
	return $months;

}

function month_select($iname,$sel_value) {
	global $months;
	$retval="";
	$retval.="<select name=\"$iname\" size=\"1\">";
	$sel="";
	for($i=1;$i<=12;$i++) {
		if($i<10) $selch="0".$i; else $selch=$i;
		if($selch==$sel_value) $sel=" SELECTED"; else $sel="";
		$retval.="<option value=\"";
		if($i<10) $opt_val="0$i"; else $opt_val=$i;
		$retval.="$opt_val\"$sel>".$months[$i]."</option>";
	}
	$retval.="</select>";

	return $retval;
}

function day_select($iname,$sel_value) {
	$retval="";
	$retval.="<select name=\"$iname\" size=\"1\">";
	$sel="";
	for($i=1;$i<=31;$i++) {
		if($i<10) $selch="0".$i; else $selch=$i;
		if($selch==$sel_value) $sel=" SELECTED"; else $sel="";
		$retval.="<option value=\"";
		if($i<10) $opt_val="0$i"; else $opt_val=$i;
		$retval.="$opt_val\"$sel>$i</option>";
	}
	$retval.="</select>";

	return $retval;
}

function add_log($db,$uid,$utype,$type,$query) {
	$now = time();
	if($utype==0) {
		$utable = 'Admins';
		$idfield= 'AdminId';
	} else if($utype==1) {
		$utable = 'DoeOfficer';
		$idfield= 'DoeOfficerId';
	}
	$qry="SELECT Name FROM $utable WHERE $idfield=$uid";
	$rs = mysqli_query($db,$qry);
	$line = mysqli_fetch_array($rs);
	$name=$line['Name'];
	$qry_query=addslashes($query);
	$qry = "INSERT INTO `logs` ( `Id` , `UserId` , `UserName` , `UserType` , `TypeId` , `Time` , `Query` )
	VALUES ( '', '$uid', '$name', '$utype', '$type', '$now', '$qry_query' )";
	if(mysqli_query($db,$qry)) return true;
}


//templates managment
function get_form($db,$templateid,&$form) {
   
	$qry="SELECT * FROM TemplateElements WHERE TemplateId='$templateid'";
	$rs = mysqli_query($db,$qry) or die ("DB Error!!!");
	while ($line = mysqli_fetch_array($rs)) {
		get_field($form,$line);
	}
}

function get_field(&$form,$params,$readonly=FALSE) {
/*  echo '<pre>';
  print_r($params);
  echo '</pre>';*/
	if($readonly) $params['Attributes'] = " READONLY";
	switch($params['Type']) {
		case "text" :
			$form->addElement('text',$params['Name'],$params['Label'],$params['Attributes']);
			break;
		case "password" :
			$form->addElement('password',$params['Name'],$params['Label'],$params['Attributes']);
			break;
		case "textarea" :
			$form->addElement('textarea',$params['Name'],$params['Label'],$params['Attributes']);
			break;
		case "select" :
			$options=explode("||",$params['Options']);
			$form->addElement('select',$params['Name'],$params['Label'],$options,$params['Attributes']);
			break;
		case "hidden" :
			$form->addElement('hidden',$params['Name'],$params['Value'],$params['Attributes']);
			break;
		case "checkbox" :
			$form->addElement('checkbox',$params['Name'],$params['Label'],$params['Text'],$params['Attributes']);
			break;
	}
	if($params['required']==1) $form->addRule($params['Name'],'This field is required','required');
}

function submit_application_form($d) {
	$studentid=$d['studentid'];
	$scholarshiptype=$d['stype'];
	$edit_type = $_GET['ty'];
	if(isset($_POST['edit_type']))
	  $edit_type = $_POST['edit_type'];
	$gssid = $_GET['id'];

	$now=time();

	global $db;
	if($edit_type=='a') //adding new form
	{
	$form_data_array = array();
	foreach($d as $key => $value) {
	   
		if($key != 'photo' and $key != 'MAX_FILE_SIZE') 
		$form_data_array[$key] = $value;
	}

	$form_data=serialize($form_data_array);

	$qry="INSERT INTO `StudentScholarship` ( `StudentScholarshipId` , `StudentId` , `ScholarshipId` , `AppTime` , `FormData` )
	VALUES (
	'', '$studentid', '$scholarshiptype', '$now', '$form_data'
	)";
	//echo $qry;
	$rs = mysqli_query ($db,$qry) or die (mysql_error() ." DB Error!!!");
$qry="SELECT StudentScholarshipId FROM StudentScholarship WHERE StudentId=$studentid AND ScholarshipId=$scholarshiptype AND AppTime=$now";

	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$line = mysqli_fetch_array($rs);
   if($_FILES['photo']['type']=="image/pjpeg" || $_FILES['photo']['type']=="image/jpeg" || $_FILES['photo']['type']=="image/jpg") 			
	{
		$uploaddir = "sholarshipphotos/";
		$uploadfile = $uploaddir . $line['StudentScholarshipId'] . ".jpg";

		if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) die("UPLOAD ERROR!");
	}

  }else //editing existing form
    {
	$form_data=serialize($d);
	//echo $form_data;
	$qry = "UPDATE `StudentScholarship` SET 
	`FormData` = '".$form_data."' 
	 WHERE `StudentScholarshipId` =".$gssid."
	";
	//echo $qry;
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

	}
	
	}

function submit_status($d) {
	global $db;
	switch($d['status']) {
		case 0 : $status="Selected";break;
		case 1 : $status="Alternate";break;
		case 2 : $status="Rejected";break;
		case 3 : $status="Closed";break;
		case 4 : $status="Discontinued";break;
	}

	$qry="
	UPDATE `StudentScholarship` SET `Status` = '$status',
	`StudyCourse` = '".$d['study_course']."',
	`courseid` = ".$d['course'].",
	`specialize` = '".$d['spc']."',
	`From` = '".$d['from']."',
	`To` = '".$d['to']."',
	`history` = '".$d['history']."',
	`YearReturned` = '".$d['year_ret']."' WHERE `StudentScholarshipId` =".$d['ssid']."
	";
	$stu_qry = "
	 UPDATE `student` SET `XiiRollno`=".$d['rollno']." where StudentId = ".$d['sid']."";
	If($d['rollno']!=0)
	$rst = mysqli_query($db,$stu_qry) or die("DB Error Student".$stu_qry);
	
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!".mysqli_error());
}

function register_user($p) {
	global $db;
	$lqry="";

	$dob=$p['dob_year']."-".$p['dob_month']."-".$p['dob_day'];
	$qry = "
	INSERT INTO `Student` ( `StudentId` , `Name` , `Surname` , `DateOfBirth` , `SecondarySchoolFinishYear` , `Password` , `Address` , `isApproved` , `regRemarks` , `email`,`Gender` )
	VALUES (
	'', '".$p['name']."', '".$p['surname']."', '$dob', '".$p['sec_school_f_year']."', '".$p['pass']."', '".addslashes($p['address'])."' , '0' , '".$p['remarks']."' , '".$p['email']."','".$p['gender']."'
	)";
	$rs=mysqli_query($db,$qry) or die(mysqli_error()."Database error 1 - $qry");
	$lqry.=$qry."\n\r";
	$qry="SELECT StudentId FROM Student
	WHERE Name='".$p['name']."' AND Surname='".$p['surname']."' AND DateOfBirth='$dob'
	";
	$rs=mysqli_query($db,$qry) or die("Database error 2");
	$line=mysqli_fetch_array($rs);
	$student_id=$line['StudentId'];
	$qry="INSERT StudentSchool ( `Id` , `StudentId` , `SchoolId` , `YearOfStart` , `GradeOfStart` )
	VALUES (
	'', '$student_id', '".$p['school']."', '".$p['year_of_start']."', '".$p['grade_of_start']."'
	)";
	$rs=mysqli_query($db,$qry) or die("Database error 3");
	$lqry.=$qry;
	//if($_SESSION['utype']==1) add_log($db,$_SESSION['userid'],10,$lqry);
}

function submit_report($d) {
	global $db;
	
	//assigning zero to the amount field if it is empty
	
	if($d['1i_y1_amt']=="") $d['1i_y1_amt']=0; 
	
	$d['1i_y1_amt']=str_replace(",","",$d['1i_y1_amt']);
	
	if($d['21i_y1_amt']=="") $d['21i_y1_amt']=0;
		$d['21i_y1_amt']=str_replace(",","",$d['21i_y1_amt']);
	if($d['fi_y1_amt']=="") $d['fi_y1_amt']=0;
		$d['fi_y1_amt']=str_replace("need","",$d['fi_y1_amt']);
	if($d['1i_y2_amt']=="") $d['1i_y2_amt']=0;
		$d['1i_y2_amt']=str_replace(",","",$d['1i_y2_amt']);
	if($d['21i_y2_amt']=="") $d['21i_y2_amt']=0;
		 $d['21i_y2_amt']=str_replace(",","",$d['21i_y2_amt']);	
	if($d['fi_y2_amt']=="") $d['fi_y2_amt']=0;
		$d['fi_y2_amt']=str_replace(",","",$d['fi_y2_amt']);
	if($d['1i_y3_amt']=="") $d['1i_y3_amt']=0;
		$d['1i_y3_amt']=str_replace(",","",$d['1i_y3_amt']);	
	if($d['21i_y3_amt']=="") $d['21i_y3_amt']=0;
		$d['21i_y3_amt']=str_replace(",","",$d['21i_y3_amt']);	 
	if($d['fi_y3_amt']=="") $d['fi_y3_amt']=0;
		$d['fi_y3_amt']=str_replace(",","",$d['fi_y3_amt']);	
	if($d['1i_y4_amt']=="") $d['1i_y4_amt']=0;
		$d['1i_y4_amt']=str_replace(",","",$d['1i_y4_amt']);	
	if($d['21i_y4_amt']=="") $d['21i_y4_amt']=0;
		$d['21i_y4_amt']=str_replace(",","",$d['21i_y4_amt']);	
	if($d['fi_y4_amt']=="") $d['fi_y4_amt']=0;
		 $d['fi_y4_amt']=str_replace(",","",$d['fi_y4_amt']);	
	if($d['1i_y5_amt']=="") $d['1i_y5_amt']=0;
		$d['1i_y5_amt']=str_replace(",","",$d['1i_y5_amt']);	
	if($d['21i_y5_amt']=="") $d['21i_y5_amt']=0;
		$d['21i_y5_amt']=str_replace(",","",$d['21i_y5_amt']);	
	if($d['fi_y5_amt']=="") $d['fi_y5_amt']=0;
		$d['fi_y5_amt']=str_replace(",","",$d['fi_y5_amt']);
		
	//update to reporting table
	$rep_qry ="update `reporting` set 
	`1i_y1_ddno`='".$d['1i_y1_ddno']."',
	`1i_y1_amt`=".$d['1i_y1_amt'].",
	`2i_y1_ddno`='".$d['2i_y1_ddno']."',
	`21i_y1_amt`=".$d['21i_y1_amt'].",
	`fi_y1_ddno`='".$d['fi_y1_ddno']."',
	`fi_y1_amt`=".$d['fi_y1_amt'].",
	`1i_y2_ddno`='".$d['1i_y2_ddno']."',
	`1i_y2_amt`=".$d['1i_y2_amt'].",
	`2i_y2_ddno`='".$d['2i_y2_ddno']."',
	`21i_y2_amt`=".$d['21i_y2_amt'].",
	`fi_y2_ddno`='".$d['fi_y2_ddno']."',
	`fi_y2_amt`=".$d['fi_y2_amt'].",
	`1i_y3_ddno`='".$d['1i_y3_ddno']."',
	`1i_y3_amt`=".$d['1i_y3_amt'].",
	`2i_y3_ddno`='".$d['2i_y3_ddno']."',
	`21i_y3_amt`=".$d['21i_y3_amt'].",
	`fi_y3_ddno`='".$d['fi_y3_ddno']."',
	`fi_y3_amt`=".$d['fi_y3_amt'].",
	`1i_y4_ddno`='".$d['1i_y4_ddno']."',
	`1i_y4_amt`=".$d['1i_y4_amt'].",
	`2i_y4_ddno`='".$d['2i_y4_ddno']."',
	`21i_y4_amt`=".$d['21i_y4_amt'].",
	`fi_y4_ddno`='".$d['fi_y4_ddno']."',
	`fi_y4_amt`=".$d['fi_y4_amt'].",
	`1i_y5_ddno`='".$d['1i_y5_ddno']."',
	`1i_y5_amt`=".$d['1i_y5_amt'].",
	`2i_y5_ddno`='".$d['2i_y5_ddno']."',
	`21i_y5_amt`=".$d['21i_y5_amt'].",
	`fi_y5_ddno`='".$d['fi_y5_ddno']."',
	`fi_y5_amt`=".$d['fi_y5_amt']."  
	 Where `StudentScholarshipId`=".$d['ssid']; 
	// echo $rep_qry;
	 mysqli_query ($db, $rep_qry) or die ("DB Error update reporting!!!".mysql_error());
	
	//updating data in studentScholarship table
	$report_data=serialize($d);
    /*echo '<pre>';
	print_r($d);
	echo '</pre>';
	exit;*/
	$str = $d['name_of_edu_inst'];
	$inst = addslashes($str);
	//echo addslashes($report_data);
	$qry="
	UPDATE `StudentScholarship` SET 
	SponsorName= '".$d['sponsor_name']."', 
	category='".$d['category']."', 
	Remarks = '".$d['remarks']."', 
   `ReportData` = '$report_data',
	NameOfInstitution = '".addslashes($d['name_of_edu_inst'])."',
	place ='".$d['place']."'
	WHERE `StudentScholarshipId` =".$d['ssid']."
	";
	//echo $qry;
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!".mysql_error());
	
	
}

function display_form_data($db,$gssid,$prm) {
	$retval="";
	$qry="SELECT * FROM `StudentScholarship` WHERE `StudentScholarshipId`='$gssid'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!! 1");
	$line=mysqli_fetch_array($rs);
	$sc_type=$line['ScholarshipId'];
	$andtemplate=$line['ScholarshipId']+1;
	$d=unserialize($line['FormData']);
	$ppath = "sholarshipphotos/";
	$retval.="<br><fieldset><legend><u><b>&nbsp;Student application form&nbsp;</b></u></legend><br>
	<table cellpadding=\"7\" cellspacing=\"0\">";
	$retval.="<tr><td><b><a href=\"index.php?pid=1000&id=$gssid&ty=e&stype=$sc_type\">Edit</a></td></tr>";
	  $ifphoto = file_exists($ppath.$gssid.".jpg") ? "<tr><td colspan=\"2\">Photo:<br /><img src=\"sholarshipphotos/$gssid.jpg\" width=\"100\" heigth=\"100\"></td></tr>" : "<tr><td colspan=\"2\"><b>no photo submitted</b></td></tr>";
	$retval.=$ifphoto;

	  foreach($d as $val_name => $val) {
		
		if($val_name!="save" && $val_name!="studentid" && $val_name!="stype") {
			$qry="SELECT `Label` FROM `TemplateElements`
			WHERE (`TemplateId`='1' OR `TemplateId`='$andtemplate') AND `Name`='$val_name'";
			$rs = mysqli_query ($db,$qry) or die ("DB Error!!! 2");
			$line=mysqli_fetch_array($rs);
			if($val_name=='gender')
			 $val=($d['gender']==0)?'Male':'Female';
			$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">".$line['Label']."</td><td width=\"65%\" style=\"border-bottom: 1px dashed #CCCCCC\"><b>$val</b></td></tr>";
	
		}
	}

	$retval.="</fieldset></table>";

	return $retval;
}

function getIndex($t,$cmp) {
	for($i=0;$i<count($t);$i++) {
		if(strcmp($t[$i],$cmp)==0) return $i;
	}

	return 0;
}

function report_show1($form_data,$em, $add) {
	$retval="";
	$d=unserialize($form_data);
/*echo '<pre>';
print_r($d);
echo '</pre>';
*/	$retval.="<table cellpadding=\"5\" cellspacing=\"0\">";
	$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">Applicant name & surname</td><td style=\"border-bottom: 1px dashed #CCCCCC\"><b>".$d['applicant_name_surname']."</b></td></tr>";
	$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">Course or Subject</td><td style=\"border-bottom: 1px dashed #CCCCCC\"><b>".$d['course_or_subject']."</b></td></tr>";
	$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">Duration of Course</td><td style=\"border-bottom: 1px dashed #CCCCCC\"><b>".$d['duration_of_course']."</b></td></tr>";
	$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">E-mail Address:</td><td style=\"border-bottom: 1px dashed #CCCCCC\"><b>".$em."</b></td></tr>";
	$retval.="<tr><td style=\"border-bottom: 1px dashed #CCCCCC\">Permanent Address:</td><td style=\"border-bottom: 1px dashed #CCCCCC\"><b>".$add."</b></td></tr>";
	$retval.="</table><br>";

	return $retval;
}

function add_report_elements(&$form,$stype,$rd,$readonly=FALSE) {
	$attribs="";
	if($readonly) $attribs = " READONLY";
	$toi=5;
	for($i=1;$i<=$toi;$i++) {
		$htmlin="</table>
		<div name=\"year_$i\" style=\"DISPLAY: none\" id=\"year_$i\">
		<br />
		<div align=\"left\"><u><b>YEAR $i</b></u></div><br />

		<table>";
		$form->addElement('html',$htmlin);

		$htmlin="<tr><td><font class=\"green\">First Installment of RS</font></td></tr>";
		$form->addElement('html',$htmlin);
		$name="1i_y".$i."_sent_on";
		$elt =& $form->addElement('text',$name,'Sent on:',$attribs);
		$elt->setValue($rd[$name]);
		$name="1i_y".$i."_ddno";
		$elt =& $form->addElement('text',$name,'DD No:',$attribs);
		$elt->setValue($rd[$name]);
		$options = array('No','Yes');
		$name="1i_y".$i."_prev_inst_receipt";
		$elt =& $form->addElement('select',$name,'Previous Installment Receipt received: ',$options,$attribs);
		$elt->setSelected($rd[$name]);
		if($stype==3 || $stype==1) {
			$name="1i_y".$i."_progress_rec";
			$elt =& $form->addElement('select',$name,'Progress Report Received: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
			$name="1i_y".$i."_amt";
			$elt =& $form->addElement('text',$name,'Amount in Rs:',$attribs);
			$elt->setValue($rd[$name]);
		} else {
			$name="1i_y".$i."_secured_required_exam";
			$elt =& $form->addElement('select',$name,'Secured the required percentage in last year examination result: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
			$name="1i_y".$i."_secured_75";
			$elt =& $form->addElement('select',$name,'Secured 75% Attendance during last academic year: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
			$name="1i_y".$i."_amt";
			$elt =& $form->addElement('text',$name,'Amount in Rs:',$attribs);
			$elt->setValue($rd[$name]);
		}

		$htmlin="<tr><td><br /><font class=\"green\">Second Installment of RS</font></td></tr>";
		$form->addElement('html',$htmlin);
		$name="2i_y".$i."_sent_on";
		$elt =& $form->addElement('text',$name,'Sent on:',$attribs);
		$elt->setValue($rd[$name]);
		$name="2i_y".$i."_ddno";
		$elt =& $form->addElement('text',$name,'DD No:',$attribs);
		$elt->setValue($rd[$name]);
		$options = array('No','Yes');
		$name="2i_y".$i."_prev_inst_receipt";
		$elt =& $form->addElement('select',$name,'Previous Installment Receipt received: ',$options,$attribs);
		$elt->setSelected($rd[$name]);
		$name="21i_y".$i."_amt";
		$elt =& $form->addElement('text',$name,'Amount in Rs:',$attribs);
		$elt->setValue($rd[$name]);
		if($stype==3 || $stype==1) {
			$name="2i_y".$i."_progress_rec";
			$elt =& $form->addElement('select',$name,'Progress Report Received: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
		}

		if($stype==3 || $stype==1) {
		$htmlin="<tr><td><br /><font class=\"green\">Third Installment of RS</font></td></tr>";
		$form->addElement('html',$htmlin);
		$name="3i_y".$i."_sent_on";
		$elt =& $form->addElement('text',$name,'Sent on:',$attribs);
		$elt->setValue($rd[$name]);
		$name="3i_y".$i."_ddno";
		$elt =& $form->addElement('text',$name,'DD No:',$attribs);
		$elt->setValue($rd[$name]);
		$options = array('No','Yes');
		$name="3i_y".$i."_prev_inst_receipt";
		$elt =& $form->addElement('select',$name,'Previous Installment Receipt received: ',$options,$attribs);
		$elt->setSelected($rd[$name]);
		$name="3i_y".$i."_progress_rec";
		$elt =& $form->addElement('select',$name,'Progress Report Received: ',$options,$attribs);
		$elt->setSelected($rd[$name]);
		$name="31i_y".$i."_amt";
		$elt =& $form->addElement('text',$name,'Amount in Rs:',$attribs);
		$elt->setValue($rd[$name]);
		}

		$htmlin="<tr><td><br /><font class=\"green\">Final Installement of RS</font></td></tr>";
		$form->addElement('html',$htmlin);
		$name="fi_y".$i."_sent_on";
		$elt =& $form->addElement('text',$name,'Sent on:',$attribs);
		$elt->setValue($rd[$name]);
		$name="fi_y".$i."_ddno";
		$elt =& $form->addElement('text',$name,'DD No:',$attribs);
		$elt->setValue($rd[$name]);
		$options = array('No','Yes');
		$name="fi_y".$i."_prev_inst_receipt";
		$elt =& $form->addElement('select',$name,'Previous Installment Receipt received: ',$options,$attribs);
		$elt->setSelected($rd[$name]);
		$name="fi_y".$i."_amt";
		$elt =& $form->addElement('text',$name,'Amount in Rs:',$attribs);
		$elt->setValue($rd[$name]);
		if($stype==3 || $stype==1) {
			$name="fi_y".$i."_progress_rec";
			$elt =& $form->addElement('select',$name,'Progress Report Received: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
			
		} else {
			$name="fi_y".$i."_exam_results";
			$elt =& $form->addElement('select',$name,'Examination Results received: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
			$name="fi_y".$i."_attend_certif";
			$elt =& $form->addElement('select',$name,'Attendance Certificate received: ',$options,$attribs);
			$elt->setSelected($rd[$name]);
		}

		if($stype==2) {
			$htmlin="<tr><td><br /><font class=\"green\">Tuition Fees</font></td></tr>";
			$form->addElement('html',$htmlin);
			$name="tf_y".$i."_bills_rec";
			$elt =& $form->addElement('text',$name,'Bills Received:',$attribs);
			$elt->setValue($rd[$name]);
			$name="tf_y".$i."_total_amount";
			$elt =& $form->addElement('text',$name,'Total amount on the Bills:',$attribs);
			$elt->setValue($rd[$name]);
			$name="tf_y".$i."_reimbursed_amount";
			$elt =& $form->addElement('text',$name,'Reimbursed Amount:',$attribs);
			$elt->setValue($rd[$name]);
			$name="tf_y".$i."_sent_on";
			$elt =& $form->addElement('text',$name,'Sent on:',$attribs);
			$elt->setValue($rd[$name]);
			$name="tf_y".$i."_dd_no";
			$elt =& $form->addElement('text',$name,'DD No:',$attribs);
			$elt->setValue($rd[$name]);
			$name="tf_y".$i."_pending";
			$elt =& $form->addElement('textarea',$name,'Pending explanation of the bills: ',$attribs);
			$elt->setValue($rd[$name]);
		}

		$htmlin="<tr><td><br /><hr color=\"green\" width=\"100%\" /></td></tr>";
		$form->addElement('html',$htmlin);
		$name="tf_y".$i."_ruppee_amount";
		$elt =& $form->addElement('text',$name,'Total Amount of money sent (Ruppees):',$attribs);
		$elt->setValue($rd[$name]);
		$name="tf_y".$i."_ruppee_pending";
		$elt =& $form->addElement('textarea',$name,'Delay or stop installment reason: ',$attribs);
		$elt->setValue($rd[$name]);

		$htmlin="</table></div><table>";
		$form->addElement('html',$htmlin);
	}
}

function show_scholarship_type($db,$sid) {
	$retval="<font class=\"title2\">Scholarship Type: ";

	$qry="SELECT Name FROM ScholarshipTypes WHERE ScholarshipId='$sid'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$line = mysqli_fetch_array($rs);
	$retval.="&nbsp;&nbsp;<u>".$line['Name']."</u></font><br><BR>";

	return $retval;
}

function get_limit($mode,$page) {
	$limit_set=$_SESSION["p_".$mode];
	$from=$limit_set*($page-1);

	return " LIMIT $from , $limit_set";
	
}

function get_paginator_select($mode,$pid,$order=-1) {
	$limit_set=$_SESSION["p_".$mode];
	if($order==-1) $ord=""; else $ord="&order=$order";
	$sel[5]="";
	$sel[10]="";
	$sel[30]="";
	$sel[50]="";
	$sel[$limit_set]=" SELECTED";
	$retval="
	<form method=\"post\" action=\"index.php?pid=$pid$ord\">
	<b>Results per page:</b>&nbsp;&nbsp;
	<select name=\"new_limit\" class=\"inputbox\">
	<option value=\"5\"".$sel[5].">5</option>
	<option value=\"10\"".$sel[10].">10</option>
	<option value=\"30\"".$sel[30].">30</option>
	<option value=\"50\"".$sel[50].">50</option>
	</select>
	<input type=\"submit\" value=\"OK\" class=\"button\">
	</form>
	";

	return $retval;
}

function get_nr_of_pages($db,$qry,$mode) {

	$rs = mysqli_query($db,$qry) or die ("error<bR>$qry");
	$num = mysqli_num_rows($rs);
	$limit_set=$_SESSION["p_".$mode];

	$nop = $num/$limit_set;
	return $nop;
	
}


 function get_paginator_pages($nop,$cpage,$pid,$order=-1,$mode) {
    $limit_set=$_SESSION["p_".$mode];
	$no = $nop*$limit_set;
    $nop = ceil($nop);
	
	if($order==-1) $ord=""; else $ord="&order=$order";
	$retval="
	<br><table border =\"1\" width=\"100%\" cellpadding=\"1\" cellspacing=\"1\">
	<tr>
	<td bgcolor=\"whitesmoke\" width=\"150\">
	<b>Total Records:&nbsp;&nbsp;".$no."&nbsp;&nbsp;</b>
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"right\">
	";
	$plinks="";
    $plinks="<b> Page $cpage of $nop   |  </b>";
	/*for($i=1;$i<=$nop;$i++) {
		if($i!=$cpage) 
		$plinks.="&nbsp;&nbsp;<a href=\"index.php?pid=$pid&page=$i$ord\">$i</a>&nbsp;&nbsp;";
		else 
		$plinks.="&nbsp;&nbsp;<b>$i</b>&nbsp;&nbsp;";
	}*/
	
	
	
	if($cpage!=1) {
	    $tp=1;
		$range = $page*$limit_set;	
		$plinks.="<a href=\"index.php?pid=$pid&page=$tp&sno=$range$ord\">--&lt;First</a>&nbsp;&nbsp;";
		$tp=$cpage-1;
		$range = ($tp-1)*$limit_set;	
		$plinks.="<a href=\"index.php?pid=$pid&page=$tp&sno=$range$ord\">&lt;Prev</a>&nbsp;";
	}
	if($cpage!=$nop) {
		$tp=$cpage+1;
		$range = $cpage*$limit_set;	
		$plinks=$plinks."|&nbsp;&nbsp;<a href=\"index.php?pid=$pid&page=$tp&sno=$range$ord\">Next&gt;</a>&nbsp;&nbsp;";
		$tp=$nop;
		$range = ($tp-1)*$limit_set;	
		$plinks.="<a href=\"index.php?pid=$pid&page=$tp&sno=$range$ord\">Last&gt;--</a>&nbsp;&nbsp;";
	}
	
	$retval.="$plinks</td></tr></table>";
	return $retval;
}

function registration_mail($uid,$mode) {
	global $db, $ADMIN_MAIL, $SYSTEM_NAME;

	$qry="SELECT Surname, Password, email FROM Student WHERE StudentId='$uid'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$line = mysqli_fetch_array($rs);

	$ulogin = $line['Surname'];
	$upass = $line['Password'];

	/* adresaci */
	$uemail = $line['email'];

	/* temat */
	$subject = ($mode=="delete") ? "Registration in $SYSTEM_NAME is rejected" : "Registration in $SYSTEM_NAME is approved";

	/* wiadomo?? */
	$now=date("m.d.y G:i");
	if($mode=="approve") {
		$msg = "Registration in $SYSTEM_NAME was accepted<br><Br>
		You can now sign in and use the system<br><Br>
		Your login data:<br>
		LOGIN: $ulogin<br>
		PASSWORD: $upass<br>
		";
	} else if($mode=="delete") {
		$msg = "Registration in $SYSTEM_NAME was rejected<br><Br>
		For any questions contact site administrator at $ADMIN_MAIL
		";
	}
	/* Aby wys?a? e-mail w formacie HTML, nale?y ustawi? nag??wek Content-type. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

	/* dodatkowe nag??wki */
	$headers .= "From: DOE Administrator <$ADMIN_MAIL>\r\n";
	/* a teraz wy?lij */
	//return mail($uemail, subject, $msg, $headers);   //this has been commented by Pema for offline server
}

?>