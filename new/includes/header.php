<?php

$today="<font class=\"white\">Today is:&nbsp;&nbsp;" . $days[date("w")] . ", &nbsp;" . date("Y-m-d") . "</font>";

$header="
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>DOE</title>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">
<link href=\"print.css\" rel=\"stylesheet\" type=\"text/css\" media=\"print\">
<script type=\"text/javascript\" src=\"includes/functions.js\"></script>
</head>
<body vlink=\"#000033\">

	<div class=\"main-container\">
		<div class=\"content\">
	
			<div class=\"top-bar\">
				<div class=\"logo\"></div>
				<div class=\"date\">$today</div>
			</div>

";

?>