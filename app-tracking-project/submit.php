<?php
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['LastName'] !== '') {
		echo "<p>hello</p>";
		echo $_POST["LastName"];
}
?>
