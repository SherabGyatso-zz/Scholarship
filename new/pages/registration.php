<?php
$c="";

$c.="<h4 class=\"title\">Registration form</h4>
";

if(isset($_GET['a']) && $_GET['a']==1) {

	$c.="<br>Your registration form was successfully submited. Pleas allow us 24 hours to verify your data. Confirmation email will be send automatically while your profile is approved. Thank you";

} else {

	$c.="<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr><td>";
	$form_action="index.php?pid=300";
	$form = new HTML_QuickForm('registration','post',$form_action);
	//$form->addElement('hidden','ssid',$gssid);
	$elt =& $form->addElement('text','name','Name:');
	$form->addRule('name','This field is required','required');
	$form->addRule('name','This field is required','required');
	$elt =& $form->addElement('text','roll','Class XII Roll no:');

	$elt =& $form->addElement('text','surname','Email Id (login):');
	$form->addRule('surname','This field is required','required');
	$form->addRule('surname','This field is required','email');
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	$elt =& $form->addElement('text','email','Email:');
	$form->addRule('email','This field is required','required');
	$form->addRule('email','This is not valid email address','email');
	$elt =& $form->addElement('password','pass','Password:');
	$form->addRule('pass','This field is required','required');
	$elt =& $form->addElement('password','passa','Password again:');
	$form->addRule('passa','This field is required','required');


	$form->addRule(array('pass', 'passa'), 'The passwords do not match', 'compare');

	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	$elt =& $form->addElement('html',"<tR><td align=\"center\"><b>Date of birth</b></td></tr>");
	for($i=1970;$i<=date('Y');$i++) $years[$i] = $i;
	for($i=1;$i<=31;$i++) $days[$i] = $i;
	$elt =& $form->addElement('select','dob_year','Year: ',$years);
	$elt =& $form->addElement('select','dob_month','Month: ',get_months_table());
	$elt =& $form->addElement('select','dob_day','Day: ',$days);
	$gender[0] = 'Male';
	$gender[1] = 'Female';
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	$elt =& $form->addElement('select', 'gender', 'Gender : ',$gender);
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	$elt =& $form->addElement('select','sec_school_f_year','Secondary school finish year: ',$years);
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	/*$qry = "SELECT * FROM Address WHERE 1";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	while($line=mysqli_fetch_array($rs)) {
		$addresses[$line['AddressId']] = $line['Zip']." - ".$line['CountryId'];
	}*/
	$elt =& $form->addElement('textarea','address','Address:');
	//$elt =& $form->addElement('select','address','Address: ',$addresses);

	$qry = "
	SELECT School.SchoolId, School.Name, School.SchoolCategoryId, SchoolCategory.SchoolCategoryName
	FROM School
	LEFT JOIN SchoolCategory ON ( SchoolCategory.SchoolCategoryId = School.SchoolCategoryId )
	WHERE 1
	ORDER BY SchoolCategory.SchoolCategoryName, School.Name
	";
	$rs = mysqli_query($db,$qry) or die ("DB Error!!!");
	while($line=mysqli_fetch_array($rs)) {
		$schools[$line['SchoolId']] = $line['SchoolCategoryName']." >> ".$line['Name'];
	}

	$elt =& $form->addElement('select','school','School: ',$schools);
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');
	$elt =& $form->addElement('select','year_of_start','Year of start: ',$years);
	$elt =& $form->addElement('text','grade_of_start','Grade of start:');
	$elt =& $form->addElement('html','<tR><td>&nbsp;</td></tr>');

	$elt =& $form->addElement('html','<tR><td colspan=2>Not all of the fields are required but if you left one of them empty, please type the reason in remarks field</td></tr>');
	$elt =& $form->addElement('textarea','remarks','Remarks:','wrap="soft"');

	$form->addElement('submit','save','Submit','class="button"');

	if ($form->validate()) {
		$form->process('register_user');
		header("Location: index.php?pid=300&a=1");
		exit();
	} else {
		$rendered_form=$form->toHtml();
		$c.=$rendered_form;

	}

}


?>
