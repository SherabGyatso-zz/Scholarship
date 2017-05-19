<?php

if(isset($_GET['templateId']) AND isset($_GET['eltId'])) {
	$templateid=$_GET['templateId'];
	$eltId=$_GET['eltId'];
	} else die("ERROR!");

$c="";

$qry="SELECT * FROM Template WHERE TemplateId='$templateid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);

$c.="<font class=\"title\">Edit form element</font><br><Br>
<b>Template name:</b> ".$line['Name']."<br />
<br />
";

if(!isset($_POST['step'])) $step=1; else $step=$_POST['step'];

if($step==1) {
	$qry="SELECT * FROM TemplateElements WHERE ElementId='$eltId'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$line = mysqli_fetch_array($rs);
	$c.="<b>Element type:</b> ".$line['Type']."<br><br>
		<form action=\"?pid=113&templateId=$templateid&eltId=$eltId\" method=\"post\">
		<input type=\"hidden\" name=\"step\" value=\"2\">
		";
	if($line['required']==1) $req=" CHECKED"; else $req="";
	switch($line['Type']) {
		case "text" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\" value=\"".$line['Label']."\"><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\"$req>&nbsp;&nbsp;Required?<br>
			";
			break;
		case "textarea" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\" value=\"".$line['Label']."\"><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\"$req>&nbsp;&nbsp;Required?<br>
			";
			break;
		case "select" :
			$trans = array("||" => "\n");
			$options = strtr($line['Options'], $trans);
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\" value=\"".$line['Label']."\"><br>
			Options:<br>
			<b><sub>* each option should be in new line !</sub></b><br>
			<textarea rows=\"7\" cols=\"30\" name=\"options\">$options</textarea><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\"$req>&nbsp;&nbsp;Required?<br>
			";
			break;
		case "checkbox" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\" value=\"".$line['Label']."\"><br>
			Checkbox option:&nbsp;&nbsp;<input type=\"text\" name=\"checkboxtext\" value=\"".$line['Text']."\"><br>
			";
			break;
	}
	$c.="<br><input type=\"submit\" value=\"Save changes\" class=\"button\">";
} else if($step==2) {

	$elementLabel=$_POST['label'];
	if(isset($_POST['required'])) {
		if($_POST['required']==1) $required=1;
	} else $required=0;
	if(isset($_POST['checkboxtext'])) $text=$_POST['checkboxtext']; else $text="";
	
	if(isset($_POST['options'])) {
		$trans = array("\n" => "||", "\r" => "");
		$options = strtr($_POST['options'], $trans);
	} else $options="";
	
	$qry="UPDATE TemplateElements SET 
		Label='$elementLabel',
		Text='$text',
		Options='$options',
		required='$required' WHERE ElementId=$eltId";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],19,$qry);
	header("Location: index.php?pid=111&id=$templateid&ewn=222");
	exit();
}

?>