<!DOCTYPE html>
<html>
<head>
	<title>Test</title>
</head>
<body>
	<?php
		$array = array(1,32,13);

		$myJSON = json_encode($array);

		echo $myJSON;

		$decode = json_decode("[1,2,3]");

		print_r($decode);
	?>
</body>
</html>