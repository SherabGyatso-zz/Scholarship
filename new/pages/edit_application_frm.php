<?php
 
$c="";

$c.="<font class=\"title\">Editing Application Form</font><br><Br>
";
if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];

$qry="SELECT * FROM StudentScholarship WHERE StudentScholarshipId='$gssid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line=mysqli_fetch_array($rs);
$status=$line['Status'];

$c.=show_scholarship_type($db,$line['ScholarshipId']);

$c.="<br><a href=\"index.php?pid=109&id=$gssid&ty=e\">&laquo; back to the scholarship</a><br><br>";
if(!isset($_GET['id'])) exit(0); else $gssid=$_GET['id'];
$stype=$_GET['stype'];
	switch($stype) {
		case 1 : $add_fields=2; $t="Overseas Scholarship Application Form"; break;
		case 2 : $add_fields=3; $t="DoE Scholarship Application Form"; break;
		case 3 : $add_fields=4; $t="Research Scholarship Application Form"; break;
		case 4 : $add_fields=5; $t="Songtsen Scholarship Application Form"; break;
	}	
$qry="select email from student where studentid=".$line['StudentId'];
$rs = mysql_query($qry,$db) or die("DB ERROR!!!");
$line1=mysqli_fetch_array($rs);
$email=$line1['email'];

$d=unserialize($line['FormData']);

	$c.="<font class=\"title\">$t</font><br><Br>";
	$edit_type = 'e';
	$template_name="template$add_fields";
	$form = new HTML_QuickForm($template_name,'post',"?pid=1000&id=$gssid");
	
	//$form->addElement('file','photo','Your Photo (max. 1MB):');
	
	get_form11($db,1,$form, $d);
	get_form11($db,$add_fields,$form, $d);
		$studentid=$line['StudentId'];
	$form->addElement('hidden','edit_type',$edit_type);
	$form->addElement('hidden','stype',$stype);
	$form->addElement('hidden','studentid',$studentid); 
	$form->addElement('submit','save','Submit','class="button"');
	$form->addElement('reset','reset','Reset','class="button"');  
	if ($form->validate()) {
		$form->process('submit_application_form');
		header("Location: index.php?pid=109&id=$gssid");
		//header("Location: index.php?ewn=231");
		exit();
	} else {
		$rendered_form=$form->toHtml();
		$c.=$rendered_form; 
	}		


/*echo '<pre>';
print_r($d);
echo '</pre>';*/
function get_form11($db,$templateid,&$form, $d) {
   
	$qry="SELECT * FROM TemplateElements WHERE TemplateId='$templateid'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	while ($line = mysqli_fetch_array($rs)) {
		get_field11($form,$line,$d);
	}
}
function get_field11(&$form,$params,$d,$readonly=FALSE) {

	if($readonly) $params['Attributes'] = " READONLY";
	switch($params['Type']) {
		case "text" :
			$elt =$form->addElement('text',$params['Name'],$params['Label'],$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
		case "password" :
			$elt =$form->addElement('password',$params['Name'],$params['Label'],$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
		case "textarea" :
			$elt =$form->addElement('textarea',$params['Name'],$params['Label'],$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
		case "select" :
			$options=explode("||",$params['Options']);
			$elt =$form->addElement('select',$params['Name'],$params['Label'],$options,$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
		case "hidden" :
			$elt =$form->addElement('hidden',$params['Name'],$params['Value'],$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
		case "checkbox" :
			$elt =$form->addElement('checkbox',$params['Name'],$params['Label'],$params['Text'],$params['Attributes']);
			$elt->setValue(mysql_real_escape_string($d[$params['Name']]));
			break;
	}
	if($params['required']==1) $elt =$form->addRule($params['Name'],'This field is required','required');
}


/*$form_action="index.php?pid=1000&id=$gssid";
		$form = new HTML_QuickForm('application form','post',$form_action);
		$form->addElement('hidden','ssid',$gssid);

		$elt =& $form->addElement('text','applicant_name_surname','Applicant Name:');
		$elt->setValue(mysql_real_escape_string($d['applicant_name_surname']));
		$elt =& $form->addElement('text','duration_of_course','Duration of Course:');
		$mailing_address = mysql_real_escape_string($d['duration_of_course']);
		$elt->setValue($mailing_address);
		$elt =& $form->addElement('text','course_or_subject','Course or Subject:');
		$elt->setValue($d['course_or_subject']);
		$elt =& $form->addElement('text','date_of_birth','Date of Birth:');
		$elt->setValue($d['date_of_birth']);
		$elt =& $form->addElement('text','gender','Gender:');
    	$val=($d['gender']==0)?'Male':'Female';
		$elt->setValue($val);
		$elt =& $form->addElement('textarea','permanent_address','Permanent Address:');
		$elt->setValue($d['permanent_address']);
		$elt =& $form->addElement('text','contact_numbers','Contact number:');
		$elt->setValue($d['contact_numbers']);
		$elt =& $form->addElement('text','E-mail_Address','E-mail_Address:');
		$elt->setValue($d['E-mail_Address']);
		$elt =& $form->addElement('text','edu_qualification','Edu_qualification:');
		$elt->setValue($d['edu_qualification']);
		$sel_status = array('','Yes','No'); 
		$elt =& $form->addElement('select','if_reserved_seats','if_reserved_seats: ',$sel_status);
		$edit_type = 'e';
		$form->addElement('hidden','edit_type',$edit_type); 
		$form->addElement('submit','save','Update','class="button"');
 
		 if ($form->validate()) {
		$form->process('submit_application_form');
		header("Location: index.php?pid=1000&id=$gssid");
		exit();	
		} else {
			$rendered_form=$form->toHtml();
			$c.=$rendered_form; 
			//$retval.=$c;
		}	
*/?>