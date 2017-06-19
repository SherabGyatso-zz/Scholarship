<?php
$c="";

$c.="<h4 class=\"title\">Report</h4>";

if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];

$qry="SELECT * FROM StudentScholarship WHERE StudentScholarshipId='$gssid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line=mysqli_fetch_array($rs);
$status=$line['Status'];
$qry="select * from student where studentid=".$line['StudentId'];
$rs = mysqli_query($db,$qry) or die("DB ERROR!!!");
$line1=mysqli_fetch_array($rs);
$c.=show_scholarship_type($db,$line['ScholarshipId']);

$c.="<br><a href=\"index.php?pid=109&id=$gssid\">&laquo; back to the scholarship</a><br><br>";
$email=$line1['email'];
$address = $line1['Address'];


$c.=report_show1($line['FormData'],$email, $address);

$r=unserialize($line['ReportData']);
/*echo '<pre>';
print_r($r);
echo '</pre>';*/

$form_action="index.php?pid=110&id=$gssid";
$form = new HTML_QuickForm('status','post',$form_action);
$form->addElement('hidden','ssid',$gssid);

$elt =$form->addElement('text','name_of_edu_inst','Name of Educational Institution:');
//$name_of_edu = mysqli_real_escape_string($r,$_POST['name_of_edu_inst']);
//$elt->setValue($name_of_edu);
$elt->setValue($r['name_of_edu_inst']);
$elt =$form->addElement('text','place','Place of Institution:');
$elt->setValue($r['place']);
$elt =& $form->addElement('text','mailing_address','Mailing address:');
//$mailing_address = mysqli_real_escape_string($r,$_POST['mailing_address']);
//$elt->setValue($mailing_address);
$elt->setValue($r['mailing_address']);
$elt =& $form->addElement('text','contact_number','Contact number:');
$elt->setValue($r['contact_number']);

if($line['ScholarshipId']=="2" || $line['ScholarshipId']=="4") {
//sponsor list dropdown

	$elt =& $form->addElement('text','sponsor_name','Sponsor Name:');
	$elt->setValue($r['sponsor_name']);
	$elt =& $form->addElement('text','amount_received','Amount received from the Sponsor for the current year:');
	$elt->setValue($r['amount_received']);
	$elt =& $form->addElement('text','medical_leave','Medical Leave sanctioned on:');
	$elt->setValue($r['medical_leave']);
	$elt =& $form->addElement('text','remarks','Remarks:');
	$elt->setValue($r['remarks']);
}elseif($line['ScholarshipId']=="1") {
$elt =& $form->addElement('text','category','Category:');
$elt->setValue($r['category']);
}
$htmlin="<tr><td><b>Choose study year:</b>&nbsp;";
$htmlin.="
<a href=\"javascript:report_show_year(1,1)\"><b>&nbsp;I&nbsp;</b></a>&nbsp;&nbsp;
<a href=\"javascript:report_show_year(2,1)\"><b>&nbsp;II&nbsp;</b></a>&nbsp;&nbsp;
<a href=\"javascript:report_show_year(3,1)\"><b>&nbsp;III&nbsp;</b></a>&nbsp;&nbsp;
<a href=\"javascript:report_show_year(4,1)\"><b>&nbsp;IV&nbsp;</b></a>&nbsp;&nbsp;
<a href=\"javascript:report_show_year(5,1)\"><b>&nbsp;V&nbsp;</b></a></td></tr>";
$form->addElement('html',$htmlin);

add_report_elements($form,$line['ScholarshipId'],$r);

$form->addElement('submit','save','Submit','class="button"');

if ($form->validate()) {
	$form->process('submit_report');
	header("Location: index.php?pid=110&id=$gssid");
	exit();
} else {
	$rendered_form=$form->toHtml();
	$c.=$rendered_form;
}

?>
