<?php
function getMons()
{
    global $conn;
    global $assetRepo;
    global $monsters;

    $mons = [];
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

    if(!empty($_POST['gender'])){
        switch ($_POST['gender']) {
            case '1':
            $gender = ' IS NOT NULL';
                break;
            case '2':
            $gender = '=1';
                break;
            case '3':
            $gender = '=2';
                break;
            case '4':
            $gender = '=3';
                break;
                }
    } else { $gender = '';}

    if(!empty($_POST['monster'])){ $monsterid = 'pokemon_id IN (' . $_POST['monster'] . ') AND ';
    } elseif(!empty($_POST['generation'])){
        switch ($_POST['generation']) {
            case '1':
            $monsterid = 'pokemon_id BETWEEN 1 AND 151 AND ';
                break;
            case '2':
            $monsterid = 'pokemon_id BETWEEN 152 AND 251 AND ';
                break;
            case '3':
            $monsterid = 'pokemon_id BETWEEN 252 AND 386 AND ';
                break;
            case '4':
            $monsterid = 'pokemon_id BETWEEN 387 AND 493 AND ';
                break;
            case '5':
            $monsterid = 'pokemon_id BETWEEN 494 AND 649 AND ';
                break;
                }
    } else { $monsterid = '';}

    if(!empty($_POST['boosted'])){ switch ($_POST['boosted']){
        case'1':
        $boosted = ' AND weather_boosted_condition=1';
            break;
        case'2':
        $boosted = ' AND weather_boosted_condition=2';
            break;
        case'3':
        $boosted = ' AND weather_boosted_condition=3';
            break;
        case'4':
        $boosted = ' AND weather_boosted_condition=4';
            break;
        case'5':
        $boosted = ' AND weather_boosted_condition=5';
            break;
        case'6':
        $boosted = ' AND weather_boosted_condition=6';
            break;
        case'7':
        $boosted = ' AND weather_boosted_condition=7';
            break;
        case'8':
        $boosted = ' AND weather_boosted_condition BETWEEN 1 AND 7';
            break;
            }
            } else { $boosted = '';}

    $sql = "SELECT form, gender, catch_prob_1, catch_prob_2, catch_prob_3, cp_multiplier, individual_attack, individual_defense, individual_stamina, pokemon_id, cp, weather_boosted_condition, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, latitude, longitude  FROM pokemon WHERE " . $monsterid . "gender" . $gender . $boosted . " AND disappear_time > utc_timestamp();";

    $result = $conn->query($sql);

    // Check if mon available
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {

            // Pull Mon ID
            $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);

            // Check if mon has stats
            if ($row->individual_attack !== null && $row->individual_defense !== null &&  $row->individual_stamina !== null) {
                $row->iv = round((($row->individual_attack + $row->individual_defense + $row->individual_stamina) / 45) * 100, 2);
                $row->ivoutput = $row->iv . '%';
                $row->catch_prob_1 = '<img height=\'42\' width=\'42\' src=\'images/poke.png\'>' . round(($row->catch_prob_1) * 100,1) . '% / ';
                $row->catch_prob_2 = '<img height=\'42\' width=\'42\' src=\'images/great.png\'>' . round(($row->catch_prob_2) * 100,1) . '% / ';
                $row->catch_prob_3 = '<img height=\'42\' width=\'42\' src=\'images/ultra.png\'>' . round(($row->catch_prob_3) * 100,1) . '%';

            // If no stats show -
            } else {
                $row->iv = '-';
                $row->ivoutput = '-';
                $row->cp = '-';
                $row->catch_prob_1 = '-';
                $row->catch_prob_2 = '';
                $row->catch_prob_3 = '';
                $row->individual_attack = '-';
                $row->individual_defense = '-';
                $row->individual_stamina = '-';
            }

            // Detect Gender
            switch ($row->gender) {
                case '0':
                    $row->gender = 'Not Set';
                    break;
                case '1':
                    $row->gender = 'Male';
                    break;
                case '2':
                    $row->gender = 'Female';
                    break;
                case '3':
                    $row->gender = 'Genderless';
                    break;
                default:
                    $row->gender = '-';
                    break;
                    }

            // Detect Weatherboost
            switch ($row->weather_boosted_condition) {
                case'0':
                $row->weather_boosted_condition = '-';
                    break;
                case'1':
                $row->weather_boosted_condition = 'Clear';
                    break;
                case'2':
                $row->weather_boosted_condition = 'Rainy';
                    break;
                case'3':
                $row->weather_boosted_condition = 'Partly  Cloudy';
                    break;
                case'4':
                $row->weather_boosted_condition = 'Cloudy';
                    break;
                case'5':
                $row->weather_boosted_condition = 'Windy';
                    break;
                case'6':
                $row->weather_boosted_condition = 'Snow';
                    break;
                case'7':
                $row->weather_boosted_condition = 'Fog';
                    break;
                    }

            // Detect Level
            if (empty($row->cp_multiplier)){
                $row->level='-';
                } else {
                    if ($row->cp_multiplier < 0.73) {
                        $row->level = 58.35178527 * $row->cp_multiplier * $row->cp_multiplier - 2.838007664 * $row->cp_multiplier + 0.8539209906;
                        } elseif ($row->cp_multiplier > 0.73) {
                            $row->level = 171.0112688 * $row->cp_multiplier - 95.20425243;
                            }
                            $row->level = (round($row->level)*2)/2;
                            }

            $row->sprite = $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_' . str_pad($row->form, 2, 0, STR_PAD_LEFT) . '.png';
            $row->name = $mon_name[$row->pokemon_id]['name'];

            // Detect Form
            if (empty($row->form)){
                $row->form='-';
                } else {
                    $row->form = $mon_name[$row->pokemon_id]['forms'][$row->form]['formName'];
                    }

            $mons[] = $row;
        }
        return $mons;
    }
}

