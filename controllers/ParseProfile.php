<?php 
	include_once'config/Database.php';
	include_once'config/Utilities.php';

	if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && !isset($_POST['updateProfileBtn'])){
		if(isset($_GET['user_identity'])){
			$url_encode_id = $_GET['user_identity'];
			$decode_id = base64_decode($url_encode_id);
			$user_id_array = explode("encodeuserid", $decode_id);
			$id = $user_id_array[1];
		}
		else {
			$id = $_SESSION['id'];
		}

		$sqlQuery = "SELECT * FROM users WHERE id = :id";
		$statement = $db->prepare($sqlQuery);
		$statement->execute(array(':id' => $id));

		while($rs = $statement->fetch()){
			$username = $rs['username']; 
			$email = $rs['email'];
			$date_joined = strftime("%d %b %Y", strtotime($rs["join_date"]));
		}

		$encode_id = base64_encode("encodeuserid{$id}");
	}
	else if(isset($_POST['updateProfileBtn'])) {
		//Initilaise the array
		$form_errors = array();

		//form validation
		$required_fields = array('email', 'username');

		//check empty fields and merge into array
		$form_errors = array_merge($form_errors, check_empty_fields($required_fields));

		//fields to check length
		$fields_to_check_length = array('username' => 4);

		//check min length and put data in array
		$form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

		//Email validation
		$form_errors = array_merge($form_errors, check_email($_POST));

		//Collect data from form
		$email = $_POST['email'];
		$username = $_POST['username'];
		$hidden_id = $_POST['hidden_id'];

		if(empty($form_errors)){
			try{
				//create sql update
				$sqlUpdate = "UPDATE users SET username =:username, email =:email WHERE id =:id";

				//Sanitise 
				$statement = $db->prepare($sqlUpdate);

				//update 
				$statement->execute(array(':username' => $username, ':email' => $email, ':id' => $hidden_id));

				//Check if one new row was created
				if($statement->rowCount() == 1){
					$result = '
						<script type="text/javascript">
							swal("Updated!", "Profile update successful", "success");
						</script>
					';
				}
				else {
					$result = '
						<script type="text/javascript">
							swal("Nothing Happened", "You didnt make any changes");
						</script>
					';
				}
			}
			catch (PDOException $ex) {
				$result = flashMessage("An error occured in: " .$ex->getMessage());
			}
		}
		else {
			if(count($form_errors) == 1) {
				$result = flashMessage("There was 1 error in the form <br>");
			}
			else {
				$result = flashMessage("There were " .count($form_errors). " errors in the form <br>");
			}
		}
	}