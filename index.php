<?php
require('database.php');
$sql = 'SELECT *
          FROM credentials
          ORDER BY email';
$statement = $db->prepare($sql);
$statement->execute();
$credentials = $statement->fetchAll();
//Free result
$statement->closeCursor();
if($credentials == null)
{
    $root_priming = true;
}
else
{
    $root_priming = false;
}
?>

<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>Login/Registration</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>
    
<!-- the body section -->
<body>
<div class="top-content">
	<div class="inner-bg">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2 text">
					<h1>Log In / Registration</h1>
					<div class="description">
						<p>Sign in without login credentials</p>
						<form action="public_user.php" method="post" id="index"><input type="submit" value="View" /></form></div>
				</div>
				<div id="Login" class="row">
					<div class="col-sm-5">
						<div class="form-box">
							<div class="form-top">
								<div class="form-top-left">
									<h3>Login</h3>
									<p>Enter email and password to log on:</p>
								</div>
								<div class="form-top-right"><i class="fa fa-key"></i></div>
							</div>
							<div class="form-bottom"><form role="form" action="check_user.php" method="post" class="login-form" id="index">
									<div class="form-group"><label class="sr-only" for="form-username">Email</label> <input type="text" name="email" placeholder="Email..." class="form-username form-control" id="form-username" /></div>
									<div class="form-group"><label class="sr-only" for="form-password">Password</label> <input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password" /></div>
									<br>
									<button type="submit" class="btn">Log in</button></form></div>
									<br>
									<a href="forgot_password.php" class="button-class">Forgot Password?</a>
						</div>
					</div>
					<div class="col-sm-1 middle-border"></div>
					<div class="col-sm-1"></div>
					<div class="col-sm-5">
						<div class="form-box">
							<div class="form-top">
								<div class="form-top-left">
									<h3>Registration</h3>
									<p>Fill in the form below to request access: <br><br>
								<?php if($root_priming){ ?>	    *Note for Root User: <br> Your password will be randomized and emailed to you for security reasons. More information will be provided at our next meeting.</p>
								<?php } ?>
								</div>
								<div class="form-top-right"><i class="fa fa-pencil"></i></div>
							</div>
							<div class="form-bottom"><form role="form" action="add_user.php" method="post" class="registration-form" id="index">
									<div class="form-group"><label class="sr-only" for="form-first-name">Email</label> <input type="text" name="email" placeholder="Email..." class="form-first-name form-control" id="form-first-name" /></div>
									<div class="form-group"><label class="sr-only" for="form-last-name">Password</label> <input type="password" name="password" placeholder="Password..." class="form-last-name form-control" id="form-last-name" /></div>
									<?php if(!$root_priming)
									{ ?>
									    <div class="form-group"><label class="sr-only" for="form-about-yourself">Access Level</label><select name="access_level" title="Please select Record Active or Not">
											<option value="" disabled="disabled" selected="selected">Select your option</option>
											<option value="Admin">Admin</option>
											<option value="Scheduler">Scheduler</option>
										</select></div>
									    <?php 
									} 
									else
									{   ?>
									    <div class="form-group"><label class="sr-only" for="form-about-yourself">Access Level</label><select name="access_level" title="Please select Record Active or Not">
											<option value="" disabled="disabled" selected="selected">Select your option</option>
											<option value="Root">Root</option>
										</select></div>
									    <?php 
									} ?>
									<br>
									<button type="submit" class="btn">Register</button></form></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>