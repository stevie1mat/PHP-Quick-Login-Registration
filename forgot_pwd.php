<?php 
	$page_title = 'Forgotten password';
	include_once'includes/header.php'; 
    include_once'controllers/ParseForgotPwd.php';
?>
        <section id="forgotPwd-main">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2 col-sm-12 col-xs-12">
                        <h1>User Authentication system.</h1>
                        <h3>Reset Password</h3>
                        <br>

                        <?php if(isset($result)){
                            echo $result;
                        } ?>
                        <?php if(!empty($form_errors)){
                            echo show_errors($form_errors);
                        } ?>
                    </div>
                    <div class="col-md-6 col-md-offset-3 text-center">
                        <form class="form-horizontal" method="POST">
                        	<div class="form-group col-md-12">
                        		<div class="input-group">
                        			<span class="input-group-addon">
                        				<i class="fa fa-envelope"></i>
                        			</span>
                        			<input class="form-control" type="email" placeholder="Email address" name="email">
                        		</div>
                        	</div>
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input class="form-control" type="password" placeholder="New Password" name="new_password">
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-key"></i>
                                    </span>
                                    <input class="form-control" type="password" placeholder="Confirm New Password" name="confirm_password">
                                </div>
                            </div>
                            <div class="form-group col-md-12 button text-center">
                                <input name="pwdResetBtn" type="submit" class="btn btn-primary btn-lg" value="Rest Password"><br />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
<?php include_once'includes/footer.php'; ?>