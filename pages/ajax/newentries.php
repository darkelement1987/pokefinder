<?php

include '../../config/config.php';

// Create connection
if(empty($port) && !$port){$port="3306";}
$conn = new mysqli($servername, $username, $password, $database, $port);
$conn->set_charset('utf8');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

include '../../functions/functions.php';

$monname = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$query = "select pokemon_id, form, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, count(pokemon.pokemon_id) as count from pokemon group by pokemon_id, form having count <= 1 order by disappear_time desc";
$result = $conn->query($query);

if($result && $result->num_rows >= 1 ) {
    $data=[];
    while ($row = $result->fetch_object() ) {
        if($row->form > 0){
            $row->formname = formName($row->pokemon_id,$row->form);
        } else {
                $row->formname = '-';
            }
            $row->monname = '<img src="images/pokemon/pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_' . str_pad($row->form, 2, 0, STR_PAD_LEFT) . '.png" height="46" width="46"> <a href="index.php?page=seen&pokemon=' . $row->pokemon_id . '&form=' . $row->form . '">' . $monname[$row->pokemon_id]['name'] . '</a>';
            $row->last_modified = '<span hidden>' . $row->last_modified . '</span>' . date('l jS \of F Y ' . $clock, $row->last_modified);
            $row->disappear_time = '<span hidden>' . $row->disappear_time . '</span>' . date('l jS \of F Y ' . $clock, $row->disappear_time);
            $jsonfile->data[]  =  $row;
    }
}
print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>