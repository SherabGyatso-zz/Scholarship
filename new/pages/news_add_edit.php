<?php
$c="";

if(isset($_POST['submitted']) && $_POST['submitted']==1) {
	$ewnid=0;
	$next_pid="115";
	$lqry="";
	$trans = array("\n" => "<br />", "\r" => "");
	$towrite_s=stripslashes($_POST['short']);
	$towrite_s = strtr($towrite_s, $trans);
	$towrite_l=stripslashes($_POST['long']);
	$towrite_l = strtr($towrite_l, $trans);
	if(isset($_GET['a']) && $_GET['a']=="a") {
		$now = time();
		
		$qry = "
		INSERT INTO `news` ( `id` , `title` , `short` , `long` , `dateadded` ) 
		VALUES (
		'', '".$_POST['title']."', '$towrite_s', '$towrite_l', '$now'
		)";
		$lqry.=$qry;
		$ewnid=232;
		if($_POST['next_opt']==0) $next_pid="115";
		if($_POST['next_opt']==1) $next_pid="116&a=a";
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],22,$lqry);
	} else if(isset($_GET['a']) && $_GET['a']=="e") {
		$qry = "
		UPDATE `news` 
		SET `title` = '".$_POST['title']."', `short` = '$towrite_s', `long` = '$towrite_l' 
		WHERE `id`='".$_POST['id']."'";
		$ewnid=233;	
		$lqry.=$qry;
		add_log($db,$_SESSION['userid'],$_SESSION['utype'],23,$lqry);
	}
	if(mysql_query($qry,$db)) {
		header("Location: index.php?pid=".$next_pid."&ewn=".$ewnid."");
		exit();
	}
}

$title="Add news";
if(isset($_GET['a']) && $_GET['a']=="e") $title="Edit news";
$c.="<font class=\"title\">$title</font><br><Br>";

if(isset($_GET['a'])) {
	if($_GET['a']=="a") {
		$sc_value="";
		$scf="?pid=116&a=a";
	} else if($_GET['a']=="e") {
		$scf="?pid=116&a=e&id=".$_GET['id']."";
		$qry = "SELECT * FROM news WHERE id='".$_GET['id']."'";
		$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");
		$line = mysqli_fetch_array($rs);
		$title_value = $line['title'];
		$trans = array("<br />" => "\n");
		$short_value=strtr($line['short'],$trans);
		$long_value=strtr($line['long'],$trans);

		$added_value=date('d.m.Y',$line['dateadded']);
	}
}

$date_added_txt = "";
if(isset($_GET['a']) && $_GET['a']=="e") $date_added_txt="<b>Date added:</b>&nbsp;&nbsp;$added_value<br>";

$form="
<form action=\"$scf\" method=\"post\">
<input type=\"hidden\" name=\"id\" value=\"".$_GET['id']."\" />
$date_added_txt
<br> 
<b>Title:</b>&nbsp;&nbsp;
	<input type=\"text\" size=\"10\" name=\"title\" maxlength=\"255\" class=\"inputbox\" value=\"$title_value\" />
<b>News header:</b><br>
<textarea name=\"short\">$short_value</textarea><br>
<b>News fulltext:</b><br>
<textarea name=\"long\">$long_value</textarea><br>
<br>
";

$c.=$form;

$c.="
<input type=\"hidden\" name=\"submitted\" value=\"1\" /><br />
";
if(isset($_GET['a']) && $_GET['a']=="a") {
$c.="
<input type=\"radio\" name=\"next_opt\" value=\"0\" checked />&nbsp;Save and go back to list<br />
<input type=\"radio\" name=\"next_opt\" value=\"1\" />&nbsp;Save and add next news<br />
<br />"; 
}

$c.="<input type=\"submit\" value=\"Submit\" class=\"button\" />&nbsp;&nbsp;&nbsp;
</form>
<a href=\"index.php?pid=115\">&laquo;&nbsp;&nbsp;back to the news list</a>
";

?>
