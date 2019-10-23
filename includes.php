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
    if (isset($_GET['page']) && !empty($_GET['page'])) {
        $page = trim($_GET['page'], './');
        if (file_exists(DIRECTORY . '/pages/' . $page . '.php')) {
            require_once(DIRECTORY  . '/pages/' . $page . '.php');
        } else {
            echo "<div class=\"card\" style=\"width: 10rem;\">\r\n <img class=\"card-img-top\" src=\"https://raw.githubusercontent.com/whitewillem/PogoAssets/resized/no_border/pokemon_icon_007_895.png\" alt=\"Card image cap\">\r\n <div class=\"card-body\">\r\n <p class=\"card-text\">Squirtle-squir. That page does not exist!</p></div></div>";
        }
    } else {
        require_once(DIRECTORY  . '/pages/pokefinder.php');
    }
}
