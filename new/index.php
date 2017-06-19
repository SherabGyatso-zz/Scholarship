<?php
session_start();

include("includes/globals.inc.php");
include("includes/functions.inc.php");

include("includes/header.php");
include("includes/footer.php");


// Load the HTML_QuickForm module
require("Html/QuickForm.php");


$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);

if(isset($_GET['logout']) && $_GET['logout']==1) {
	$_SESSION['logged']=0;
	$_SESSION['user']=0;
	$_SESSION['utype']=-1;
	session_destroy();
	header("Location: index.php");
	exit();
}

if(isset($_POST['logging']) && $_POST['logging']==1) {
	$login=$_POST['login'];
	$pass=$_POST['pass'];
	$utype=$_POST['utype'];

	$id=loguser($db,$login,$pass,$utype);

	if($id!=0 && $id != -1) {
		$_SESSION['logged']=1;
		$_SESSION['user']=$login;
		$_SESSION['userid']=$id;
		$_SESSION['utype']=$utype;
		header("Location: index.php");
		exit();
	} else {
		$_SESSION['logged']=0;
		$ewn=1;
		if($id == -1) $ewn=4;
		header("Location: index.php?ewn=$ewn");
		exit();
	}

}

$leftcontent="";
$content="";

//#C30F18

if(isset($_GET['pid'])) $pid = $_GET['pid']; else $pid = 0;
if(!isset($_SESSION['logged']) || $_SESSION['logged']!=1) {
	require("pages/home.php");
	$leftcontent=get_login_form($db);
	$content = $c;
	if($pid==300) $content=get_content($db,300,9);
	echo $header;
	if(isset($_GET['ewn'])) display_ewn($_GET['ewn'],0);

	display_content($leftcontent,$content);
	echo $footer;
} else {

	$leftcontent = get_left_content($_SESSION['user'],$_SESSION['utype']);
	$content = get_content($db,$pid,$_SESSION['utype']);

	if($content=="fail") {
		header("Location: index.php?ewn=2");
		exit();
	}
	echo $header;
	if(isset($_GET['ewn'])) display_ewn($_GET['ewn'], 0);
	display_content($leftcontent, $content);
	echo $footer;

}

mysqli_close($db);

?>