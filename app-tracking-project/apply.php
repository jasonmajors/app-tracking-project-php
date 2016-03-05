<?php
//TODO - FIX ERROR MSG CSS
    require 'header.inc.php';
    require __DIR__ . '/classes/FormValidate.class.php';
    use Jason\Database;


    // Key will be used as the html input name and Value will be used as the type.
    $DEMOGRAPHICS = array("Last_Name" => "text",
                    "First_Name" => "text",
                    "Email" => "text",
                    "Phone_Number" => "text",
                    "Address" => "text",
                    "City" => "text",
                    "State" => "text",
                    "Zipcode" => "text",
                    );


    // Keys will be printed out as category titles.
    $APPLICATION = array("Demographics" => $DEMOGRAPHICS, 

                        );


    function get_position()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $position = $_POST['position'];
            $position = urldecode($position);
            // Need this variable for the form incase submission fails. See line 94.
            $position_encoded = urlencode($position);    
        } else {
            // Set neccesary variables for the form.
            $position = $_GET['position'];
            // Don't know what i'm doing here... Will figure it out later.
            $position_test = new FormValidate(array(''));
            if ($position_test->positions($position)) {
                // Replace the +'s with spaces to display as h3 heading.
                $position = urldecode($position);
                // Replace the spaces with +'s to pass to the form.
                $position_encoded = urlencode($position);
            }
        }
        return array($position, $position_encoded);
    }

    function main()
    {
        list($position, $position_encoded) = get_position();
        $errors = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Fields must be identical to the keys in $DEMOGRAPHICS etc.
            $validation_fields = array("Last_Name" => "Open",
                                    "First_Name" => "Open",
                                    "Email" => "Open",
                                    "Phone_Number" => "Phone Number",
                                    "City" => "Open",
                                    "State" => "Open",
                                    "Zipcode" => "Zipcode",
                                    "Address" => "Open",
                                    "Work_History" => "Open",
    
                                    );

            $validation = new FormValidate($validation_fields);
            $completed_app = $validation->validate();
            $errors = $validation->get_errors();
            if ($completed_app) {
                $connect = new Database();
                $connect->submitApplication($completed_app, $position);
                $connect = null;
                $_SESSION['app_submitted'] = true;
                // Redirect.
                return header("Location: $PATH" . "success.php");
            } else {
                // App not completed, return the info needed to build the app.
                return array($position, $position_encoded, $errors);
            }
        } else {
            // No submission attempt yet.
            return array($position, $position_encoded, $errors);  
        }
    }


    list($position, $position_encoded, $errors) = main();
?>

<!-- Begin HTML application form -->

<div class="jumbotron">
    <div class="container">
        <h1><?php echo $position; ?></h1>
    </div>
</div>

<!-- Validation errors -->
<div class="container">
   
    <?php foreach($errors as $err) {
        echo "<p class='jm-error'>$err</p>";
    }
    ?>

</div>
<!-- End errors -->

<div class="container">
    <?php 
    // Build the form based on $APPLICATION.
    echo '<form class="form-inline" method="POST" action="apply.php" enctype="multipart/form-data">'.PHP_EOL;
        foreach($APPLICATION as $key => $value) {
            echo "<h3>$key</h3>".PHP_EOL;
            // $k will be the fields, $v will be their type.
            foreach ($value as $k => $v) {
                $decoded_field = str_replace('_', ' ', $k);
                $encoded_field = str_replace(' ', '_', $k);
                // Make a session variable to repopulate form fields.
                if ($_SERVER['REQUEST_METHOD'] === "POST") {
                    $_SESSION[$encoded_field] = $_POST[$encoded_field];
                    $formval = $_SESSION[$encoded_field];

                } else {
                    if (isset($_SESSION[$encoded_field])) {
                        $formval = $_SESSION[$encoded_field];
                    } else {
                        $formval = '';
                    }
                }
                // End repopulation.
                // Echo out the form item.
                
                echo "<div class='form-group'>".PHP_EOL;
                echo "<label for='$encoded_field' class='sr-only'>$decoded_field:</label>".PHP_EOL;
                //echo "<div class='col-sm-10'>".PHP_EOL;
                echo "<input class='form-control input-lg' type='$v' name='$encoded_field' value='$formval' placeholder='$decoded_field'>".PHP_EOL;
                //  echo "</div>".PHP_EOL;
                echo "</div>".PHP_EOL;
            }
        }
    ?>  
        <!-- End form building loop -->
        <!-- Create textarea -->

           <br><br><h3>Relevant Work History</h3>
                <textarea class ='form-control input-sm' name='Work_History' rows='10' maxlength='20000' placeholder="Copy/Paste resume here"></textarea>
                <!-- Removing file uploads...
                    <label>Upload a Resume </label> <input type="file" name="Resume" /></br> 
                -->
                <!-- Used to pass the position after a POST request -->
                <input type="hidden" name="position" value=<?php echo $position_encoded ?>></br>
                <br><br>
                <div class="col-md-2">
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                </div>
            </form>
            
            <!-- End form -->
</div>
<?php require 'footer.php'; ?>
