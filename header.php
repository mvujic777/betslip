<?php

?>

<head>
<!--.. solution for resubmiting form on refresh page from stackoverflow, not working in safari https://stackoverflow.com/questions/5690541/best-way-to-avoid-the-submit-due-to-a-refresh-of-the-page, best is use csrf tokens--> 
<script>history.pushState({}, "", "")</script>
		<title>Verti by HTML5 UP</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<!-- Link the Spry Validation Checkbox JavaScript library --> 
<script src="../SpryValidationCheckbox.js" type="text/javascript"></script>

	</head>