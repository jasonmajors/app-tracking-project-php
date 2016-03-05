<?php
    require 'header.inc.php';
    require __DIR__ . '/classes/Authenticate.class.php';
    use Jason\Database;

    Authenticate::login_required_redirect('/login.php');

    
    function get_application()
    {
        // Get the application ID.
    	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
	   	   	$app_id = $_GET['id'];	
        // $_POST['id'] is passed as a hidden value in the form	
    	} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    		$app_id = $_POST['id'];
    	}

    	$db_connect = new Database();
	    $applicant = $db_connect->getTable($table='applications', $field='ID', $condition=$app_id);
	    $db_connect = null;
	    // $applicant will be an array of 1 application array.
	    $application = $applicant[0]; 

	   	return $application;
    }

    function get_next_status($status)
    {
        switch($status) {
            case 'Pending':
                $new_status = 'Interview';
                break;
            case 'Interview':
                $new_status = 'Processing';
                break;
            case 'Processing':
                // Not going to add a panel for Orientation but keeping this incase I do in the future.
                $new_status = 'Orientation';
                break;   
            case 'Deleted':
                $new_status = 'Deleted';   
        } 
        return $new_status; 
    }

    function change_app_status(array $application, $unix_date)
    {
    	$id = $application['ID'];
    	$status = $application['App_Status'];
    	$new_status = get_next_status($status);	
    	// Cast to array in order to use the alterApplication method.
    	$id_array = (array)$id;
    	$db_connect = new Database();
        $db_connect->alterApplication($id_array, 'Date', $unix_date);
    	$db_connect->alterApplication($id_array, 'App_Status', $new_status);
    	$db_connect = null;
    }

    function format_date($input_date, $input_time)
    { 
        $input_datetime = $input_date . ' ' . $input_time;
        $datetime = strtotime($input_datetime);
        return $datetime;
    }

    function main()
    {
    	$application = get_application();
        // Need to pass the next step for the applicant so we can use it in the scheduling form.
        $next_status = get_next_status($application['App_Status']);
        $error_msg = '';

    	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input_time = $_POST['time'];
            $input_date = $_POST['date'];

            // Could wrap this into a function 'validate_datetime'
            // Validate the form
            if (empty($input_time) && empty($input_date)) {
                $error_msg = 'Please enter a valid time and date';
                return array($application, $next_status, $error_msg);
            }
            elseif (empty($input_date)) {
                $error_msg = 'Please enter a valid date';
                return array($application, $next_status, $error_msg);
            }
            elseif (empty($input_time)) {
                $error_msg = 'Please enter a valid time';
                return array($application, $next_status, $error_msg);
            }
            else {
                // Both fields filled
                $unix_datetime = format_date($input_date, $input_time);
            }
            // End hypothetical function 'validate_datetime'
            
            if ($unix_datetime) {
                change_app_status($application, $unix_datetime);

                // Make variables to put into the email confirmation.
                $app_email_address = $application['Email'];
                $email_date = date('m/d/Y g:i a', $unix_datetime);
                // Send email.
                //EmailWrap::sendEmail($app_email_address, "Confirmation", $email_date);

                return header("Location: $PATH" . "employer.php");
            
            } else {
                // Making the assumption they use the datepicker -- only way $unix_datetime could be invalid is if the user
                // enters an invalid time.
                $error_msg = "Invalid Time - please enter as HH:MM (AM/PM)";
                return array($application, $next_status, $error_msg);
            }

    	} else {
    		return array($application, $next_status, $error_msg);
    	}
    }

    list($application, $next_status, $error_msg) = main();

?>    
<div class="jumbotron">
    <div class="container">
    	<h1><?php echo $application['First_Name'] . ' ' . $application['Last_Name']; ?></h1>
    	<h2><?php echo $application['Position'] . " Application"; ?></h2>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12">    
            <h2>Employment History</h2>
            <pre><?php echo $application['Work_History']; ?></pre></br></br>
        </div>
    </div>   
    <div class="row">
        <div class="col-md-12">     
            <h2>Schedule for <?php echo str_replace('_', ' ', $next_status); ?></h2>
            <!-- show any errors if there are any -->
        	<form class="form-horizontal" method="POST" action="candidate.php">
                <?php echo "<div class='jm-error'><p>$error_msg</p></div>"; ?> 
                <div class="form-group">
                    <label for="date" class="sr-only">Select a date:</label> 
                        <div class="col-sm-4">
                            <input class="form-control" type='text' id='datepicker' name='date' placeholder='Pick a date'/>
                        </div>
                </div>        
                <div class="form-group">        
                    <label for="time" class="sr-only">Enter a time:</label> 
                        <div class="col-sm-4">
                            <input class="form-control" type='text' name='time' placeholder='Enter a time (HH:MM AM/PM)' />
                        </div>    
                    <!-- Set the id value so we can still retrieve $application if theres an incomplete
                        POST request -->
                </div>        
            		<input type='hidden' name='id' value=<?php echo $application['ID']; ?> /></br>
            		<button type='submit' name='update' class='btn btn-primary'>Confirm</button>
                </div>    
        	</form>
        </div>
    </div>	
<?php require 'footer.php'; ?>
</div>