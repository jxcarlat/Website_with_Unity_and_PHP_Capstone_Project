<?php
require('database.php');
$id = filter_input(INPUT_GET, 'id');
if($id == null || $id == false)
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
    if(filter_var($id, FILTER_VALIDATE_EMAIL))
    {
        $query = "DELETE FROM credentials WHERE email = '$id'";
        $statement = $db->prepare($query);
        $statement->execute();
        $statement->closeCursor();
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <br>
        <form role="form" action="deny_message.php" method="post" id="deny">
            <h3>Reason for Denial</h3>
            <div class="form-group"><label class="sr-only" for="form-username">Email</label> <input type="text" name="email" value=<?php echo $id ?> class="form-username form-control" id="form-username" readonly/></div>
            <textarea id="deny_message" name="message"  placeholder="Please enter reason for denial here. This message will be sent as an email to the user explaining why they were denied access..." rows="10" cols="30"></textarea>
            <button type="submit" class="btn">Submit</button>
        </form>
        <?php
    }
    else
    {
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>An error has occured. Please return back to Login page.</h1>
        <br>
        <a href="index.php" class="button-class">Back to Login</a>
        <?php
    }
}
?>