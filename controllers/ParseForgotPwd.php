<?php
	include_once'config/Database.php';
	include_once'config/Utilities.php';
    
    //Process the form when btn is pressed
    if(isset($_POST['pwdResetBtn'])) {
        //initialise the form
        $form_errors = array();
        
        //validate
        $required_fields = array('email', 'new_password', 'confirm_password');
        
        //Check empty fields
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
        
        //Set required length to fields that require it 
        $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);
        
        //Merge data into an array 
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));
        
        //Email validation
        $form_errors = array_merge($form_errors, check_email($_POST));
        
        //Check if there are NO errors, if clear proceed
        if(empty($form_errors)){
            //Collect data from the form
            $email = $_POST['email'];
            $new_pwd = $_POST['new_password'];
            $new_pwd_2 = $_POST['confirm_password'];
            
            //check if pwd's match
            if($new_pwd != $new_pwd_2){
                $result = flashMessage("Passwords do not match!");
            }
            else {
                try{
                    //create SQL select statement to verify if email address input exist in the database
                    $sqlQuery = "SELECT email FROM users WHERE email =:email";

                    //use PDO prepared to sanitize data
                    $statement = $db->prepare($sqlQuery);

                    //execute the query
                    $statement->execute(array(':email' => $email));

                    //check if record exist
                    if($statement->rowCount() == 1){
                        //hash the password
                        $hashed_password = password_hash($new_pwd, PASSWORD_DEFAULT);

                        //SQL statement to update password
                        $sqlUpdate = "UPDATE users SET password =:password WHERE email=:email";

                        //use PDO prepared to sanitize SQL statement
                        $statement = $db->prepare($sqlUpdate);

                        //execute the statement
                        $statement->execute(array(':password' => $hashed_password, ':email' => $email));
                        
                        //call sweetalert 
                    	$result = " 
                            <script type=\"text/javascript\">
                                swal({
                                    title: \"Updated\",
                                    text: \"Your password has been reset successfully\",
                                    type: \"success\",
                                    confirmButtonText: \"Thank you\"});
                                    
                            </script>";
                        
                    }
                    else {
                        //call sweetalert 
                    	$result = " 
                            <script type=\"text/javascript\">
                                swal({
                                    title: \"Error\",
                                    text: \"The email address provided does not match our records, please try again\",
                                    type: \"error\",
                                    confirmButtonText: \"Ok\"});
                                    
                            </script>";
                    }
                }
                catch (PDOException $ex) {
                    $result = flashMessage("An error occurred: " .$ex->getMessage());    
                }
            }
        }
        else {
            if(count($form_errors) == 1){
                $result = flashMessage("There was 1 error in the form<br>");
            }
            else {
                $result = flashMessage("There were " .count($form_errors). " errors in the form <br>");
            }
        }
    }
?>