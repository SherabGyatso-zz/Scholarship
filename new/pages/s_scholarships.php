<?php
//session_start();

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
SELECT studentscholarship.StudentScholarshipId, studentscholarship.ScholarshipId, scholarshiptypes.Name, studentscholarship.Status, studentscholarship.SponsorName, studentscholarship.category, studentscholarship.Remarks, studentscholarship.StudyCourse, studentscholarship.AppTime, student.StudentId, student.Name AS SName, student.Surname, course.Course, studentscholarship.specialize, studentscholarship.From, studentscholarship.To
FROM (scholarshiptypes INNER JOIN (student INNER JOIN studentscholarship ON student.StudentId = studentscholarship.StudentId) ON scholarshiptypes.ScholarshipId = studentscholarship.ScholarshipId) LEFT JOIN course ON studentscholarship.courseid = course.courseid ";
//$add2qry="WHERE 1 ";

if(isset($_GET['clk']) && $_GET['clk']==0) {
		$_SESSION['stype']=0;	
		$_SESSION['statustype']=0;
		$_SESSION['s_name'] =0;
		$_SESSION['stu_name']="";
		$_SESSION['course']="";
		$_SESSION['institute']="";
		$add2qry ="";
}
if(isset($_POST['stu_name']))
    $_SESSION['stu_name']=$_POST['stu_name'];
if(isset($_POST['course']))
	$_SESSION['course']=$_POST['course'];
if(isset($_POST['institute']))
	$_SESSION['institute']=$_POST['institute'];
if(isset($_POST['year']))
   $_SESSION['year']=$_POST['year'];

if(isset($_POST['stype']) && isset($_POST['statustype'])){
	$_SESSION['stype']=$_POST['stype'];
	$_SESSION['statustype']=$_POST['statustype'];
	$_SESSION['s_name']=$_POST['sp_type'];
    $_SESSION['year']=$_POST['year'];
	$_SESSION['s_cat']=$_POST['sp_type'];

	if($_POST['stype']==2 or $_POST['stype']==1)
	  $style="style=\"visibility:visible\"";
   	else
	  $style="style=\"visibility:hidden\"";  
  }
 elseif($_SESSION['stype']==2  or $_SESSION['stype']==1)  //this apply when we click on >>back to scholarship on other page
	  $style="style=\"visibility:visible\"";
 else
      $style="style=\"visibility:hidden\"";  //pply when we click on >>back to scholarship on other page but the choice is not DOE
 $year="";
if(isset($_SESSION['stype']) && isset($_SESSION['statustype'])) {
	//$add2qry="WHERE ";
	
	if($_SESSION['stype']!=0) $add2qry.=" AND StudentScholarship.ScholarshipId=".$_SESSION['stype']." ";
	//for sponsor name
	if($_SESSION['s_name']!='all' and $_SESSION['stype']==2) 
	$add2qry.=" AND StudentScholarship.SponsorName='".$_SESSION['s_name']."'";
	elseif($_SESSION['s_name']!='all' and $_SESSION['stype']==1)  	//for category
	$add2qry.=" AND StudentScholarship.category='".$_SESSION['s_name']."'";
	elseif($_SESSION['s_name']=='all')  $add2qry.="";
	else
	unset($_SESSION['s_name']);
	if($_SESSION['year']!='all')
	 {
	  if($_SESSION['statustype']==1)
	   	$add2qry.=" AND year(studentscholarship.From) = '".$_SESSION['year']."' ";
	  else
	  	$add2qry.=" AND year(studentscholarship.To) = '".$_SESSION['year']."' ";
	  }
	if($_SESSION['stu_name']!="") 
	 {
	   $add2qry.=" AND student.name like '%".$_SESSION['stu_name']."%' ";
	   }
   
	if($_SESSION['course']!="")
	   {
		$add2qry.=" AND course.Course like '".$_SESSION['course']."%'";
		}
	if($_SESSION['institute']!="")
	  {
		$add2qry.=" AND StudentScholarship.NameOfInstitution like '%".$_SESSION['institute']."%' ";
	  }
	$status_tbl[1]="Selected";
	$status_tbl[2]="Alternate";
	$status_tbl[3]="Rejected";
	$status_tbl[4]="Closed";
	$status_tbl[5]="Discontinued";
	
	//if($_SESSION['stype']!=0 && $_SESSION['statustype']!=0)  $add2qry.=" AND ";
   
		
	if($_SESSION['statustype']!=0)  {
		$status_opt=$status_tbl[$_SESSION['statustype']];
		$add2qry.=" AND StudentScholarship.Status='$status_opt' ";
	}
	
	//if($_SESSION['stype']==0 && $_SESSION['statustype']==0) $add2qry.="1 "; 
	// echo substr($add2qry,4);
  
}
if(substr($add2qry,0,4) == " AND")
	  $add2qry=substr($add2qry,5);
	else
	   $add2qry.=" 1 ";
	   
