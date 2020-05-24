<?php
//Randomize root user password function
function randomizePassword()
{
    $password_length = 25;

    // Add a symbol to the password
    $symbols = '~!@#$%^&*()-_=+[]{};:,.<>?';
    $symbol_count = strlen($symbols);
    $index = random_int(0, $symbol_count - 1);
    $password = substr($symbols, $index, 5);
    $Upper = false;
    // Adds numbers until half of the remaining length has been reached
    while (strlen($password) < $password_length / 2)
    {
	    $password .= chr(random_int(48, 57));
    }
    // Alternate lowercase and uppercase letters to reach the specified length
    while (strlen($password) < $password_length) 
    {
        if(!$Upper)
        {
		    $password .= chr(random_int(97, 122));
            $Upper = true;
        }
        else
        {
    	    $password .= chr(random_int(65, 90));
            $Upper = false;
        }
    }

    $password = str_shuffle($password);
    return $password;
}
require('database.php');
$credential_email = filter_input(INPUT_POST, 'email');

if($credential_email == null || $credential_email == false)
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
    if(filter_var($credential_email, FILTER_VALIDATE_EMAIL))
    {
        $query = "SELECT email, password FROM credentials WHERE email = :email";
        $statement = $db->prepare($query);
        $sel = $statement->execute(array(':email' => $credential_email));
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        if($result['email'] == $credential_email)
        {
            $newPassword = randomizePassword();
            //Encrypt password with hash
            $hash = password_hash($newPassword, PASSWORD_DEFAULT);
            // Add updated credentials to the database
            $query = "UPDATE credentials SET password = '$hash' WHERE email = '$credential_email'";
            $statement = $db->prepare($query);
            $statement->execute();
            $statement->closeCursor();
            mail($credential_email,'New password',$newPassword);
            ?>
            <link rel="stylesheet" type="text/css" href="main.css" />
            <h1>An email containing your new password has been sent.</h1>
            <br>
            <a href="index.php" class="button-class">Back to Login</a>
            <?php
            
        }
        else
        {
            ?>
            <link rel="stylesheet" type="text/css" href="main.css" />
            <h1>Invalid email address. Please check field and try again.</h1>
            <br>
            <a href="index.php" class="button-class">Back to Login</a>
            <?php
        }
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>Invalid credentials. Please check fields and try again.</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}