<?php

$c="";

$c.="<h4 class=\"title\">Manage templates</h4>"

$qry="SELECT * FROM Template WHERE 1";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$c.="
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Template name</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";

while ($line = mysqli_fetch_array($rs)) {
	$c.="
	<tr>
	<td>
	".$line['Name']."
	</td>
	<td align=\"center\">
	<a href=\"?pid=107&id=".$line['TemplateId']."\"><img src=\"images/preview.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Preview\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=111&id=".$line['TemplateId']."\" title=\"Edit template\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit template\" /></a>
	</td>
	</tr>
	";
}

$c.="</table>";

/*
// Instantiate a new form
$form = new HTML_QuickForm('book','post','?pid=106');
// Add a text box
$form->addElement('text','title','Book Title:');
// Add a select box
$subjects = array('Math','Ice Fishing','Anatomy');
$form->addElement('select','subject','Subject(s): ',$subjects);
// Add a submit button
$form->addElement('submit','save','Save Book');
// Add a validation rule: title is required
$form->addRule('title','Please Enter a Book Title','required');
// Call the processing function if the submitted form
// data is valid; otherwise, display the form
if ($form->validate()) {
$c.=$form->process('praise_book');
} else {
$c.=$form->toHtml();
}


// Define a function to process the form data
function praise_book($v) {
global $subjects;
$retval="";

foreach($v as $vname => $vval) {
	$retval.="$vname => $vval<br>";
}

return $retval;
}*/

?>