$qry.=" where ".$add2qry;	
//echo $qry;
$order=-1;
if(!isset($_GET['order'])) $order=0; else $order=$_GET['order'];
switch($order) {
	case 0 : $qry.="ORDER BY StudentScholarship.StudentScholarshipId desc";break;
	case 1 : $qry.="ORDER BY Student.Name ";break;
	case 2 : $qry.="ORDER BY ScholarshipTypes.Name ";break;
	case 3 : $qry.="ORDER BY StudentScholarship.Status ";break;
	case 4 : $qry.="ORDER BY StudentScholarship.AppTime ";break;
	case 5 : $qry.="ORDER BY StudentScholarship.SponsorName desc";break;
}

if(isset($_POST['new_limit'])) $_SESSION['p_scholarships']=$_POST['new_limit'];
if(!isset($_GET['page'])) $page=1; else $page=$_GET['page'];
if(!isset($_GET['sno'])) $i=0; else $i=$_GET['sno'];
  
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
$rs2 = mysqli_query ($db,$qry2) or die ("DB Error!!!");
while ($line2 = mysqli_fetch_array($rs2)) {
	$sel1="";
	if(isset($_SESSION['stype'])) {
		if($line2['ScholarshipId']==$_SESSION['stype']) $sel1=" SELECTED";
	}
	$stradios.="<option value=\"".$line2['ScholarshipId']."\"$sel1>".$line2['Name']."</otion>";
}

//for sponsor combo box
$sp_name="<option value=\"all\">All</option>";
$qry3="SELECT distinct SponsorName FROM studentscholarship where StudentScholarship.ScholarshipId=2 order by SponsorName asc";
$rs3 = mysqli_query ($db,$qry3) or die ("DB Error!!!");
while ($line3 = mysqli_fetch_array($rs3)) {
	$sel1="";
	if(isset($_SESSION['s_name'])) {
		if($line3['SponsorName']==$_SESSION['s_name']) $sel1=" SELECTED";
	}
	$sp_name.="<option value=\"".$line3['SponsorName']."\"$sel1>".$line3['SponsorName']."</otion>";
}
//for oversea scholarship category combo box
$cat="";
$cat="<option value=\"all\" SELECTED>All</option>";

$qry4="SELECT distinct category FROM studentscholarship WHERE 1 order by category desc";
$rs4 = mysqli_query ($db, $qry4) or die ("DB Error!!!");
while ($line4 = mysqli_fetch_array($rs4)) {
	$sel1="";
	if(isset($_SESSION['s_cat'])) {
		if($line4['category']==$_SESSION['s_cat']) $sel1=" SELECTED";
	}
	$cat.="<option value=\"".$line4['category']."\"$sel1>".$line4['category']."</otion>";

}

