<?php

$id = isset($_GET['id']) ? $_GET['id'] : die("ERROR");

$qry = "SELECT * FROM news WHERE id=$id";
$rs = mysql_query($qry,$db);
$line = mysqli_fetch_array($rs);
$c .="<b>".$line['title']."</b><br><font style=\"color: grey;font-size: 10px\">".date('d.m.Y',$line['dateadded'])."</font><br /><i>".$line['short']."</i><br /><br>
".$line['long']."<br>
<br>

<a href=\"?pid=0\">go back &raquo;<br><Br>";

?>