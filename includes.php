<?php
include './config/config.php';
define('DIRECTORY', __Dir__);

session_start(); // To keep track of the particular user

if ($usediscordauth) {
    if (!empty($_GET['discordlogout'])) {
        session_destroy();
        unset($_SESSION['discordloggedin']);
        unset($_SESSION['discordallowed']);
        unset($_SESSION['discordname']);
        unset($_SESSION['discordtoken']);
        unset($_SESSION['authdate']);

        header("Refresh: 0; url=$discordredirect");
      }

    if (empty($_SESSION['discordloggedin'])) { // If discordlogged is empty, get a check
        header('Location: ./pages/discordauth.php');
        exit;
    }

        // Check if the discord auth was today. This means you need to reauthenticate every new day
        $current = strtotime(date("Y-m-d"));
        if($_SESSION['authdate'] > $current) {
            header('Location: ./?discordlogout=true');
        }

    if (!$_SESSION['discordallowed']) {
        header('Location: ./pages/discordnotallowed.php');
        exit;
    }
}

// For composer
require ('./vendor/autoload.php');

// Create connection
if(empty($port) && !$port){$port="3306";}
$conn = new mysqli($servername, $username, $password, $database, $port);
$conn->set_charset('utf8');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

include './functions/functions.php';

switch ($seconds) {
    case true:
        $showseconds = ':s';
        break;
    case false:
        $showseconds = '';
        break;
    default:
        $showseconds = '';
        break;
}

switch ($clock) {
    case '24':
        $clock = 'H:i' . $showseconds;
        break;
    case '12':
        $clock = 'g:i' . $showseconds;
        break;
    default:
        $clock = 'g:i' . $showseconds;
        break;
}

// Count stuff for index

$mcount = $conn->query("select count(pokemon_id) as moncount from pokemon where disappear_time > utc_timestamp()");
$row = $mcount->fetch_assoc();
$moncount = $row['moncount'];
$mcount->close();

$scount = $conn->query("select count(*) as stopcount from pokestop where incident_expiration > utc_timestamp()");
$row = $scount->fetch_assoc();
$stopcount = $row['stopcount'];
$scount->close();

$rcount = $conn->query("select count(*) as raidcount from raid where raid.end > utc_timestamp()");
$row = $rcount->fetch_assoc();
$raidcount = $row['raidcount'];
$rcount->close();

$qcount = $conn->query("select count(*) as questcount from trs_quest");
$row = $qcount->fetch_assoc();
$questcount = $row['questcount'];
$qcount->close();

function index()
{
    if (isset($_GET['page']) || !empty($_GET['page'])) {
        $page = trim($_GET['page'], './');
        if (file_exists(DIRECTORY . '/pages/' . $page . '.php')) {
            require_once(DIRECTORY  . '/pages/' . $page . '.php');
        } else {
            echo "<div class=\"card\" style=\"width: 10rem;\">\r\n <img class=\"card-img-top\" src=\"images/pokemon/pokemon_icon_007_895.png\" alt=\"Card image cap\">\r\n <div class=\"card-body\">\r\n <p class=\"card-text\">Squirtle-squir. That page does not exist!</p></div></div>";
        }
    } else {
        require_once(DIRECTORY  . '/pages/main.php');
    }
}
