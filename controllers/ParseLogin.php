<?php 
    include_once'config/Database.php';
    include_once'config/Utilities.php';

    if(isset($_POST['loginBtn'])){
        //initialise the array 
        $form_errors = array();

        //Validate the form
        $required_fields = array('username', 'password');

        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

        if(empty($form_errors)){
            //Collect the form data
            $user = $_POST['username'];
            $password = $_POST['password'];
            isset($_POST['remember']) ? $remember = $_POST['remember'] : $remember = "";


            //check if user exists in the db 
            $sqlQuery = "SELECT * FROM users WHERE username = :username";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':username' => $user));

            //fetch data from DB & compare it with inputted data 
            while($row = $statement->fetch()){
                $id = $row['id'];
                $hashed_password = $row['password'];
                $username = $row['username'];

                //If pwd's match create the session 
                if(password_verify($password, $hashed_password)){
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;

                    $fingerprint = md5($_SERVER['REMOTE_ADDR']. $_SERVER['HTTP_USER_AGENT']);
                    $_SESSION['last_active'] = time();
                    $_SESSION['fingerprint'] = $fingerprint;

                    if($remember == "yes") {
                        rememberMe($id);
                    }

                    //call sweetalert 
                    echo $welcome = " 
                                    <script type=\"text/javascript\">
                                        swal({
                                            title: \"Welcome back $username\",
                                            text: \"Your being logged in\",
                                            type: \"success\",
                                            timer: 6000,
                                            showConfirmButton: false});
                                            
                                            setTimeout(function(){
                                                window.location.href='index.php';
                                            }, 5000);

                                    </script>";                    
                }
                else{
                    $result = flashMessage("Invalid username or password!");
                }
            }


        }
        else {
            if(count($form_errors) == 1) {
                $result = flashMessage("There was 1 error in the form<br>");
            }
            else {
                $result = flashMessage('There were ' .count($form_errors). ' errors in the form<br>'); 
            }
        }
    }
    
?>