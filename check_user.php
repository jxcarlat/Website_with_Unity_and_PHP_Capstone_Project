<?php
// Get the user data
$credential_email = filter_input(INPUT_POST, 'email');
$credential_password = filter_input(INPUT_POST, 'password');

// Validate inputs
if ($credential_email == null || $credential_email == false || $credential_password == null
    || $credential_password == false)
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>Invalid credentials. Please check all fields and try again.</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
} 
else 
{
    require_once('database.php');
    $query = "SELECT approval, email, password, access_level FROM credentials WHERE email = :email";
    $statement = $db->prepare($query);
    $sel = $statement->execute(array(':email' => $credential_email)); //Associative array
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    $statement->closeCursor();
    $hash = $result['password'];
    if($credential_email == $result['email'] && (password_verify($credential_password, $hash)) && $result['approval'] == 0)
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>You have not been approved by root user. Please wait until approval.</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
    else if($credential_email == $result['email'] && !(password_verify($credential_password, $hash)))
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>Password is incorrect. Please try again</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
    else if ((password_verify($credential_password, $hash)) && $result['access_level'] == 0)
    {
        session_start();
        $_SESSION['email'] = $credential_email;
        require_once('root_user.php');
    }
    else if((password_verify($credential_password, $hash)) && $result['access_level'] == 1)
    {
        session_start();
        $_SESSION['email'] = $credential_email;
        require_once('admin_user.php');
    }
    else if((password_verify($credential_password, $hash)) && $result['access_level'] == 2)
    {
        session_start();
        $_SESSION['email'] = $credential_email;
        require_once('scheduler_user.php');
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>Failure. Please recheck email and password</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
?>