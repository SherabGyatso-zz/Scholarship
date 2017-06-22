<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="1";
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$qry = "
		INSERT INTO `DoeOfficer` ( `DoeOfficerId` , `Name` , `Password` , `Contact` ) 
		VALUES (
		'', '".$_POST['doename']."', '".$_POST['doepass']."', '".$_POST['doecontact']."'
		)";
		$ewnid=216;
		if($_POST['next_opt']==0) $next_pid="1";
		if($_POST['next_opt']==1) $next_pid="2&a=a";
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],13,$qry);	
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$qry = "
		UPDATE `DoeOfficer` 
		SET 
		`Name` = '".$_POST['doename']."', 
		`Password` = '".$_POST['doepass']."', 
		`Contact` = '".$_POST['doecontact']."' 
		WHERE `DoeOfficerId`='".$_POST['id']."'";
		$ewnid=217;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],14,$lqry);	
	}
	if(mysqli_query($db,$qry)) {
		header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
		exit();
	}
}

$title="Add new DOE Officer";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit DOE Officer";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=2&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=2&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM DoeOfficer WHERE DoeOfficerId='".$_GET['id']."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$name_value=$line['Name'];
		$pass_value=$line['Password'];
		$contact_value=$line['Contact'];
	}
}

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
<b>Name:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"doename\" maxlength=\"255\" class=\"inputbox\" value=\"$name_value\" /><br>
<b>Password:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"doepass\" maxlength=\"255\" class=\"inputbox\" value=\"$pass_value\" /><br>
<b>Contact:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"doecontact\" maxlength=\"255\" class=\"inputbox\" value=\"$contact_value\" /><br>
";


$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add new DOE Officer<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=1\">&laquo;&nbsp;&nbsp;back to the DOE Officers list</a>
";

?>