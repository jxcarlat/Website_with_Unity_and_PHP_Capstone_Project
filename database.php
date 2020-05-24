<?php
$dsn = 'mysql:host=localhost;dbname=id12588448_login';
$username = 'id12588448_take_2';
$password = '8wD6#F&}2A1rELCc';

try 
{
    $db = new PDO($dsn, $username, $password);
} 
catch (PDOException $e)
{
    $error_message = $e->getMessage();
    include('database_error.php');
    exit();
} 
?>