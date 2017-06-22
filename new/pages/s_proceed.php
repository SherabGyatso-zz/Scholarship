<?php
$c="";
$prm="";
// for editing the appliation form
if(isset($_GET['ty']))
  $prm=$_GET['ty'];


$c.="<h4 class=\"title\">Scholarship</h4>
";


if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];

$qry="SELECT * FROM StudentScholarship inner join student on student.StudentId=StudentScholarship.StudentId WHERE StudentScholarshipId='$gssid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line=mysqli_fetch_array($rs);
$status=$line['Status'];

$c.=show_scholarship_type($db,$line['ScholarshipId']);

$c.="<br><a href=\"index.php?pid=108\">&laquo; back to the scholarship list</a><br><br>";

$c.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>";
$form_action="index.php?pid=109&id=$gssid";
$form = new HTML_QuickForm('status','post',$form_action);
$form->addElement('hidden','ssid',$gssid);
$elt =& $form->addElement('hidden','sid');
$elt->setValue($line['StudentId']);
$sel_status = array('Selected','Alternate','Rejected','Closed','Discontinued','Returnee','Non Returnee');
$elt =& $form->addElement('select','status','Status: ',$sel_status);
$elt->setSelected(getIndex($sel_status,$line['Status']));
//$elt =& $form->addElement('text','study_course','Study Course:',' READONLY');
//$elt->setValue($line['StudyCourse']);
$qry="select * from course order by course";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!".mysql_error());
//$course="<option value=\"all\" SELECTED><--Select Year--></option>";
	while($row=mysqli_fetch_array($rs)) {

		$course[$row['courseid']] = $row['Course'] .">><b>".$row['courseType']."</b>>> ".$row['fullform'];
	}

$elt =& $form->addElement('select','course','Course: ',$course);
$elt->setValue($line['courseid']);
$elt =& $form->addElement('text','spc','Specialize:');
$elt->setValue($line['specialize']);
$form->addElement('html','<tr><td><b>Duration:</b></td></tr>');
$elt =& $form->addElement('text','from','From (YYYY-MM-DD):');
$elt->setValue($line['From']);
$elt =& $form->addElement('text','to','To (YYYY-MM-DD):');
$elt->setValue($line['To']);
$elt =& $form->addElement('text','year_ret','Year Returned:');
$elt->setValue($line['YearReturned']);
if($line['ScholarshipId']=="2")
{
$form->addElement('html','<tr><td><b>Case History:</b></td></tr>');
	$elt =& $form->addElement('textarea','history','History:');
	$elt->setValue($line['history']);
}
$elt =& $form->addElement('text','rollno','Class XII roll no:');
$elt->setValue($line['XiiRollno']);
$form->addElement('submit','save','Submit','class="button"');

if ($form->validate()) {
	$form->process('submit_status');
	header("Location: index.php?pid=109&id=$gssid");
	exit();
} else {
	$rendered_form=$form->toHtml();
	$c.=$rendered_form;
}
if($status=="Selected") $c.="</td><td width=\"50%\" align=\"center\"><table cellpadding=\"5\" cellspacing=\"0\" align=\"center\"><tr><td style=\"border: 1px solid #cccccc; background: whitesmoke\"><a href=\"index.php?pid=110&id=$gssid\"><img src=\"images/report.gif\" border=0 align=\"absmiddle\">&nbsp;&nbsp;REPORT</a></td></tr></table>";
$c.="</td></tr></table>";
$c.=display_form_data($db,$gssid,$prm);
if($prm=='e')
header("Location:index.php?pid=1000&id=$gssid");
?>
