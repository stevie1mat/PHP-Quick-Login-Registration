<?php 

    define("DSN", "mysql:host=localhost;dbname=github");
    define("USERNAME", "root");
    define("PWD", "");


	

	try {
            
            //Create the connection 
            $db = new PDO(DSN, USERNAME, PWD);
            
            //set the PDO error mode to exception
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	}
	catch (PDOException $ex) {
            //Display error message
            echo "Connection failed ".$ex->getMessage();
        }




	