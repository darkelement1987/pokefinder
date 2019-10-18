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