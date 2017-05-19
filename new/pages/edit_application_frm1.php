  <?php
  if(isset($_GET['id']))
    $gssid = $_GET['id'];
  global $db;
  $c.="<br><a href=\"index.php?pid=109&id=$gssid\">&laquo; back to the scholarship</a><br><br>";
  $qry="SELECT * FROM `StudentScholarship` WHERE `StudentScholarshipId`='$gssid'";
 
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!! 1");
	$line=mysqli_fetch_array($rs);
	
	$andtemplate=$line['ScholarshipId']+1;
	$d=unserialize($line['FormData']);

	   $studentid=$line['StudentId'];
		//print_r($_POST);
		$form_action="index.php?pid=1000&id=$gssid";
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
			$c.="<a href=\"?pid=109&id=".$line['StudentScholarshipId']."&ty=r\"><img src=\"images/edit.gif\" width=\"17\" height=\"17\" border=\"0\" alt=\"Proceed with scholarship\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>";
		/*header("Location: index.php?pid=1000&id=$gssid");
		exit();	*/
		} else {
			$rendered_form=$form->toHtml();
			$c.=$rendered_form; 
			//$retval.=$c;
		}	
		?>	