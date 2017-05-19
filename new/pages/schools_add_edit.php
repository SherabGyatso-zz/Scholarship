<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="102";
	$lqry="";
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$qry = "
		INSERT INTO `School` ( `SchoolId` , `Name` , `Address` , `SchoolCategoryId` ) 
		VALUES (
		'', '".$_POST['sname']."', '".$_POST['saddress']."', '".$_POST['ssc']."'
		)";
		$lqry.=$qry;
		$ewnid=208;
		if($_POST['next_opt']==0) $next_pid="102";
		if($_POST['next_opt']==1) $next_pid="103&a=a";
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],7,$lqry);
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$qry = "
		UPDATE `School` 
		SET `Name` = '".$_POST['sname']."', `Address` = '".$_POST['saddress']."', `SchoolCategoryId` = '".$_POST['ssc']."' 
		WHERE `SchoolId`='".$_POST['id']."'";
		$ewnid=209;	
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],8,$lqry);
	}
	if(mysqli_query($db,$qry)) {
		header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
		exit();
	}
}

$title="Add new school";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit school";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=103&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=103&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM School WHERE SchoolId='".$_GET['id']."'";
		$rs = mysqli_query($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$name_value=$line['Name'];
		$scid_value=$line['SchoolCategoryId'];
		$address_value=$line['Address'];
	}
}

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
<b>School Name:</b>&nbsp;&nbsp;
<input type=\"text\" size=\"40\" name=\"sname\" maxlength=\"255\" class=\"inputbox\" value=\"$name_value\" /><br>
<b>Addres:</b><br />
<textarea name=\"saddress\">$address_value</textarea>
<br /><br />
<b>School category:</b>&nbsp;&nbsp;
<select name=\"ssc\" size=\"1\">
";

$qry2 = "SELECT * FROM SchoolCategory WHERE 1";
$rs2 = mysqli_query ($db,$qry2) or die ("DB Error!!!");
while($line2=mysqli_fetch_array($rs2)) {
	if($line2['SchoolCategoryId']==$scid_value) $sel=" selected"; else $sel="";
	$form.="<option value=\"".$line2['SchoolCategoryId']."\"$sel>".$line2['SchoolCategoryName']."</option>\n";
}

$form.="
</select><br />

<br>
";

$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add new school<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=102\">&laquo;&nbsp;&nbsp;back to the school list</a>
";

?>