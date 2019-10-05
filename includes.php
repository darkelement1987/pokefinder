<?php
include './config/config.php';
define('DIRECTORY', __Dir__);

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include './functions/functions.php';

switch ($clock) {
    case '24':
        $clock = 'H:i:s';
        break;
    case '12':
        $clock = 'g:i:s';
        break;
    default:
        $clock = 'g:i:s';
        break;
}

function index()
{
            require_once(DIRECTORY  . '/pages/pokefinder.php');
}