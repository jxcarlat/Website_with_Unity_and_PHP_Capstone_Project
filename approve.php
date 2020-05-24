<?php
require('database.php');
$id = filter_input(INPUT_GET, 'id');
if($id == null || $id == false)
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>An error has occured. Please return back to Login.</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
}
else
{
    if(filter_var($id, FILTER_VALIDATE_EMAIL))
    {
        $query = "UPDATE credentials SET approval = 1 WHERE email = '$id'";
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->closeCursor();
        mail($id,'Account Approved','You may now login with your account to access ADTAA.');
        require_once("root_user.php");
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>An error has occured. Please return back to Login</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
?>