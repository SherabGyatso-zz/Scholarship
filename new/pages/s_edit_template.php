<?php

if(isset($_GET['id'])) $templateid=$_GET['id']; else die("ERROR!");

$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {
	$eltId=$_GET['eltId'];
	$eltOrd=$_GET['eltOrd'];
	$qry="DELETE FROM `TemplateElements` WHERE ElementId=$eltId";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry."\n\r";
	$qry="UPDATE TemplateElements SET Ord=Ord-1 WHERE TemplateId=$templateid AND Ord>$eltOrd";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],20,$lqry);
	header("Location: index.php?pid=111&id=$templateid&ewn=221");
	exit();
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$eltId=$_GET['eltId'];
	$newOrd=$_GET['newOrd'];
	$mode=$_GET['mode'];

	if($mode=="up") $pOrd=1; else if($mode=="down") $pOrd=-1;

	$qry="UPDATE TemplateElements SET Ord=Ord+($pOrd) WHERE TemplateId=$templateid AND Ord=$newOrd";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

	$qry="UPDATE TemplateElements SET Ord=$newOrd WHERE ElementId=$eltId";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

	header("Location: index.php?pid=111&id=$templateid&ewn=223");
	exit();

}

$c="";

$qry="SELECT * FROM Template WHERE TemplateId='$templateid'";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
$line = mysqli_fetch_array($rs);

$c.="<h4 class=\"title\">Edit template</h4>
<b>Template name:</b> ".$line['Name']."<br />
<br />
<a href=\"?pid=112&toTemplateId=$templateid\" title=\"Add new element to form\"><img src=\"images/add.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Add element\" align=\"left\" />&nbsp;&nbsp;Add new element to form</a><br><Br>";

$c.="
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"50\">
<b>Order</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Label &amp; Field</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"90\">
<b>Edit/Delete</b>
</td>
</tr>
";

$qry="SELECT * FROM TemplateElements WHERE TemplateId='$templateid' ORDER BY Ord";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");

$i=0;
$cnt=mysqli_num_rows($rs);

while ($line = mysqli_fetch_array($rs)) {

	$i++;

	$up="images/up.gif";
	$down="images/down.gif";
	$upurl="?pid=111&id=$templateid&eltId=".$line['ElementId']."&newOrd=".($line['Ord']-1)."&a=2&mode=up";
	$downurl="?pid=111&id=$templateid&eltId=".$line['ElementId']."&newOrd=".($line['Ord']+1)."&a=2&mode=down";

	if($cnt==1) {
		$up="images/null.gif";
		$upurl="javascript:void(0)";
		$down="images/null.gif";
		$downurl="javascript:void(0)";
	} else {
		if($i==1) {
			$up="images/null.gif";
			$upurl="javascript:void(0)";
		} else if($i==$cnt) {
			$down="images/null.gif";
			$downurl="javascript:void(0)";
		}
	}

	$template_name="template$templateid";
	$form = new HTML_QuickForm($template_name,'post','javascript:void(0)');
	get_field($form,$line,TRUE);
	$elt = $form->toHtml();
	unset($form);
	$c .= "
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">

	<a href=\"$downurl\">
	<img src=\"$down\" width=\"16\" height=\"16\" align=\"middle\" border=\"0\" />
	</a>
	<a href=\"$upurl\">
	<img src=\"$up\" width=\"16\" height=\"16\" border=\"0\" />
	</a>

	</td>
	<td bgcolor=\"#FAFAFA\" align=\"left\">
	$elt
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=113&templateId=$templateid&eltId=".$line['ElementId']."\"><img src=\"images/edit.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Edit element\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
	<a href=\"?pid=111&id=$templateid&eltId=".$line['ElementId']."&eltOrd=".$line['Ord']."&a=1\"><img src=\"images/delete.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"Delete element\" /></a>
	</td>
	</tr>
	";
}

?>
