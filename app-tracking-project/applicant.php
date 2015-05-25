<?php 
	require 'header.inc.php';
	require 'Database.class.php';

	$positions = new Database();
	$available = $positions->getTable('positions');
?>
<div id='container'>
	<div class='centered'>
	<h1>Open Positions</h1>
	<ul>
		<?php
			// $available is an array of associative arrays.
			foreach($available as $position) {
				$open_position = $position['Position'];

				$position_url = urlencode($open_position);

				echo "<li><a href=$PATH" . "/description.php?position=$position_url>$open_position</a></li>";
			}
		?>
	</ul>	
	</div>
<?php require 'footer.php'; ?>
</div>


