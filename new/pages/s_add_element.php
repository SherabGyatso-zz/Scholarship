<?php

if(isset($_GET['toTemplateId'])) $templateid=$_GET['toTemplateId']; else die("ERROR!");

$c="";

$qry="SELECT * FROM Template WHERE TemplateId='$templateid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);

$c.="<font class=\"title\">Add new element to form</font><br><Br>
<b>Template name:</b> ".$line['Name']."<br />
<br />
";

if(!isset($_POST['step'])) $step=1; else $step=$_POST['step'];

if($step==1) {
	$qry="SELECT * FROM TemplateElements WHERE TemplateId='$templateid' ORDER BY Ord";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	
	$eltcount=mysqli_num_rows($rs)+1;
	$eltName = $templateid."_".$eltcount;

	$c.="
		<form action=\"?pid=112&toTemplateId=$templateid\" method=\"post\">
		Select element type:
		<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
		<tr>
		<td bgcolor=\"white\" align=\"white\">
		&nbsp;
		</td>
		<td bgcolor=\"whitesmoke\" align=\"center\">
		<b>Element example</b>
		</td>
		
		</tr>
		<tr>
		<td bgcolor=\"#FAFAFA\" align=\"left\">
		<input type=\"radio\" name=\"elementType\" value=\"text\"> Text
		</td>
		<td bgcolor=\"#FAFAFA\">
		<input type=\"text\" READONLY>
		</td>
		</tr>
		
		</tr>
		<tr>
		<td bgcolor=\"#FAFAFA\" align=\"left\">
		<input type=\"radio\" name=\"elementType\" value=\"textarea\"> Textarea
		</td>
		<td bgcolor=\"#FAFAFA\">
		<textarea READONLY></textarea>
		</td>
		</tr>
		
		</tr>
		<tr>
		<td bgcolor=\"#FAFAFA\" align=\"left\">
		<input type=\"radio\" name=\"elementType\" value=\"select\"> Select
		</td>
		<td bgcolor=\"#FAFAFA\">
		<select READONLY style=\"width: 100px\"></select>
		</td>
		</tr>
		
		</tr>
		<tr>
		<td bgcolor=\"#FAFAFA\" align=\"left\">
		<input type=\"radio\" name=\"elementType\" value=\"checkbox\"> Checkbox
		</td>
		<td bgcolor=\"#FAFAFA\">
		<input type=\"checkbox\" READONLY>
		</td>
		</tr>
		
		</table>
		
		<input type=\"hidden\" name=\"step\" value=\"2\">
		<input type=\"hidden\" name=\"elementName\" value=\"$eltName\">
		<br>
		
		<b>Element order - add after field:</b>&nbsp;&nbsp;
		<select name=\"afterOrder\">
		<option value=\"-1\">(First - at the beginning)</option>
		";
		while ($line = mysqli_fetch_array($rs)) {
			if(strlen($line['Label'])>45) {
				$opt=substr($line['Label'],0,45)."...";
			} else $opt=$line['Label'];
			$c .= "<option value=\"".$line['Ord']."\">$opt</option>";
		}
		$c .= "</select><Br><Br>
		<input type=\"submit\" value=\"Next step\" class=\"button\">
		</form>";
} else if($step==2) {
	$c.="
		<form action=\"?pid=112&toTemplateId=$templateid\" method=\"post\">
		<input type=\"hidden\" name=\"elementType\" value=\"".$_POST['elementType']."\">
		<input type=\"hidden\" name=\"afterOrder\" value=\"".$_POST['afterOrder']."\">
		<input type=\"hidden\" name=\"elementName\" value=\"".$_POST['elementName']."\">
		<input type=\"hidden\" name=\"step\" value=\"3\">
		";
	switch($_POST['elementType']) {
		case "text" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\"><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\">&nbsp;&nbsp;Required?<br>
			";
			break;
		case "textarea" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\"><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\">&nbsp;&nbsp;Required?<br>
			";
			break;
		case "select" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\"><br>
			Options:<br>
			<b><sub>* each option should be in new line !</sub></b><br>
			<textarea rows=\"7\" cols=\"30\" name=\"options\"></textarea><br>
			<input type=\"checkbox\" value=\"1\" name=\"required\">&nbsp;&nbsp;Required?<br>
			";
			break;
		case "checkbox" :
			$c.="Element Label:&nbsp;&nbsp;<input type=\"text\" name=\"label\"><br>
			Checkbox option:&nbsp;&nbsp;<input type=\"text\" name=\"checkboxtext\"><br>
			";
			break;
	}
	$c.="<br><input type=\"submit\" value=\"Add element\" class=\"button\">";
} else if($step==3) {
	
	$qry="UPDATE TemplateElements SET Ord=Ord+1 WHERE TemplateId=$templateid AND Ord>".$_POST['afterOrder'];
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	
	$elementType=$_POST['elementType'];
	$order=$_POST['afterOrder']+1;
	$elementName=$_POST['elementName'];
	$elementLabel=$_POST['label'];
	if(isset($_POST['required'])) {
		if($_POST['required']==1) $required=1;
	} else $required=0;
	if(isset($_POST['checkboxtext'])) $text=$_POST['checkboxtext']; else $text="";
	
	if($elementType=="select") {
		$trans = array("\n" => "||", "\r" => "");
		$options = strtr($_POST['options'], $trans);
	} else $options="";
	
	$qry="INSERT INTO `TemplateElements` (`ElementId`, `TemplateId`, `Type`, `Name`, `Label`, `Text`, `Value`, `Options`, `Attributes`, `Ord`, `required`) VALUES (NULL, '$templateid', '$elementType', '$elementName', '$elementLabel', '$text', '', '$options', '', '$order', '$required')";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],18,$qry);
	header("Location: index.php?pid=111&id=$templateid&ewn=220");
	exit();
}

?>