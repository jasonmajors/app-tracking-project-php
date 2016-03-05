<?php
    require 'header.inc.php';
    use Jason\Database;
    // Array of all positions.
    include 'positions.php';
    require __DIR__ . '/classes/Authenticate.class.php';
    

    $auth = new Authenticate();
    $auth->login_required_redirect('/login.php');

    $modify_positions = new Database();
    $applications = $modify_positions->getTable('applications', 'App_Status', 'Deleted'); 

    if (!empty($applications)): 
?>
<div class='container'>
        <h2>Deleted Applications</h2></br>
        <form action="deleted.php" method="POST">
            <div class="table-responsive">
                <table id="apps" class="display">
                    <thead>    
                        <tr>
                            <th>Position</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Phone Number</th>
                            <th>E-Mail</th>
                            <th>Date</th>
                            <th>Profile</th>
                        </tr>
                    </thead>
                    <tbody>    
                    <?php
                        // TODO: Update in php.ini
                        date_default_timezone_set('America/Los_Angeles');
                        $media_path = '/opt/lampp/htdocs/test/media/';
                        
                        foreach($applications as $app)
                        {
                            $position = $app['Position'];
                            $last_name = $app['Last_Name'];
                            $first_name = $app['First_Name'];
                            $phone_number = $app['Phone_Number'];
                            $email = $app['Email'];
                            $date = date('m/d/Y', $app['Date']);
                            $id = $app['ID'];
                            
                            // Populate the applicant table.
                            echo "<tr>
                                    <td>$position</td>
                                    <td>$last_name</td>
                                    <td>$first_name</td>
                                    <td>$phone_number</td>
                                    <td><a href='mailto:$email'>$email</a></td>
                                    <td>$date</td>
                                    <td><a href='$PATH/candidate.php?id=$id'>View Profile</a></td>
                                </tr>";
                        }
                    ?>  
                    
                    </tbody>  
                </table></br>
            </div>
        </form>
    </div>           
    <?php endif; ?>

    <?php if(empty($applications)): ?>
    <div class="container">
        <h2>No Deleted Applications</h2>
    <?php endif; ?>
</div>
<?php require 'footer.php'; ?>