<?php

$c="<font class=\"title\">Template preview</font><br><Br>";

if(isset($_GET['id'])) $templateid=$_GET['id']; else die("ERROR!");

$template_name="template$templateid";
$form = new HTML_QuickForm($template_name,'post','?pid=107');

get_form($db,$templateid,$form);

$rendered_form=$form->toHtml();

$c.=$rendered_form;


?>