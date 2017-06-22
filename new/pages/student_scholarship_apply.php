<?php

$c="";

if(isset($_GET['step'])) $step=$_GET['step']; else $step=1;

if($step==1) {
   $studentid=$_GET['id'];
	$c.="<h4 class=\"title\">Available scholarships</h4>
	<b>Choose scholarship</b><br>
	<form action=\"?pid=201&step=2&id=$studentid\" method=\"post\">";
	$qry="SELECT * FROM ScholarshipTypes WHERE 1";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	while ($line = mysqli_fetch_array($rs)) {
		$c.="<input type=\"radio\" name=\"stype\" value=\"".$line['ScholarshipId']."\">&nbsp;&nbsp;".$line['Name']."<br>";
	}
	$c.="<br><input type=\"submit\" value=\"Next &raquo;\" class=\"button\">
	</form>";
}

if($step==2) {
	//$studentid=$_SESSION['userid'];
	if(isset($_GET['id']))
		$studentid=$_GET['id'];
	else
		$studentid=$_SESSION['userid'];

	if(!isset($_POST['stype'])) {
		header("Location: index.php?pid=201&ewn=100");
		exit();
	} else {
		$qry="SELECT * FROM `StudentScholarship` WHERE StudentId='$studentid' AND ScholarshipId='".$_POST['stype']."' order by studentscholarship.From";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$rst = mysqli_fetch_array($rs);
		$rsc=mysqli_num_rows($rs);
	  	if(($rsc!=0 and $rst['Status']!="Closed")) {
			header("Location: index.php?pid=201&ewn=3");
			exit();
	   } else {
		header("Location: index.php?pid=201&step=3&id=$studentid&stype=".$_POST['stype']."");
		exit();
		}
	}
}

if($step==3) {
	$stype=$_GET['stype'];
	switch($stype) {
		case 1 : $add_fields=2; $t="Overseas Scholarship Application Form"; break;
		case 2 : $add_fields=3; $t="DoE Scholarship Application Form"; break;
		case 3 : $add_fields=4; $t="Research Scholarship Application Form"; break;
		case 4 : $add_fields=5; $t="Songtsen Scholarship Application Form"; break;
		case 5 : $add_fields=3; $t="Phurbu Dolma Memorial Scholarship Scholarship Application Form"; break;
		case 6 : $add_fields=3; $t="Tibet Funds Professional Scholarship Application Form"; break;
		case 7 : $add_fields=3; $t="Professional Scholarship Under POHDD Application Form"; break;
		case 8 : $add_fields=3; $t="The Dalai Lama Trust Scholarship Application Form"; break;
	}
	$c.="<h4 class=\"title\">$t</h4>";
	$edit_type = 'a';
	$template_name="template$add_fields";
	$form = new HTML_QuickForm($template_name,'post',"?pid=201&step=3&stype=".$_GET['stype']."");

	$form->addElement('file','photo','Your Photo (max. 1MB):');

	get_form($db,1,$form);
	get_form($db,$add_fields,$form);

	if(isset($_GET['id']))
		$studentid=$_GET['id'];
	else
		$studentid=$_SESSION['userid'];

	$form->addElement('hidden', 'edit_type', $edit_type);
	$form->addElement('hidden', 'stype', $stype);
	$form->addElement('hidden', 'studentid', $studentid);
	$form->addElement('submit', 'save', 'Submit', 'class="button"');
	$form->addElement('reset', 'reset', 'Reset', 'class="button"');
	if ($form->validate()) {
		$form->process('submit_application_form');
		header("Location: index.php?ewn=231");
		exit();
	} else {
		$rendered_form=$form->toHtml();
		$c.=$rendered_form;
	}
}

?>
