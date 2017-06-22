<?php
$c="";
if(isset($_GET['ty']))
  $prm=$_GET['ty'];
  

$c.="<font class=\"title\">Scholarship</font><br><Br>
";

if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];

$qry="SELECT * FROM StudentScholarship WHERE StudentScholarshipId='$gssid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line=mysqli_fetch_array($rs);
$status=$line['Status'];

$c.=show_scholarship_type($db,$line['ScholarshipId']);

$c.="<br><a href=\"index.php?pid=108\">&laquo; back to the scholarship list</a><br><br>";

$c.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>";
$form_action="index.php?pid=109&id=$gssid";
$form = new HTML_QuickForm('status','post',$form_action);
$form->addElement('hidden','ssid',$gssid);
$sel_status = array('Selected','Alternate','Rejected','Closed','Discontinued'); 
$elt =& $form->addElement('select','status','Status: ',$sel_status);
$elt->setSelected(getIndex($sel_status,$line['Status']));
$elt =& $form->addElement('text','study_course','Study Course:');
$elt->setValue($line['StudyCourse']);
$form->addElement('html','<tr><td><b>Duration:</b></td></tr>');
$elt =& $form->addElement('text','from','From (YYYY-MM-DD):');
$elt->setValue($line['From']);
$elt =& $form->addElement('text','to','To (YYYY-MM-DD):');
$elt->setValue($line['To']);
$elt =& $form->addElement('text','year_ret','Year Returned From Studies:');
$elt->setValue($line['YearReturned']);
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

?>