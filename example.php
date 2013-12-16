<?php 

	ini_set('DISPLAY_ERRORS', 'ON');
	require_once 'client.class.php';

?>
<!DOCTYPE html>
<html class="<?=Client::$Browser->HTMLClasses?>" lang="en">
<head>
	<meta charset="UTF-8">
	<title>Browser Identification</title>
</head>
<body>
	<pre>
		<?php
		print_r(Client::$Browser);
		echo '<hr />';
		print_r(Client::$System);
		echo '<hr />';
		print_r(Client::$Execution);
		?>
	</pre>
</body>
</html>