//for year combo box
$year_combo="<option value=\"all\" SELECTED><--Select Year--></option>";
if($status_opt=='Selected')
$qry5 = "SELECT distinct year(studentscholarship.From) as year FROM studentscholarship WHERE 1 order by studentscholarship.From desc";
else
$qry5="SELECT distinct year(studentscholarship.To) as year FROM studentscholarship WHERE 1 order by studentscholarship.To desc";
$rs5 = mysqli_query ($db, $qry5) or die ("DB Error!!!");
while ($line5 = mysqli_fetch_array($rs5)) {
	$sel1="";
	if(isset($_SESSION['year'])) {
		if($line5['year']==$_SESSION['year']) $sel1=" SELECTED";
	}
	$year_combo.="<option value=\"".$line5['year']."\"$sel1>".$line5['year']."</otion>";
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

if($_POST['stype']==2 or $_SESSION['stype']==2)
{
 $id_txt='2';
 $label = 'lbl';
 $combo = $sp_name;
 }
 
 if($_POST['stype']==1 or $_SESSION['stype']==1){
 $id_txt='1';
 $label = 'lbl';
 $combo = $cat;
 }
 
$c.="
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" >
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
<table  cellpadding=\"0\" cellspacing=\"0\">
<tr>
<td align=\"left\" valign=\"bottom\"><b>Scholarship types:</b></td>
<td>
";
//display_txt function is not firing ...y?
//$c.="<select name=\"stype\" class=\"inputbox\" onchange=\"display_txt(this,'".$id_txt."','".$label."')\">";
$c.="<select name=\"stype\" onchange=\"this.form.submit();\">
$stradios
</select>

</td>
<td width=\"100\" align=\"center\"><b>Status:</b></td>
<td>
<select name=\"statustype\" onchange=\"this.form.submit();\" >
<option value=\"0\"".$sel2[0].">All</option>
<option value=\"1\"".$sel2[1].">Selected</option>
<option value=\"5\"".$sel2[5].">Discontinued</option>
<option value=\"2\"".$sel2[2].">Alternate</option>
<option value=\"3\"".$sel2[3].">Rejected</option>
<option value=\"4\"".$sel2[4].">Closed</option>
<option value=\"6\"".$sel2[6].">Non Returnee</option>
<option value=\"7\"".$sel2[7].">Returnee</option>
</select>
</td>";

$c.="<td><label ".$style." id=\"".$label."\">Sponsor/Category:</label></td><td><select name=\"sp_type\"  ".$style." id=\"".$id_txt."\">".$combo."</select></td>
</tr>
<tr><td>Student Name:</td><td><input type=text name=stu_name value=".$_SESSION['stu_name']."></td>
<td>Course:</td><td><input type=text name=course size=\"5\" value=".$_SESSION['course']."></td>
<td>Institute/Place:</td><td><input type=text name=institute value=".$_SESSION['institute']."></td>
</tr>
<tr><td>Year:</td><td><select name=\"year\"  ".$style1." id=\"".$id_txt1."\">".$year_combo."</select></td>
<td align=\"center\" valign=\"bottom\"><input type=\"submit\" value=\"Filter\" class=\"button\"></td>
</tr>

</table>
</form>

$paginator_pages

<form method=\"post\" action=\"?pid=108&a=2\">
<table width=\"100%\" cellpadding=\"3\" cellspacing=\"2\" >
<tr>
<td bgcolor=\"whitesmoke\" align=\"center\" width=\"20\">
&nbsp;
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>S.No</b>
</td>
<td bgcolor=\"whitesmoke\" align=\"center\">
<b>Student Name</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Scholarship</b>
</td>";
if($_POST['stype']==2 or $_SESSION['stype']==0)
 {
 $c.="<td bgcolor=\"whitesmoke\">
<b>Sponsor</b>
</td>";
}elseif($_SESSION['stype']==2){ 
$c.="<td bgcolor=\"whitesmoke\">
<b>Sponsor</b>
</td>";
}else
$c.="<td bgcolor=\"whitesmoke\">
<b>Category</b>
</td>";	


$c.="<td bgcolor=\"whitesmoke\">
<b>Status</b>
</td>
<td bgcolor=\"whitesmoke\">
<b>Course</b>
</td>
<!--<td bgcolor=\"whitesmoke\">
<b>Applied time</b>
</td>-->
<td bgcolor=\"whitesmoke\" align=\"center\" colspan=\"2\">
<b>Actions</b>
</td>
</tr>
";

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
	<td bgcolor=\"#FAFAFA\">
	<a href=\"?pid=200&id=".$line['StudentId']."\">".$line['SName']."</a>
	</td>
	<td bgcolor=\"#FAFAFA\">
	".$line['Name'];
	if($line['Remarks']!="") 
	$c.=" (".$line['Remarks'].") ";
	$c.="</td>";
	if($line['ScholarshipId']==2)
	{
	$c.="<td bgcolor=\"#FAFAFA\">
	".$line['SponsorName']."
	</td>";
	}else{
	$c.="<td bgcolor=\"#FAFAFA\">
	".$line['category']."
	</td>";
	}
	$c.="<td bgcolor=\"#FAFAFA\">
	".$line['Status']."
	</td>";
	
	if(empty($line['Course']))
	 {
		 $c.="<td bgcolor=\"#FAFAFA\">".$line['StudyCourse']."</td>";
	}
	else
	 {
	 $c.="<td bgcolor=\"#FAFAFA\">".$line['Course']."";
		if(empty($line['specialize']))
			$c.="</td>";
		else
			$c.="(".$line['specialize'].")</td>";
	 }
	$c.="
	<!--<td bgcolor=\"#FAFAFA\">
	//$when
	</td>-->
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