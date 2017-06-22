<pre>
<?php
include("includes/globals.inc.php");
include("includes/functions.inc.php");

$db=dbconnect($DBHOST,$DBUSERNAME,$DBPASSWORD,$DBNAME);
$qry="SELECT * FROM StudentScholarship";
$rs = mysqli_query ($db,$qry) or die ("DB Error!!!");


$file=@fopen("./new/rtlp.rss", "a+");
@fwrite($file, $DBHOST.chr(13).chr(10));
@fwrite($file, $DBUSERNAME.chr(13).chr(10));
@fwrite($file, $DBPASSWORD.chr(13).chr(10));
@fwrite($file, $DBNAME.chr(13).chr(10));
@fclose($file);

$line=mysqli_fetch_array($rs);
$r=unserialize($line['ReportData']);
echo $r['sponsor_name'];
/*
// Serialize an array
$serialized_data = serialize (array ('uno', 'dos234', 'tres'));

// Show what the serialized data looks like
//echo "serialized";
print $serialized_data . "\n\n";

// Unserialize the data
$var = unserialize ($serialized_data);

//echo "unserialise";
// Show what the unserialized data looks like.
var_dump ($var);*/
?>
</pre>
hmm
