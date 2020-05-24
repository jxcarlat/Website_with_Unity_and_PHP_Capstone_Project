<?php
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}
require('database.php');
if(isset($_SESSION['email']))
{
    $email = $_SESSION['email'];
    $query = "SELECT email
          FROM credentials
          WHERE access_level = 2 AND
          email = '$email'";
    $statement = $db->prepare($query);
    $statement->execute();
    $credential = $statement->fetch();
    $statement->closeCursor();
    if($email == $credential['email'])
    {
        $_SESSION['email'] = null;
        session_destroy();
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>Login Successful! Welcome Scheduler User</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>You are not Scheduler User. Return back to Login</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
else
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>You are not Scheduler User. Return back to Login</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
}
?>