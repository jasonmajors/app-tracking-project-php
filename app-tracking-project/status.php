<?php
    // FILE NOT CURRENTLY IN USE.
    // Was created before putting all applicant statuses (pending, interiew, processing) all son employer.php
    require 'header.inc.php';
    require 'Database.class.php';
    require 'Authenticate.class.php';

    $auth = new Authenticate();
    $auth->login_required_redirect('/login.php');

    function delete_apps()
    {
        // Returns an array of the selected entries' applications' IDs.
        $deleted = $_POST['delete'];
        $modify_app = new Database();
        $modify_app->alterApplication($deleted, 'Deleted');
        $modify_app = null; 
    }

    function main()
    {
	    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {   
	        delete_apps();
	        $status = $_POST['status'];
	    } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
	    	$status = $_POST['status'];
	    } else {
	    	$status = $_GET['status'];   	
	    } 

		$db_connect = new Database();
	    $applications = $db_connect->getTable($table='applications', $field='App_Status', $condition=$status); 
	    $db_connect = null;  

	    return array($applications, $status);	
    }

    list($applications, $status) = main();

    if (!empty($applications)): 
?>
        <div id="deleted-applicants">
            <h2>Scheduled for <?php echo $status ?></h2></br>
            <form action="status.php" method="POST">
                <input type="hidden" name="status" value=<?php echo $status ?> />
                <table id="apps" class="display">
                    <thead>    
                        <tr>
                            <th>Date</th>
                            <th>Position</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Phone Number</th>
                            <th>E-Mail</th>
                            <th>Profile</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>    
                    <?php
                        // TODO: Update in php.ini
                        date_default_timezone_set('America/Los_Angeles');
                        foreach($applications as $app)
                        {
                            $position = $app['Position'];
                            $last_name = $app['Last_Name'];
                            $first_name = $app['First_Name'];
                            $phone_number = $app['Phone_Number'];
                            $email = $app['Email'];
                            $date = date('m/d/Y', $app['Date']);
                            $id = $app['ID'];
                            $app_status = $app['App_Status'];

                            // Populate the applicant table.
                            echo "<tr>
                                    <td>$date</td>
                                    <td>$position</td>
                                    <td>$last_name</td>
                                    <td>$first_name</td>
                                    <td>$phone_number</td>
                                    <td><a href='mailto:$email'>$email</a></td>
                                    <td><a href='/test/candidate.php?id=$id'>View Profile</a></td>
									<td><input type='checkbox' name='delete[]' value='$id' /></td>
                                </tr>";
                        }
                    ?>  
                    
                    </tbody>  
                </table></br> 
                </br><input type="submit" value="Delete Selected" /></br> 
            </form>
            </br><a href="/test/deleted.php">View Deleted</a>
        </div>              
<?php else: ?>
    <div id="applicants">
        <h2>No Scheduled Interviews</h2>
    </div>        
<?php endif; ?>


<script type="text/javascript" charset="utf-8" src="/test/js/DataTables/media/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8" src="/test/js/DataTables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="/test/js/datatables.js"></script>    
<?php require 'footer.php'; ?>   