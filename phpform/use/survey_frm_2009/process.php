<?php
include("global.inc.php");
$errors=0;
$error="The following errors occured while processing your form input.<ul>";
pt_register('POST','name');
pt_register('POST','PlaceofBirth');
pt_register('POST','Dateofbirth');
pt_register('POST','Gender');
pt_register('POST','EducationandProfessionofParents');
pt_register('POST','completegraduationorpostgraduation');
pt_register('POST','Education');
$Education=preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $Education);pt_register('POST','WorkExperience');
pt_register('POST','Fundingsources');
pt_register('POST','Inwahtareaofexpertisedoyoufeelyoucanbestcontributetothetibetancommunity');
pt_register('POST','PresentAddress');
$PresentAddress=preg_replace("/(\015\012)|(\015)|(\012)/","&nbsp;<br />", $PresentAddress);pt_register('POST','Phone');
pt_register('POST','email');
if($name=="" || $PlaceofBirth=="" || $Dateofbirth=="" || $Gender=="" || $EducationandProfessionofParents=="" || $completegraduationorpostgraduation=="" || $Education=="" || $WorkExperience=="" || $Inwahtareaofexpertisedoyoufeelyoucanbestcontributetothetibetancommunity=="" || $PresentAddress=="" || $Phone=="" || $email=="" ){
$errors=1;
$error.="<li>You did not enter one or more of the required fields. Please go back and try again.";
}
if(!eregi("^[a-z0-9]+([_\\.-][a-z0-9]+)*" ."@"."([a-z0-9]+([\.-][a-z0-9]+)*)+"."\\.[a-z]{2,}"."$",$email)){
$error.="<li>Invalid email address entered";
$errors=1;
}
if($errors==1) echo $error;
else{
$where_form_is="http".($HTTP_SERVER_VARS["HTTPS"]=="on"?"s":"")."://".$SERVER_NAME.strrev(strstr(strrev($PHP_SELF),"/"));
$message="name: ".$name."
Place of Birth: ".$PlaceofBirth."
Date of birth: ".$Dateofbirth."
Gender: ".$Gender."
Education and Profession of Parents: ".$EducationandProfessionofParents."
complete graduation or post graduation: ".$completegraduationorpostgraduation."
Education: ".$Education."
Work Experience: ".$WorkExperience."
Funding sources : ".$Fundingsources."
In waht area of expertise do you feel you can best contribute to the tibetan community: ".$Inwahtareaofexpertisedoyoufeelyoucanbestcontributetothetibetancommunity."
Present Address: ".$PresentAddress."
Phone: ".$Phone."
email: ".$email."
";
$message = stripslashes($message);
mail("doe.project@gov.tibet.net","Form Submitted at your website",$message,"From: phpFormGenerator");
$make=fopen("admin/data.dat","a");
$to_put="";
$to_put .= $name."|".$PlaceofBirth."|".$Dateofbirth."|".$Gender."|".$EducationandProfessionofParents."|".$completegraduationorpostgraduation."|".$Education."|".$WorkExperience."|".$Fundingsources."|".$Inwahtareaofexpertisedoyoufeelyoucanbestcontributetothetibetancommunity."|".$PresentAddress."|".$Phone."|".$email."
";
fwrite($make,$to_put);
?>


<!-- This is the content of the Thank you page, be careful while changing it -->

<h2>Thank you!</h2>

<table width=50%>
<tr><td>name: </td><td> <?php echo $name; ?> </td></tr>
<tr><td>Place of Birth: </td><td> <?php echo $PlaceofBirth; ?> </td></tr>
<tr><td>Date of birth: </td><td> <?php echo $Dateofbirth; ?> </td></tr>
<tr><td>Gender: </td><td> <?php echo $Gender; ?> </td></tr>
<tr><td>Education and Profession of Parents: </td><td> <?php echo $EducationandProfessionofParents; ?> </td></tr>
<tr><td>complete graduation or post graduation: </td><td> <?php echo $completegraduationorpostgraduation; ?> </td></tr>
<tr><td>Education: </td><td> <?php echo $Education; ?> </td></tr>
<tr><td>Work Experience: </td><td> <?php echo $WorkExperience; ?> </td></tr>
<tr><td>Funding sources : </td><td> <?php echo $Fundingsources; ?> </td></tr>
<tr><td>In waht area of expertise do you feel you can best contribute to the tibetan community: </td><td> <?php echo $Inwahtareaofexpertisedoyoufeelyoucanbestcontributetothetibetancommunity; ?> </td></tr>
<tr><td>Present Address: </td><td> <?php echo $PresentAddress; ?> </td></tr>
<tr><td>Phone: </td><td> <?php echo $Phone; ?> </td></tr>
<tr><td>email: </td><td> <?php echo $email; ?> </td></tr>
</table>
<!-- Do not change anything below this line -->

<?php 
}
?>