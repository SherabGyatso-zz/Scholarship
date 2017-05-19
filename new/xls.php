<?php

error_reporting(E_ALL); // Notice errors break the Excel file format. 
// Creating a workbook

session_start();

include("includes/globals.inc.php");
include("includes/functions.inc.php");

require_once 'Spreadsheet/Excel/Writer.php';

$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);

if(isset($_SESSION['logged']) && $_SESSION['logged']==1) {
	
	if(!isset($_POST['stype'])) die('Scholarship Type is not selected');
	
	//worksheet header
	
	$header = "";
	
	$header.='"S.No",';
	$header.='"Fr.No",';
	$header.='"Name",';
	$header.='"Sex",';
	$header.='"DoB",';
	$header.='"Roll No",';
	$header.='"Course",';
	$header.='"R.C.",';
	$header.='"G.B.",';
	$header.='"F.G.B.",';
	$header.='"M.G.B.",';
	$header.='"Sps.G.B.",';
	$header.='"Parent (Death/Tibet)",';
	$header.='"BRDL",';
	$header.='"Med.Cer.",';
	$header.='"X M.C.",';
	$header.='"Cert.",';
	$header.='"XII M.C.",';
	$header.='"Cert.",';
	$header.='"%",';
	$header.='"Bachelor M.C.",';
	$header.='"Cert.",';
	$header.='"%",';
	$header.='"Additional M.C.",';
	$header.='"%",';
	$header.='"Bachelor & Additional Course Undertaken",';
	$header.='"TOEFL Score",';
	$header.='"NOC",';
	$header.='"Address",';
	$header.='"Remarks"';
	$header .= "\n";
	
	//date range and scholarship type
	$dstart=mktime(0,0,0,$_POST['m_start'],$_POST['d_start'],$_POST['y_start']);
	$dend=mktime(23,59,59,$_POST['m_end'],$_POST['d_end'],$_POST['y_end']);
	$sid = $_POST['stype'];
	$content = $header;
	$sn=0;
	
	$qry="SELECT * FROM `StudentScholarship` WHERE ScholarshipId='$sid' AND AppTime BETWEEN $dstart AND $dend";
	$rs = mysqli_query ($db,$qry) or die ("DB Error!!! 1");
	while($line=mysqli_fetch_array($rs)) {
		
		$r ="";
		$d=unserialize($line['FormData']);
		$sn++;
		
		$r .= "\"$sn\",";
		
		$r .= "\"".$line['StudentScholarshipId']."\",";
		$r .= "\"".$d['applicant_name_surname']."\",";
		$gender=($d['gender'] == 0) ? 'M' : 'F';
		$r .= "\"$gender\",";
		$r .= "\"".$d['date_of_birth']."\",";
		//$line['StudentId'];
		$r .= "\"\",";
		$r .= "\"".$d['course_or_subject']."\",";
		$r .= "\"".$d['residential_certificate_no']."\",";
		$gb = ($d['green_book_no']=='') ? 'N/A' : $d['green_book_no'];
		$fgb = ($d['f_green_book_no']=='') ? 'N/A' : $d['f_green_book_no'];
		$mgb = ($d['m_green_book_no']=='') ? 'N/A' : $d['m_green_book_no'];
		$sgb = ($d['s_green_book_no']=='') ? 'N/A' : $d['s_green_book_no'];
		$r .= "\"$gb\",\"$fgb\",\"$mgb\",\"$sgb\",\"\",\"\",";
		$mfc = ($d['medical_fitness_c_s'] == 0) ? 'Y' : 'N';
		$r .= "\"$mfc\",";
		$r .= "\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",\"\",";
		if(array_key_exists('toefl_ielts_score',$d)) {
		  $toefl = ($d['toefl_ielts_score'] == '') ? 'N/A' : $d['toefl_ielts_score'];
		} else $toefl="";
		$r .= "\"$toefl\",";
		$r .= "\"\",\"".$d['permanent_address']."\",\"\"\n";
		
		$content .= $r;
	}
	
header("Content-type: text/csv");
header("Content-disposition: attachment; filename=scholarships.csv");
header("Pragma: no-cache");

echo $content;
}
?>