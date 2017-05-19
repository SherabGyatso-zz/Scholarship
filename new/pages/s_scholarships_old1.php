<?php
$c="";
$ppath = "sholarshipphotos/";
$lqry="";
if(isset($_GET['a']) && $_GET['a']==1) {
	$qry="DELETE FROM `StudentScholarship` WHERE StudentScholarshipId='".$_GET['id']."'";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
	if(file_exists($ppath.$_GET['id'].".jpg")) {
		if(!unlink($ppath.$_GET['id'].".jpg")) {
			echo("ERROR: Can't delete existing photo");
			exit(0);
		}
	}
	$lqry.=$qry;
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],21,$lqry);
	header("Location: index.php?pid=108&ewn=225");
	exit();		
}

if(isset($_GET['a']) && $_GET['a']==2) {
	$fcheck=$_POST['fcheck'];
	for($i=0;$i<count($fcheck);$i++) {		
		$qry="DELETE FROM `StudentScholarship` WHERE StudentScholarshipId='".$fcheck[$i]."'";
		$lqry.=$qry."\n\r";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		if(file_exists($ppath.$fcheck[$i].".jpg")) {
			if(!unlink($ppath.$fcheck[$i].".jpg")) {
				echo("ERROR: Can't delete existing photo");
				exit(0);
			}
		}
	}
	add_log($db,$_SESSION['userid'],$_SESSION['utype'],21,$lqry);
	header("Location: index.php?pid=108&ewn=224");
	exit();
}

$c.="<font class=\"title\">Scholarships</font><br><Br>
";
//show existing addresses

$qry = "
SELECT StudentScholarship.StudentScholarshipId, StudentScholarship.ScholarshipId, ScholarshipTypes.Name, StudentScholarship.Status, StudentScholarship.SponsorName, StudentScholarship.AppTime, Student.StudentId, Student.Name as SName, Student.Surname 
FROM (`StudentScholarship` LEFT JOIN `Student` USING(StudentId)) LEFT JOIN `ScholarshipTypes` ON(ScholarshipTypes.ScholarshipId=StudentScholarship.ScholarshipId) ";

$add2qry="WHERE 1 ";

if(isset($_GET['clk']) && $_GET['clk']==0) {
		$_SESSION['stype']=0;	
		$_SESSION['statustype']=0;
		$_SESSION['s_name'] =0;
}

if(isset($_POST['stype']) && isset($_POST['statustype']) && isset($_POST['sp_type'])){
	$_SESSION['stype']=$_POST['stype'];
	$_SESSION['statustype']=$_POST['statustype'];
	$_SESSION['s_name']=$_POST['sp_type'];
	if($_POST['stype']==2)
	  $style="style=\"visibility:visible\"";
   	else
	  $style="style=\"visibility:hidden\""; 
  }else
      $style="style=\"visibility:hidden\"";

if(isset($_SESSION['stype']) && isset($_SESSION['statustype'])) {
	$add2qry="WHERE ";
	if($_SESSION['stype']!=0) $add2qry.="StudentScholarship.ScholarshipId=".$_SESSION['stype']." ";
	if($_SESSION['s_name']!='all' and $_SESSION['stype']==2) $add2qry.=" AND StudentScholarship.SponsorName='".$_SESSION['s_name']."'";
	
	$status_tbl[1]="Selected";
	$status_tbl[2]="Alternate";
	$status_tbl[3]="Rejected";
	$status_tbl[4]="Closed";
	
	if($_SESSION['stype']!=0 && $_SESSION['statustype']!=0) $add2qry.=" AND ";
	
	if($_SESSION['statustype']!=0)  {
		$status_opt=$status_tbl[$_SESSION['statustype']];
		$add2qry.="StudentScholarship.Status='$status_opt' ";
	}
	if($_SESSION['stype']==0 && $_SESSION['statustype']==0) $add2qry.="1 "; 
}

$qry.=$add2qry;	

$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY StudentScholarship.StudentScholarshipId ";break;
	case 1 : $qry.="ORDER BY Student.Name ";break;
	case 2 : $qry.="ORDER BY ScholarshipTypes.Name ";break;
	case 3 : $qry.="ORDER BY StudentScholarship.Status ";break;
	case 4 : $qry.="ORDER BY StudentScholarship.AppTime ";break;
	case 5 : $qry.="ORDER BY StudentScholarship.SponsorName desc";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_scholarships']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_SESSION['p_scholarships'])) $_SESSION['p_scholarships']=10;

$paginator_select=get_paginator_select("scholarships",$pid,$order);

$paginator_pages=get_paginator_pages(get_nr_of_pages($db,$qry,"scholarships"),$page,$pid,$order,"scholarships");

$limit=get_limit("scholarships",$page);

$qry.=$limit;

$rs = mysqli_query ($db,$qry) or die ("error<bR>$qry");

$selord[0]="";
$selord[1]="";
$selord[2]="";
$selord[3]="";
$selord[4]="";
$selord[5]="";

$selord[$order]=" SELECTED";

