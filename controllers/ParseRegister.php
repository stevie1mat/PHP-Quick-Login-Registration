<?php 
    include_once'config/Database.php';
    include_once'config/Utilities.php';

    //process the form 
    if(isset($_POST['registerBtn'])){
        //Initialise the array to store messages from form
        $form_errors = array();

        //form validation
        $required_fields = array('email', 'username', 'password');

        //call the function to check empty field and merge the return data into array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

        //Fields that require checking for min length
        $fields_to_check_length = array('username' => 4, 'password' => 6);

        //Call the function to check min required length
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        //Email validation 
        $form_errors = array_merge($form_errors, check_email($_POST));

        //Collect form data and store in vars
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        if(checkDuplicateEntries("users", "username", $username, $db)){
            $result = flashMessage("Username is already taken! Please try another one");
        }
        else if(checkDuplicateEntries("users", "email", $email, $db)){
            $result = flashMessage("email address is already being used! Please try another one");
        }

        //check if error array is empty, if yes process and insert data
        else if(empty($form_errors)){
            //Hash the pwd
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            try{
                //Create SQL insert 
                $sqlInsert = "INSERT INTO users (username, email, password, join_date)
                                VALUES (:username, :email, :password, now())";

                //sanitise data
                $statement = $db->prepare($sqlInsert);

                //Add data into the db 
                $statement->execute(array(':username' => $username, ':email' => $email, ':password' => $hashed_password));

                //Check if one new row has been created 
                if($statement->rowCount() == 1){

                    //call sweetalert 
                    $result = " 
                            <script type=\"text/javascript\">
                                swal({
                                    title: \"Congratulations $username\",
                                    text: \"Registration has been successful\",
                                    type: \"success\",
                                    confirmButtonText: \"Thank you\"});
                                    
                            </script>";    
                }
            }
            catch(PDOException $ex) {
                $result = flashMessage("An error has occurred " .$ex->getMessage());
            }
        }
        else {
            if(count($form_errors) == 1){
                $result = flashMessage("There was 1 error in the form<br>");
            }
            else {
                $result = falshMessage("There were " .count($form_errors). " errors in the form<br>");
            }
        }
    }
   
?>