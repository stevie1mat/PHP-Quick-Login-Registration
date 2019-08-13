<?php 
	$page_title = "Edit Profile";
	include_once'includes/header.php';
	include_once'controllers/ParseProfile.php';

?>

<div class="container">
	<div class="col-lg-6 col-lg-offset-3 text-center">
		<h2>Edit Profile</h2><hr>
		<div>
			<?php if(isset($result)) echo $result; ?>
			<?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
		</div>
		<div class="clearfix"></div>

		<?php if(!isset($_SESSION['username'])): ?>
			<p class="lead">
				Your not authroised to view this page! <br>
				<a href="login.php">Login</a> Not yet a member? <a href="register.php">Register</a>
			</p>
		<?php else: ?>
			<form class="form-horizontal" method="POST" action="">
				<div class="form-group col-md-12">
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-envelope"></i>
						</span>
						<input class="form-control" type="text" name="email" value="<?php if(isset($email)) echo $email; ?>">
					</div>
				</div>
				<div class="form-group col-md-12">
					<div class="input-group">
						<span class="input-group-addon">
							<i class="fa fa-user"></i>
						</span>
						<input class="form-control" type="text" name="username" value="<?php if(isset($username)) echo $username; ?>">
					</div>
				</div>
				<div class="form-group col-md-12 text-center">
					<input type="hidden" name="hidden_id" value="<?php if(isset($id)) echo $id; ?>">
					<input name="updateProfileBtn" type="submit" class="btn btn-primary pull-right" value="Update profile">
				</div>
			</form>
	</div>
</div>

<?php endif; ?>

<?php include_once'includes/footer.php'; ?>