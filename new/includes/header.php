<?php

$today="<font class=\"white\">Today is:&nbsp;&nbsp;" . $days[date("w")] . ", &nbsp;" . date("Y-m-d") . "</font>";

$header="
<!DOCTYPE html>
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
<title>DOE | Scholarship</title>
<link rel=\"icon\" type=\"images/jpeg\" href=\"/scholarship_new/new/images/logo.png\"/>
<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">
<link href=\"print.css\" rel=\"stylesheet\" type=\"text/css\" media=\"print\">
<script type=\"text/javascript\" src=\"includes/functions.js\"></script>
</head>
<body>
	<div class=\"header\">
		<div class=\"content\">
			<div class=\"top-bar\">
				<div class=\"logo\">
        </div>
				<div class=\"date\">$today</div>
			</div>
    </div>
  </div>
";

?>
