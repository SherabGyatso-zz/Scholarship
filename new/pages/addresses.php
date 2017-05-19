<?php
$c="";

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {

	$qry="SELECT * FROM `School` WHERE AddressId=".$_GET['id'].""; 
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$c1 = mysqli_num_rows($rs);
	$qry="SELECT * FROM `Student` WHERE AddressId=".$_GET['id'].""; 
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$c2 = mysqli_num_rows($rs);
	if($c1>0 || $c2>0) {
		header("Location: index.php?pid=101&ewn=103");
		exit();		
	}

	$qry="DELETE FROM `Address` WHERE AddressId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],3,$lqry);
	header("Location: index.php?pid=101&ewn=206");
	exit();		
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {
	
		$qry="SELECT * FROM `School` WHERE AddressId=".$fcheck[$i].""; 
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$c1 = mysqli_num_rows($rs);
		$qry="SELECT * FROM `Student` WHERE AddressId=".$fcheck[$i].""; 
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$c2 = mysqli_num_rows($rs);
		if($c1>0 || $c2>0) {
			header("Location: index.php?pid=101&ewn=103");
			exit();		
		}
			
		$qry="DELETE FROM `Address` WHERE AddressId='".$fcheck[$i]."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$lqry.=$qry."\n\r";
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],3,$lqry);
	header("Location: index.php?pid=101&ewn=207");
	exit();
} 

$qry = "SELECT * FROM Address WHERE 1";

if(isset($_POST['new_limit'])) $_SESSION['p_addresses']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_SESSION['p_addresses'])) $_SESSION['p_addresses']=10;

$paginator_select=get_paginator_select("addresses",$pid);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"addresses"),$page,$pid,"addresses");

$limit=get_limit("addresses",$page);

$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$c.="<font class=\"title\">Addresses</font><br><Br>
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\">
<a href=\"javascript:void(0)\" onclick=\"popup('addresses_add_edit.php?a=a',500,200)\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit category\" align=\"left\" />&nbsp;&nbsp;Add new address</a></td>
<td align=\"right\">
$paginator_select
</td>
</tr>
</table>
<br><Br>
";
//show existing addresses



$c.="
$paginator_pages
<form method=\"post\" action=\"?pid=101&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Zip</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>CountryId</b>
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
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['AddressId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['AddressId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Zip']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['CountryId']."
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"javascript:void(0)\" onclick=\"popup('addresses_add_edit.php?a=e&id=".$line['AddressId']."',500,200)\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit address\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=101&a=1&id=".$line['AddressId']."\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete address\" /></a>
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