$stradios="<option value=\"0\"".$selord[0].">All</option>";
$qry2="SELECT * FROM ScholarshipTypes WHERE 1";
$rs2 = mysql_query ($qry2,$db) or die ("DB Error!!!");
while ($line2 = mysqli_fetch_array($rs2)) {
	$sel1="";
	if(isset($_SESSION['stype'])) {
		if($line2['ScholarshipId']==$_SESSION['stype']) $sel1=" SELECTED";
	}
	$stradios.="<option value=\"".$line2['ScholarshipId']."\"$sel1>".$line2['Name']."</otion>";
}

//for sponsor combo box
$sp_name="<option value=\"all\"".$selord[0].">All</option>";
$qry3="SELECT distinct SponsorName FROM studentscholarship WHERE 1 order by SponsorName desc";
$rs3 = mysql_query ($qry3,$db) or die ("DB Error!!!");
while ($line3 = mysqli_fetch_array($rs3)) {
	$sel1="";
	if(isset($_SESSION['s_name'])) {
		if($line3['SponsorName']==$_SESSION['s_name']) $sel1=" SELECTED";
	}
	$sp_name.="<option value=\"".$line3['SponsorName']."\"$sel1>".$line3['SponsorName']."</otion>";
}

$sel2[0]="";
$sel2[1]="";
$sel2[2]="";
$sel2[3]="";
$sel2[4]="";
if(isset($_SESSION['statustype'])) {
	$sel2[$_SESSION['statustype']]=" SELECTED";
}

$addact="";
if($order!=-1) $addact="&order=$order";

$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\">
<form>
<b>Sort by:</b>&nbsp;&nbsp;<select name=\"ordertype\" id=\"ordertype\" class=\"inputbox\">
<option value=\"0\"".$selord[0].">ID</option>
<option value=\"1\"".$selord[1].">Student Name</option>
<option value=\"2\"".$selord[2].">Scholarship type</option>
<option value=\"3\"".$selord[3].">Status</option>
<option value=\"4\"".$selord[4].">Applied time</option>
<option value=\"5\"".$selord[5].">Sponsor</option>
</select>&nbsp;&nbsp;
<INPUT TYPE=\"button\" VALUE=\"OK\" onclick=\"sortlist('index.php?pid=108')\" class=\"button\"><br />
</form>
</td>

<td align=\"right\">
$paginator_select
</td>
</tr>
</table>

<br>
<form action=\"index.php?pid=108$addact\" method=\"post\">
<table cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td width=\"100\" align=\"left\" valign=\"bottom\"><b>Show only:</b></td>
<td>
Scholarship types:<br />
<select name=\"stype\" class=\"inputbox\" onchange=\"display_txt(this,'2','lbl')\">
$stradios
</select>

</td>
<td width=\"100\" align=\"center\" valign=\"bottom\"><b>AND</b></td>
<td>
Status:<br />
<select name=\"statustype\" class=\"inputbox\">
<option value=\"0\"".$sel2[0].">All</option>
<option value=\"1\"".$sel2[1].">Selected</option>
<option value=\"2\"".$sel2[2].">Alternate</option>
<option value=\"3\"".$sel2[3].">Rejected</option>
<option value=\"4\"".$sel2[4].">Closed</option>
</select>
</td>
<td width=\"100\" ></td>
<td width=\"100\" align=\"center\" valign=\"bottom\"><label style=\"visibility:hidden;\" id=\"lbl\">Sponsor:<br /></label><select name=\"sp_type\" class=\"inputbox\" ".$style." id=\"2\">$sp_name</select></td>
<td align=\"center\" valign=\"bottom\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" value=\"Filter\" class=\"button\"></td>
</tr>
</table>
</form>

$paginator_pages

<form method=\"post\" action=\"?pid=108&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\">
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>S.No</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>ID</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>StudentID</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Student Name</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Scholarship</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Sponsor</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Status</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Applied time</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Actions</b>
</td>
</tr>
";
$i=0;
while ($line = mysqli_fetch_array($rs)) {
	++$i;
	$when=date("m.d.y",$line['AppTime'])."&nbsp;&nbsp;".date("H:i",$line['AppTime']);
	
	$c.="
	<tr>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<input type=\"checkbox\" name=\"fcheck[]\" value=\"".$line['StudentScholarshipId']."\" />
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$i."</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['StudentScholarshipId']."
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	".$line['StudentId']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	<a href=\"?pid=200&id=".$line['StudentId']."\">".$line['SName']."</a>
	</td>
	<td bgcolor=\"#FAFAFA\">
	
	".$line['Name']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	
	".$line['SponsorName']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Status']."
	</td>
	<td bgcolor=\"#FAFAFA\">
	$when
	</td>
	<td bgcolor=\"#FAFAFA\" align=\"center\">
	<a href=\"?pid=109&id=".$line['StudentScholarshipId']."\"><img src=\"images/edit.gif\" width=\"17\" height=\"17\" border=\"0\" alt=\"Proceed with scholarship\" /></a>&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td>
	<a href=\"?pid=108&a=1&id=".$line['StudentScholarshipId']."\"><img src=\"images/delete.gif\" width=\"17\" height=\"17\" border=\"0\" alt=\"Delete scholarship\" /></a>
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