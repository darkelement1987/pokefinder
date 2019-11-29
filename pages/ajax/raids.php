<?php

include '../../includes.php';
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $raid_move_1 = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/moves.json'), true);
    $raid_move_2 = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/moves.json'), true);
    $query = "SELECT UNIX_TIMESTAMP(CONVERT_TZ(a.start, '+00:00', @@global.time_zone)) as start, UNIX_TIMESTAMP(CONVERT_TZ(a.end, '+00:00', @@global.time_zone)) as end, UNIX_TIMESTAMP(CONVERT_TZ(a.spawn, '+00:00', @@global.time_zone)) as spawn, a.pokemon_id, a.move_1, a.move_2, a.form, UNIX_TIMESTAMP(CONVERT_TZ(a.last_scanned, '+00:00', @@global.time_zone)) as last_scanned, b.name, b.url as image, c.team_id as team, a.level, a.cp, c.latitude, c.longitude, c.is_ex_raid_eligible FROM raid a INNER JOIN gymdetails b INNER JOIN gym c ON a.gym_id = b.gym_id AND a.gym_id = c.gym_id AND a.end > UTC_TIMESTAMP() ORDER BY a.end ASC";
    $result = $conn->query($query);
    $jsonfile = new stdClass();
    if($result && $result->num_rows >= 1 ) {
    $jsonfile->data = [];
    while ($row = $result->fetch_object() ) {
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
            $row->fname = '';
            
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
                $row->sprite = '<img src="' . monPicAjax('pokemon', $row->pokemon_id, $row->form) . '" height="42" width="42"/>';              
                $row->formlink = '&form=' . $row->form;
                $row->bossname = '<a href="index.php?page=seen&pokemon=' . $row->pokemon_id . $row->formlink . '">' . $row->sprite . $mon_name[$row->pokemon_id]['name'] . '</a>';
                if($row->form > 0){$row->formname = formName($row->pokemon_id,$row->form);}
                if(empty($row->move_1)){$row->move_1='Unknown &';} else {$row->move_1 = $raid_move_1[$row->move_1]['name'] . ' & ';}
                if(empty($row->move_2)){$row->move_2='Unknown';} else {$row->move_2 = $raid_move_2[$row->move_2]['name'];}
                if(!empty($row->formname)){$row->fname = ' (' . $row->formname . ')';} else {$row->fname = '';}
                $row->id = '#' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT);
            }
                $row->hidden = '';
                $row->coords = '<a href=https://www.google.com/maps?q=' . $row->latitude . ',' . $row->longitude . '>' . $row->name . '</a>';
                $row->bossname = $row->bossname . $row->fname;
                $row->moves = $row->move_1 . $row->move_2;
                $row->level = '<span hidden>' . $row->level . '</span>' . str_repeat('<img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/premierball_sprite.png" height="28" width="28">', $row->level);
                $row->times = $row->time_start . '-' . $row->time_end;
                $row->rteam = '<span hidden>' . $row->team . '</span><img class=' . $row->team . ' height=42 width=42 src=' . $row->image . '>';
                $jsonfile->data[]  =  $row;
                }
                print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                } else {
                    echo '{"data":[]}';
                    }
?>