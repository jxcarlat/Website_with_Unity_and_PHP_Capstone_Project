<?php
$message = filter_input(INPUT_POST, 'message');
$email = filter_input(INPUT_POST, 'email');
if($email == null || $email == false)
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>An error has occured. Please return back to Login page.</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
}
else
{
    if($message == null || $message == false)
    {
        $message = 'No reason provided for deletion';
        mail($email,'Reason for Denial',$message);
    }
    else
    {
        mail($email,'Reason for Denial',$message);
    }
    require_once("root_user.php");
}
?>