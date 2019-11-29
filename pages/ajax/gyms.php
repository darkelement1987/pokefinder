<?php
include '../../includes.php';

$monname = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

    $query = "select gym.gym_id, gym.team_id as team, gym.is_ex_raid_eligible, gym.guard_pokemon_id, gym.slots_available, gymdetails.name, gymdetails.url, raid.level, UNIX_TIMESTAMP(CONVERT_TZ(raid.end, '+00:00', @@global.time_zone)) end from gym left join gymdetails on gym.gym_id = gymdetails.gym_id left join raid on gym.gym_id = raid.gym_id";
    $result = $conn->query($query);
    $jsonfile = new stdClass();
    if($result && $result->num_rows >= 1 ) {
    $jsonfile->data = [];
    while ($row = $result->fetch_object() ) {
        switch ($row->team) {
            case '0':
                $row->team = 'Uncontested';
                break;
            case '1':
                $row->team = 'Mystic';
                break;
            case '2':
                $row->team = 'Valor';
                break;
            case '3':
                $row->team = 'Instinct';
                break;
            default:
                $row->team = 'Unknown';
                break;
        }
        if(date('Y-m-d ' . $clock, $row->end) > date("Y-m-d H:i:s")){$row->raid='Yes';} else {$row->raid='No';}
        if($row->url == NULL){$row->url='<img src=images/Unknown.png height=46 width=46 class=' . $row->team . '>';} else {$row->url='<img src=' . $row->url . ' height=46 width=46 class=' . $row->team . '>';}
        if($row->name == NULL){$row->name='Unknown';} else {$row->name='<a href=index.php?page=gyms&gym=' . $row->gym_id . '>' . $row->name . '</a>';}
        if($row->is_ex_raid_eligible > 0){$row->is_ex_raid_eligible='Yes';} else {$row->is_ex_raid_eligible='No';}
        if($row->slots_available == 0){$row->slots_available = '-';}
        $pic = monPicAjax('pokemon',$row->guard_pokemon_id,0);
        $row->guard_pokemon_id = '<img src=' . $pic . ' height=46px width=46px><br><a href=index.php?page=seen&pokemon=' . $row->guard_pokemon_id . '>' . $monname[$row->guard_pokemon_id]['name'] . '</a>';
        $jsonfile->data[]  =  $row;
        }
        print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo '{"data":[]}';
            }
            ?>