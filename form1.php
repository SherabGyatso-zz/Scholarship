<?
require("formxml.class.php");

// Here require include $ADODB class.
// , require $db= ADONewConnection('mysql'), all other data in xml file.

$formxml=new FormXML;
$formxml->DELETE_IMAGE_STR='Delete';
$formxml->html_form[1][0]=	"<table border=0 align=center>\n";
$formxml->html_form[1][1]=	"  <tr>\n". //[1][1]
							"    <td class=\"text_12\" >\n"."          ";
$formxml->ParseFile('forms/registration.xml');
$formxml->CheckPost();

echo $formxml->out;

?>
