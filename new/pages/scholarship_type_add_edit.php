<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="120";
	$lqry="";
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$qry = "
		INSERT INTO `scholarshiptypes` ( `Name` ) 
		VALUES (
		 '".trim($_POST['sname'])."'
		)";
		$lqry.=$qry;
		$ewnid=236;
		if($_POST['next_opt']==0) $next_pid="120";
		if($_POST['next_opt']==1) $next_pid="120&a=a";
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],7,$lqry);
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$qry = "
		UPDATE `scholarshiptypes` 
		SET `Name` = '".trim($_POST['sname'])."' 
		WHERE `ScholarshipId`='".$_POST['id']."'";
		
		$ewnid=209;	
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],8,$lqry);
	}
	if(mysqli_query($db,$qry) or die(mysqli_error())) {
		header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
		exit();
	}
}

$title="<br />Add New Scholarship Type: ";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit Scholarship Type";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=121&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=121&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM scholarshiptypes WHERE ScholarshipId='".$_GET['id']."'";
		//echo $qry;
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$name_value=$line['Name'];
	}
}

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
<b>Scholarship Type:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"20\" name=\"sname\" maxlength=\"255\" class=\"inputbox\" value=\"$name_value\" /><br /><br />

";

$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add new scholarship type<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=120\">&laquo;&nbsp;&nbsp;back to the scholarship list</a>
";

?>