<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="118";
	$lqry="";
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$qry = "
		INSERT INTO `course` ( `courseType` , `course` , `fullform` ) 
		VALUES (
		 '".$_POST['sname']."', '".$_POST['saddress']."', '".$_POST['ssc']."'
		)";
		$lqry.=$qry;
		$ewnid=236;
		if($_POST['next_opt']==0) $next_pid="118";
		if($_POST['next_opt']==1) $next_pid="118&a=a";
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],7,$lqry);
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$qry = "
		UPDATE `course` 
		SET `courseType` = '".$_POST['sname']."', `course` = '".$_POST['saddress']."', `fullform` = '".$_POST['ssc']."' 
		WHERE `courseid`='".$_POST['id']."'";
		
		$ewnid=209;	
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],8,$lqry);
	}
	if(mysqli_query($db,$qry) or die(mysqli_error())) {
		header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
		exit();
	}
}

$title="Add new course";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit Course";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=119&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=119&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM course WHERE courseid='".$_GET['id']."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$name_value=$line['courseType'];
		$scid_value=$line['Course'];
		$address_value=$line['fullform'];
	}
}

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
<b>Degree:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"20\" name=\"sname\" maxlength=\"255\" class=\"inputbox\" value=\"$name_value\" />(Bachelor/Master/Diploma/Certificate)<br /><br />
<b>Course:</b>
<input type=\"text\" size=\"20\" name=\"saddress\" class=\"inputbox\" value=\"$scid_value\" />
<br /><br />
<b>FullForm:</b>
<input type=\"text\" size=\"40\" name=\"ssc\" class=\"inputbox\" value=\"$address_value\" />
<br /><br />
";

$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add new course<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=118\">&laquo;&nbsp;&nbsp;back to the Course list</a>
";

?>