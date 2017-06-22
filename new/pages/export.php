<?php

$c="";


$c.="<font class=\"title\">Export Scholarships data to Excel</font><br><Br>
";

$c.="<Br><b>Select date range to export:</b><br><br>";
$c.="<form action=\"xls.php\" method=\"POST\" target=\"_blank\">";

$qry="SELECT * FROM ScholarshipTypes WHERE 1";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
while ($line = mysqli_fetch_array($rs)) {
	$c.="<input type=\"radio\" name=\"stype\" value=\"".$line['ScholarshipId']."\">&nbsp;&nbsp;".$line['Name']."<br>";
}

$c.="<br><br><b>Select date range to export:</b><br><br>";

$c.="<table><tr>";

$c.="<td><b>From:</b>&nbsp;<font class=\"info\">Year/Month/Day</font><br>";

$c.=year_select("y_start",1970,date("Y"),'');
$c.="&nbsp;";
$c.=month_select("m_start",'');
$c.="&nbsp;";
$c.=day_select("d_start",'');

$c.="</td><td width=\"15\">&nbsp;</td><td><b>To:</b>&nbsp;<font class=\"info\">Year/Month/Day</font><br>";

$c.=year_select("y_end",1970,date("Y"),date('Y'));
$c.="&nbsp;";
$c.=month_select("m_end",date('m'));

$c.="&nbsp;";
$c.=day_select("d_end",date('d'));

$c.="</tr></table><br><Br>";

$c.="<input type=\"submit\" value=\"Export\" class=\"button\">";

$c.="</form>";

?>