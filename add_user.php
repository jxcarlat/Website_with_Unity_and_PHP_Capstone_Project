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
// Get the product data
$credential_email = filter_input(INPUT_POST, 'email');
$credential_password = filter_input(INPUT_POST, 'password');
$credential_access_level = filter_input(INPUT_POST, 'access_level');
$credential_approval = 0;

// Validate inputs
if ($credential_email == null || $credential_email == false || $credential_password == null
    || $credential_password == false || $credential_access_level == null || $credential_access_level == false) 
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>'Invalid credential data. Check all fields and try again.'</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
} 
else 
{
    if(filter_var($credential_email, FILTER_VALIDATE_EMAIL))
    {
        require_once('database.php');
        if($credential_access_level === 'Admin')
        {
            $credential_access_level = 1;
        }
        else if($credential_access_level === 'Scheduler')
        {
            $credential_access_level = 2;
        }
        else
        {
            $credential_access_level = 0;
            $credential_password = randomizePassword();
            mail($credential_email,'Root password',$credential_password);
            $credential_approval = 1;
        }
        $query = "SELECT email FROM credentials WHERE email = :email";
        $statement = $db->prepare($query);
        $sel = $statement->execute(array(':email' => $credential_email));
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $statement->closeCursor();
        if ($credential_email === $result['email'])
        {
            ?>
            <link rel="stylesheet" type="text/css" href="main.css" />
            <h1>Email address already exists. Please register with a new email</h1>
            <br>
            <a href="index.php" class="button-class">Back to Login</a>
            <?php
        }
        else
        {
            //Encrypt password with hash
            $hash = password_hash($credential_password, PASSWORD_DEFAULT);
            // Add the product to the database
            $query = 'INSERT INTO credentials
            (email, password, access_level, approval)
            VALUES
            (:credential_email, :credential_password, :credential_access_level, :credential_approval)';
            $statement = $db->prepare($query);
            $statement->bindValue(':credential_email', $credential_email);
            $statement->bindValue(':credential_password', $hash);
            $statement->bindValue(':credential_access_level', $credential_access_level);
            $statement->bindValue(':credential_approval', $credential_approval);
            $statement->execute();
            $statement->closeCursor();
            if($credential_access_level != 0)
            {
                mail($credential_email,'Registration successful', 'Please await approval from root user before logging into your account.');
                ?>
                <link rel="stylesheet" type="text/css" href="main.css" />
                <h1>Registration success! Please see email for further instructions</h1>
                <br>
                <a href="index.php" class="button-class">Back to Login</a>
                <?php
            }
            else
            {
                ?>
                <link rel="stylesheet" type="text/css" href="main.css" />
                <h1>'Invalid credential data. Check all fields and try again.'</h1>
                <br>
                <a href="index.php" class="button-class">Back to Login</a>
                <?php
            }
        }
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>'Invalid credential data. Check all fields and try again.'</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
?>