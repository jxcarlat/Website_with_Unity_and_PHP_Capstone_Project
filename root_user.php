<?php
if (session_status() == PHP_SESSION_NONE) 
{
    session_start();
}
require('database.php');
$query = 'SELECT email
          FROM credentials
          WHERE access_level = 0';
    $statement = $db->prepare($query);
    $statement->execute();
    $credential = $statement->fetch();
    $statement->closeCursor();
if(isset($_SESSION['email']) && $_SESSION['email'] == $credential['email'])
{
    $query = 'SELECT email, access_level
          FROM credentials
          WHERE access_level != 0 AND approval = 0
          ORDER BY email';
    $statement = $db->prepare($query);
    $statement->execute();
    $credentials = $statement->fetchAll();
    $statement->closeCursor();
    if($credentials == null)
    {
        $id = $_SESSION['email'];
        $_SESSION['email'] = null;
        session_destroy();
        ?>
        <link rel="stylesheet" type="text/css" href="main.css" />
        <h1>No more accounts need approval</h1>
        <br>
        <a href="admin_user.php?id=<?php echo $id; ?>" class="button-class">Admin Schedule</a></td>
        <br><br>
        <a href="index.php" class="button-class">Logout</a>
        <?php
    }
    else
    {
        ?>

        <!DOCTYPE html>
        <html>

        <!-- the head section -->
        <head>
            <title>root</title>
            <link rel="stylesheet" type="text/css" href="main.css" />
        </head>
    
        <!-- the body section -->
        <body>
        <main>
            <section>
                <table>
                    <tr>
                        <th>Email</th>
                        <th>Access Level</th>
                        <th class="right">Actions</th>
                    </tr>
            
                    <?php foreach ($credentials as $credential) : ?>
                    <tr>
                        <td><?php echo $credential['email']; ?></td>
                        <td><?php if($credential['access_level'] == 1)
                                {
                                    echo "Admin";
                                }
                                else
                                {
                                    echo "Scheduler";
                                }
                        ?></td>
                        <td class="right"><a href="approve.php?id=<?php echo $credential['email']; ?>" class="button-class">Approve</a>
                        <a href="deny.php?id=<?php echo $credential['email']; ?>" class="button-class">Deny</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </section>
        </main>
        <footer></footer>
        </body>
        </html>
        <?php
    }
}
else
{
    ?>
    <link rel="stylesheet" type="text/css" href="main.css" />
    <h1>You are not root user. Return back to Login.</h1>
    <br>
    <a href="index.php" class="button-class">Back to Login</a>
    <?php
}
?>