function getRocket()
{
    global $conn;
    global $assetRepo;

    $rocket = [];
    $rocket_name = json_decode(file_get_contents('https://raw.githubusercontent.com/whitewillem/PMSF/develop/static/data/grunttype.json'), true);
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $sql = "SELECT latitude as lat, longitude as lon, name, image, UNIX_TIMESTAMP(CONVERT_TZ(incident_expiration, '+00:00', @@global.time_zone)) as stop, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as scanned, UNIX_TIMESTAMP(CONVERT_TZ(incident_start, '+00:00', @@global.time_zone)) as start, incident_grunt_type as type FROM pokestop WHERE name IS NOT NULL and incident_expiration > utc_timestamp() ORDER BY scanned desc;";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $row->rgender = str_replace(" Grunt","",$rocket_name[$row->type]['grunt']);
            $row->rtype = $rocket_name[$row->type]['type'];
           if (empty($rocket_name[$row->type]['type'])) {
                $row->rtype = 'Unknown';
            }

            $row->secreward = $rocket_name[$row->type]['second_reward'];
            $row->onefirst = $rocket_name[$row->type]['encounters']['first'];
            $row->onesecond = $rocket_name[$row->type]['encounters']['second'];

                        if (is_array($row->onefirst) || is_array($row->onesecond) || is_array($row->onethird)) {
                            for($x = 0; $x <= 2; $x++){
                                if (!empty($row->onefirst[$x])) {
                                    $row->{"firstname" . $x} = $mon_name[ltrim((str_replace("_00","",$row->onefirst[$x])), '0')]['name'];
                                    $row->{"firstrow" . $x} = '<a href="index.php?page=seen&pokemon=' . ltrim((str_replace("_00","",$row->onefirst[$x])), '0') . '"><img src="' . $assetRepo . 'pokemon_icon_' . $row->onefirst[$x] . '.png" height="42" width="42"></a>';
                                    } else { 
                                    $row->{"firstrow" . $x} = '';
                                    $row->{"firstname" . $x} = '';
                                    };
                                    };
                                    for($x = 0; $x <= 2; $x++) {
                                        if (!empty($row->onesecond[$x])) {
                                            $row->{"secondname" . $x} = $mon_name[ltrim((str_replace("_00","",$row->onesecond[$x])), '0')]['name'];
                                            $row->{"secondrow" . $x} = '<a href="index.php?page=seen&pokemon=' . ltrim((str_replace("_00","",$row->onesecond[$x])), '0') . '"><img src="' . $assetRepo . 'pokemon_icon_' . $row->onesecond[$x] . '.png" height="42" width="42"></a>';
                                            } else {
                                                $row->{"secondrow" . $x} = '';                                                
                                                $row->{"secondname" . $x} = '';
                                                };
                                                };
                                                $rocket[] = $row;
                                                }
                                                }
                                                return $rocket;
                                                }
                                                }
                                                
