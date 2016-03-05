<?php
	require 'header.inc.php';
	require 'jobdescriptions.php';
	use Jason\Database;

	function get_available()
	{
		$connect = new Database();
		$available = $connect->getTable('positions');
		$connect = null;

		return $available;
	}
	
	// NEED TO REDO THIS FUNCTION TO HANDLE THE CASE WHERE POSITION IS NO LONGER AVAILABLE
	function build_app_link(array $available, $position)
	{
		include "settings.php";
		$connect = new Database();
		$available_positions = $connect->build_column_array('positions', 'Position');
		$connect = null;
		
		$position_url = urlencode($position);
		// Check to see if the position is available
		if (in_array($position, $available_positions)) {
			$apply_url = "$PATH" . "/apply.php?position=$position_url";
			return $apply_url;
		} else {
			// TODO - Should make this a redirect to somewhere with an alert
			echo "$position is no longer available";
		}		
	}

	// This will return either the current position or "Default" if there's no job description for the position
	function get_job_description_key($position, $descriptions)
	{
		$key = '';
		if (isset($descriptions[$position])) {
			$key = $position;
		} else {
			$key = "Default";
		}

		return $key;
	}

	$position = $_GET["position"];
	$available = get_available();
	$link = build_app_link($available, $position);
	// Pass in the current position and the descriptions global array from jobscriptions.php
	$key = get_job_description_key($position, $DESCRIPTIONS);
?>


<div class="jumbotron">
	<div class="container">
		<h1><?php echo $position ?></h1>
		<p><?php echo $DESCRIPTIONS[$key]["Summary"]; ?></p>
		<p><a class="btn btn-primary btn-lg" role="button" href=<?php echo $link; ?>>Apply Now!</a></p>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h2>Responsibilities</h2>
				<ul>
					<?php foreach ($DESCRIPTIONS[$key]["Responsibilities"] as $responsibility) {
						echo "<li>$responsibility</li>";
					}
					?>
				</ul>
		</div>
		<div class="col-md-6">
			<h2>Requirements</h2>
				<ul>
					<?php foreach ($DESCRIPTIONS[$key]["Requirements"] as $requirement) {
						echo "<li>$requirement</li>";
					}
  					?>
  				</ul>	
  		</div>
  	</div>
</div> 				
<?php require 'footer.php'; ?>


