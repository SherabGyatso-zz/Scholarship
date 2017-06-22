<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {

	$qry="DELETE FROM `news` WHERE id='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],24,$lqry);
	header("Location: index.php?pid=115&ewn=234");
	exit();		
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {	
			
		$qry="DELETE FROM `news` WHERE id='".$fcheck[$i]."'";
		$lqry.=$qry."\n\r";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],24,$lqry);
	header("Location: index.php?pid=115&ewn=235");
	exit();
} 

$c.="<font class=\"title\">News</font><br><Br>
<a href=\"?pid=116&a=a\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add news</a><br><Br>
";
//show existing addresses

$qry = "
SELECT * FROM news WHERE 1 
";

$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY id ";break;
	case 1 : $qry.="ORDER BY title ";break;
	case 2 : $qry.="ORDER BY dateadded ";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_news']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_SESSION['p_news'])) $_SESSION['p_news']=10;

$paginator_select=get_paginator_select("news",$pid,$order);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"news"),$page,$pid,$order,"news");

$limit=get_limit("news",$page);

$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$selord[0]="";
$selord[1]="";
$selord[2]="";

$selord[$order]=" SELECTED";

$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\">
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">ID</option>
<option value=\"1\"".$selord[1].">News title</option>
<option value=\"2\"".$selord[2].">Date added</option>

</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=115')\" class=\"button\"><br />
</form>
</td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>

$paginator_pages

<form method=\"post\" action=\"?pid=115&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>News title</b><br>
</td>
<td bgcolor=\"whitesmoke\">
<b>Date added</b><br>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";

while ($line = mysqli_fetch_array($rs)) {
	
	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['id']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['id']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	<b>".$line['title']."</b>
	</td>
	<td bgcolor=\"#FAFAFA\">
	".date('d.m.Y',$line['dateadded'])."
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=116&a=e&id=".$line['id']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit news\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=115&a=1&id=".$line['id']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete news\" /></a>
	</td>
	</tr>
	";
}

$c.="</table><br>
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td width=\"50\">
<img src=\"images/arrow_ltr.png\" width=\"38\" height=\"22\">
</td>
<td align=\"left\">
<input type=\"submit\" value=\"Delete selected\" class=\"button\" />
</td>
</tr>
</table>

</form>
$paginator_pages";

?>