function getQuest()
{
    global $conn;
    global $assetRepo;

    $quest = [];

    $sql = "SELECT pokestop.latitude as lat, pokestop.longitude as lon, pokestop.name, pokestop.image, trs_quest.quest_reward_type as type, trs_quest.quest_item_amount as amount, trs_quest.quest_task as task, trs_quest.quest_stardust as stardust, trs_quest.quest_pokemon_id as monid, trs_quest.quest_item_id as itemid from pokestop,trs_quest WHERE trs_quest.GUID = pokestop.pokestop_id;";
    $result = $conn->query($sql);
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $item_name = json_decode(file_get_contents('https://raw.githubusercontent.com/whitewillem/PMSF/master/static/data/items.json'), true);

    if ($result && $result->num_rows > 0){
        while ($row = $result->fetch_object()) {
            $row->text='';
            $row->monname='';
            $row->item='';
            switch ($row->type) {
                case '2':
                $row->item = $item_name[$row->itemid]['name'];
                $row->type = 'https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/images/quest/reward_' . $row->itemid . '_1.png';
                $row->text = $row->amount;
                break;
                case '3':
                $row->type = 'https://raw.githubusercontent.com/Map-A-Droid/MAD/master/madmin/static/quest/reward_stardust.png';
                $row->text = $row->stardust . ' Stardust';
                break;
                case '7':
                $row->type = $assetRepo . '/pokemon_icon_' . str_pad($row->monid, 3, 0, STR_PAD_LEFT) . '_00.png';
                $row->text = '<br><a href="index.php?page=seen&pokemon=' . $row->monid . '">' . $mon_name[$row->monid]['name'] . '</a>';
                break;
            }
            $quest[] = $row;
                }
                return $quest;
                }
                }

function getRaids()
{
    global $conn;
    global $assetRepo;
    global $clock;
    $raids = [];
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $raid_move_1 = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/moves.json'), true);
    $raid_move_2 = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/moves.json'), true);
    //$sql = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start, UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, a.pokemon_id, a.move_1, a.move_2, a.form, UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, b.name, b.url as image, c.team_id as team, a.level, a.cp, c.latitude, c.longitude, c.is_ex_raid_eligible FROM raid a INNER JOIN gymdetails b INNER JOIN gym c ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id AND a.end > UTC_TIMESTAMP() ORDER BY a.end ASC";
    $sql = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start, UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, a.pokemon_id, a.move_1, a.move_2, a.form, UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, b.name, b.url as image, c.team_id as team, a.level, a.cp, c.latitude, c.longitude, c.is_ex_raid_eligible FROM raid a INNER JOIN gymdetails b INNER JOIN gym c ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            if ($row->is_ex_raid_eligible == '1') {$ex=' <span class="badge badge-secondary">EX</span>';} else {$ex='';}
            
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
            
            $row->time_start = date($clock, $row->start);
            $row->time_end = date($clock, $row->end);
            $row->spawn = date($clock, $row->spawn);
            $row->raid_scan_time = date($clock, $row->last_scanned);
            $row->name = $row->name . $ex;
            // If no mon id is scanned then its considered an egg
            if (empty($row->pokemon_id)){
                $row->bossname = '<img class="egg" src="images/egg' . $row->level . '.png"> Egg not hatched';
                $row->formname = '';
                $row->move_1 = '-';
                $row->move_2 = '';
                $row->cp = '-';
                $row->id = '#???';
            // Else it's a raid :-)
            } else {
                $row->sprite = '<img src="' . $assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_' . str_pad($row->form, 2, 0, STR_PAD_LEFT) . '.png" height="42" width="42"/>';
                if($row->form != 0 || $row->form != '0'){$row->formname = $mon_name[$row->pokemon_id]['forms'][$row->form]['formName'];}                
                if(empty($row->formname)){$row->formlink = '';} else {$row->formlink = '&form=' . $row->form;}
                $row->bossname = '<a href="index.php?page=seen&pokemon=' . $row->pokemon_id . $row->formlink . '">' . $row->sprite . $mon_name[$row->pokemon_id]['name'] . '</a>';
                if(empty($row->move_1)){$row->move_1='Unknown &';} else {$row->move_1 = $raid_move_1[$row->move_1]['name'] . ' & ';}
                if(empty($row->move_2)){$row->move_2='Unknown';} else {$row->move_2 = $raid_move_2[$row->move_2]['name'];}
                $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);
            }
            $raids[] = $row;
        }
        return $raids;
    }
}
