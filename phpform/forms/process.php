<?php
include("global.inc.php");
$errors=0;
$error="The following errors occured while processing your form input.<ul>";
pt_register('POST','Name');
pt_register('POST','DOB');
pt_register('POST','Course');
pt_register('POST','College');
pt_register('POST','ClassXIIid');
pt_register('POST','RCno');
pt_register('POST','Greenbookno');
pt_register('POST','Duration');
pt_register('POST','Degree');
if($errors==1) echo $error;
else{
$where_form_is="http".($HTTP_SERVER_VARS["HTTPS"]=="on"?"s":"")."://".$SERVER_NAME.strrev(strstr(strrev($PHP_SELF),"/"));
$message="Name: ".$Name."
DOB: ".$DOB."
Course: ".$Course."
College: ".$College."
Class XII id: ".$ClassXIIid."
RC no: ".$RCno."
Greenbook no: ".$Greenbookno."
Duration: ".$Duration."
Degree: ".$Degree."
";
$message = stripslashes($message);
mail("sherabguerdat@gmail.com","Form Submitted at your website",$message,"From: phpFormGenerator");
$make=fopen("admin/data.dat","a");
$to_put="";
$to_put .= $Name."|".$DOB."|".$Course."|".$College."|".$ClassXIIid."|".$RCno."|".$Greenbookno."|".$Duration."|".$Degree."
";
fwrite($make,$to_put);
?>


<!-- This is the content of the Thank you page, be careful while changing it -->

<h2>Thank you!</h2>

<table width=50%>
<tr><td>Name: </td><td> <?php echo $Name; ?> </td></tr>
<tr><td>DOB: </td><td> <?php echo $DOB; ?> </td></tr>
<tr><td>Course: </td><td> <?php echo $Course; ?> </td></tr>
<tr><td>College: </td><td> <?php echo $College; ?> </td></tr>
<tr><td>Class XII id: </td><td> <?php echo $ClassXIIid; ?> </td></tr>
<tr><td>RC no: </td><td> <?php echo $RCno; ?> </td></tr>
<tr><td>Greenbook no: </td><td> <?php echo $Greenbookno; ?> </td></tr>
<tr><td>Duration: </td><td> <?php echo $Duration; ?> </td></tr>
<tr><td>Degree: </td><td> <?php echo $Degree; ?> </td></tr>
</table>
<!-- Do not change anything below this line -->

<?php 
}
?>