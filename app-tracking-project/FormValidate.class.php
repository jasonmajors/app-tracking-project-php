<?php
    class FormValidate 
    {
        private $fields;
        private $file_upload;
        private $errors;

        public function __construct(array $fields, $file_upload='')
            {
                $this->fields = $fields;
                // No more file uploads - fuck that shit
                $this->file_upload = $file_upload;
                $this->errors = array();
            }


        public function get_errors()
        {
            return $this->errors;
        }


        public function positions($page)
        {
            include "positions.php";
            if (in_array($page, $positions)) {
                return true;
            } else {
                return false;
            }
        }
        //IN PROGRESS -- NEED TO VALIDATE THE FORMAT OF EACH INPUT
        public function validate_type($value, $type)
        {
            if ( $type == 'Phone Number' ) {
                $phonenumber = preg_replace("/[^0-9,.]/", "", $value);
                if ( strlen($phonenumber) != 10 ) {
                    // Add appropriate error
                    $this->errors['Phone_Number'] = "Invalid phone number";
                    return false;
                }       
            }

            if ( $type == 'Zipcode' ) {
                $zipcode = $value;
                if ( strlen($zipcode) != 5 || !is_numeric($value) ) {
                    // Add appropriate error
                    $this->errors['Zipcode'] = "Invalid Zipcode";
                    return false;  
                }                
            }
            return true;
        }
        

        // Returns a new associative array of $field => $value pairs.
        public function validate()
        {
            $form = array();
            // Iterate through the array of form fields.
            foreach($this->fields as $field => $type) {
                if ( !empty($_POST[$field]) ) {
                    $value = $_POST[$field];
                    // Remove any HTML tags.
                    $value = strip_tags($value);
                    $valid = $this->validate_type($value, $type);
                    if ( $valid ) {
                        // If the field is filled with valid entries add it to the $form array/dict as ($field => $value).
                        $form[$field] = $value;
                    }

                } else {
                    // Error! Empty field!
                    /* Note this error wont be added if there's an improper value entered.
                       the error from validate_type() will populate instead
                    */
                    $fieldDisplay = str_replace('_', ' ', $field);
                    $this->errors[$field] = "$fieldDisplay is required.</br>";                    
                }
            }
            // Check if all the fields are accounted for.
            if (count($this->fields) === count($form)) {   
                return $form;
            }
        }

        public function get_form_data() 
        {
            if ($_SERVER['REQUEST_METHOD'] === "POST") {                                                                                                                                                               
                $completed_form = $this->validate();  

                return $completed_form;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
            }
        }

        // Helper function for validate().
        // Needs to be built out more to be more secure.
        // No longer in use. 
        /*   
        private function _handle_file_upload($lastname, $file_upload) 
        {
            if (!empty($_FILES[$file_upload]['name'])) {
                $filename = $_FILES[$file_upload]['name'];
                $filesize = $_FILES[$file_upload]['size'];
                $orig_path = $_FILES[$file_upload]['tmp_name'];
                $file_type = $_FILES[$file_upload]['type'];
                // Add the applicants last name to their filename.
                $new_name = $lastname . "_" . $filename;
                $path = "/opt/lampp/htdocs/test/media/";
                $new_path = $path . $new_name;

                $allowed_mimes = array('application/pdf', 
                                        'application/msword', 
                                        'text/plain', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                        );
                $max_size = 2500000;

                // If the file isn't one of the allowed MIME types or is too large, delete the file.
                if(!in_array($file_type, $allowed_mimes) || $filesize > $max_size) {
                    unlink($orig_path);
                    return false;
                } else {
                    // Valid file. Copy it to the new path (the media folder).
                    if(move_uploaded_file($orig_path, $new_path)) {
                        // Return the new file name (lastname_filename.filetype).
                        return $new_name;
                    }
                }
            } else {
                return false;
            }
        }
        */
    }
?>  
