<?php
    require 'header.inc.php';
    require __DIR__ . '/classes/Authenticate.class.php';
    require __DIR__ . '/classes/Database.class.php';
    // Array of all positions named $positions.
    include 'positions.php';


    $auth = new Authenticate();
    $auth->login_required_redirect('/login.php');

    $HEADERS = array("Position",
                "Last Name",
                "First Name",
                "Phone Number",
                "E-Mail",
                "Profile",
                "Delete",
                );

    function build_table($HEADERS, $status, $date_field='')
    {
        include "settings.php";
        date_default_timezone_set('America/Los_Angeles');

        $table = "<thead>".PHP_EOL;
        $table .= "<tr>".PHP_EOL;
        if ($date_field) {
            $table .= "<th>$date_field</th>".PHP_EOL;
        }
        foreach($HEADERS as $header) {
            $table .= "<th>$header</th>".PHP_EOL;
        }
        $table .= "</tr>".PHP_EOL;
        $table .= "</thead>".PHP_EOL;
        $table .= "<tbody>".PHP_EOL;

        foreach($status as $app) {
            $position = $app['Position'];
            $last_name = $app['Last_Name'];
            $first_name = $app['First_Name'];
            $phone_number = $app['Phone_Number'];
            $email = $app['Email'];
            $date = date('m/d/Y g:i a', $app['Date']);
            $id = $app['ID'];

            $table .= "<tr>".PHP_EOL;
            $table .= "<td>$date</td>".PHP_EOL;
            $table .= "<td>$position</td>".PHP_EOL;
            $table .= "<td>$last_name</td>".PHP_EOL;
            $table .= "<td>$first_name</td>".PHP_EOL;
            $table .= "<td>$phone_number</td>".PHP_EOL;
            $table .= "<td><a href='mailto:$email'>$email</a></td>".PHP_EOL;
            $table .= "<td><a href=$PATH" . "/candidate.php?id=$id>View Profile</a></td>".PHP_EOL;
            $table .= "<td><input type='checkbox' name='delete[]' value='$id' /></td>".PHP_EOL;
            $table .= "</tr>".PHP_EOL;
        }
        $table .= "</tbody>".PHP_EOL;

        return $table;
    }    

    function update_positions($positions)
    {
        $open_positions = array();

        foreach($positions as $position) {
            // Encode the string (get rid of spaces).
            $position_encoded = urlencode($position);

            if (isset($_POST[$position_encoded])) {
                if ($_POST[$position_encoded] === 'open') {   
                    // Add the decoded version (with spaces) into the array to be entered
                    // into the DB.
                    $open_positions[] = $position;
                }
            }
        }

        $db_connect = new Database();
        $db_connect->updatePositions($open_positions);
        $db_connect = null;
    }

    function delete_apps()
    {
        // Returns an array of the selected entries' applications' IDs.
        $deleted = $_POST['delete'];
        $modify_app = new Database();
        $modify_app->alterApplication($deleted, 'App_Status', 'Deleted');
        $modify_app = null; 
    }

    function main()
    {
        include "positions.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_positions'])) {   
            update_positions($positions);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {   
            delete_apps();
        }   

        $db_connect = new Database();
        //Uncomment to reset the applications table.
        //$db_connect->resetApplications();

        $pending = $db_connect->getTable($table='applications', $field='App_Status', $condition='Pending'); 
        $interview = $db_connect->getTable($table='applications', $field='App_Status', $condition='Interview');
        // TODO - in the process of changing work_permit over to the more general term - "Processing"
        $work_permit = $db_connect->getTable($table='applications', $field='App_Status', $condition='Processing');
        $db_connect = null;  

        return array($pending, $interview, $work_permit);      
    }

    list($pending, $interview, $work_permit) = main(); ?>


<!-- Begin Container -->
<div id="container">
    <!-- Begin Dashboard tables -->
    <div id="col-left">
    <!-- pending applicants -->
        <?php
            if (!empty($pending)): 
        ?>
            <div class="applicants">
                <h2>Pending Applicants</h2></br>
                <form action="employer.php" method="POST">
                    <table id="apps" class="display">
                        <?php echo build_table($HEADERS, $pending, "Submission Date"); ?> 
                    </table></br> 
                    </br><input type="submit" value="Delete Selected" /></br> 
                </form>
            </div>  
         
        <?php else: ?> <!-- Handle case where no pending applicants -->            
            <div class="applicants">
                <h2>No Pending Applications</h2>
            </div>            
        <?php endif; ?>
        <!-- scheduled for interviews -->
        <?php if (!empty($interview)): ?>
            <div class="applicants">
                <h2>Interviews</h2></br>
                <form action="employer.php" method="POST">
                    <table id="interviews" class="display">
                        <?php echo build_table($HEADERS, $interview, "Interview Time"); ?>
                    </table></br> 
                    </br><input type="submit" value="Delete Selected" /></br> 

                </form>
            </div>             
        <?php else: ?>
            <div class="applicants">
                <h2>No Scheduled Interviews</h2>
            </div>     
        <?php endif; ?>
        <!-- scheduled for processing -->
        <?php if (!empty($work_permit)): ?>
            <div class="applicants">
                <h2>Scheduled for Processing</h2></br>
                <form action="employer.php" method="POST">
                    <table id="work_permit" class="display">
                        <?php echo build_table($HEADERS, $work_permit, "Orientation Date"); ?>
                    </table></br>
                    </br><input type="submit" value="Delete Selected" /></br>
                </form>
            </div> 
            </br><a href=<?php echo $PATH . "/deleted.php"; ?>>View Deleted</a>   

        <?php else: ?>
            <div class="applicants">
                <h2>No One Scheduled for Processing</h2>
                </br><a href=<?php echo $PATH . "/deleted.php"; ?>>View Deleted</a> 
            </div>
        <?php endif; ?>           
    </div> <!-- End col-left -->

    <!-- Begin update positions bar -->
    <div id="col-right">
        <div id="update-bar">
            <h4>Update Positions</h4>
            <form action="employer.php" method="POST"> 
                <table class="table">
                    <thead>
                        <tr>
                            <th>Position</th>
                            <th>Open</th>
                            <th>Closed</th>
                        </tr>
                    <thead>
                    <tbody>
                    <!-- Build the table of positions -->
                    <?php
                        // Make an array of the open positions.
                        $db_connect = new Database();
                        $openings = $db_connect->build_column_array('positions', 'Position');
                        $db_connect = null;
                        foreach($positions as $position)
                        {
                            // Get rid of the spaces in position names.
                            $position_encoded = urlencode($position);
                            if (in_array($position, $openings))
                            {
                                $open = 'checked';
                                $closed = '';
                            } else {
                                $open = '';
                                $closed = 'checked';
                            }
                            echo "<tr>
                                    <td>$position</td>
                                    <td><input type='radio' name='$position_encoded' value='open' $open /></td>
                                    <td><input type='radio' name='$position_encoded' value='closed' $closed /></td>
                                </tr>";         
                        }
                    ?>  

                    </tbody>
                </table>
                    <input id="update-button" type="submit" name="update_positions" value="Update" />
            </form> 
        </div>
    </div> <!-- end col-left -->
    <!-- End update positions bar-->

    <?php require 'footer.php'; ?>
</div> <!-- End Container -->