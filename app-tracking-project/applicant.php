<?php 
	require 'header.inc.php';
	require __DIR__ . '/classes/Database.class.php';

	$positions = new Database();
	$available = $positions->getTable('positions');
?>
<div class='jumbotron'>
	<div class='container'>
		<h1>Open Positions</h1>
	</div>
</div>	
<div class='container'>

		<?php
			// $available is an array of associative arrays.
			foreach($available as $position) {
				$open_position = $position['Position'];

				$position_url = urlencode($open_position);

				echo "<h4><a href=$PATH" . "/description.php?position=$position_url>$open_position</a></h4>";
			}
		?>
	
</div>

<?php require 'footer.php'; ?>



