<?php
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}
try{
    $get_vars = count($_GET);
    if($get_vars!=0) 
    {
        $id = filter_input(INPUT_GET, 'id');
        if(filter_var($id, FILTER_VALIDATE_EMAIL))
        {
            $_SESSION['email'] = $id;
        }
        else
        {
            ?>
            <link rel="stylesheet" type="text/css" href="main.css" />
            <h1>Fatal Error. Please return back to Login.</h1>
            <br>
            <a href="index.php" class="button-class">Back to Login</a>
            <?php
        }
    } 
}
catch(Exception $e) {
    
}

require('database.php');
if(isset($_SESSION['email']))
{
    $email = $_SESSION['email'];
    $query = "SELECT email
          FROM credentials
          WHERE (access_level = 1 AND email = '$email') OR (access_level = 0 AND
          email = '$email')";
    $statement = $db->prepare($query);
    $statement->execute();
    $credential = $statement->fetch();
    $statement->closeCursor();
    echo $credential['email'];
    if($email == $credential['email'])
    {
        $_SESSION['email'] = null;
        session_destroy();
        include_once('admin.html');
        ?>
        <br>
        <a href="index.php" class="button-class">Logout</a>
        <?php
        
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>You are not Admin user. Return to the Login page.</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
else
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>You are not Admin user. Return to the Login page.</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
}
?>