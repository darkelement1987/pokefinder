<?php

include '../../includes.php';

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$query = "select pokemon.pokemon_id, pokemon.disappear_time, pokemon.latitude, pokemon.longitude, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified from pokemon, trs_stats_detect_raw WHERE pokemon.encounter_id = trs_stats_detect_raw.type_id AND trs_stats_detect_raw.is_shiny=1";
$result = $conn->query($query);
$jsonfile = new stdClass();
if($result && $result->num_rows >= 1 ) {
    $jsonfile->data = [];
    while ($row = $result->fetch_object() ) {
        $row->monname = '<img src=https://raw.githubusercontent.com/darkelement1987/shinyassets/master/96x96/pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00_shiny.png height=46 width=46> <a href=index.php?page=seen&pokemon=' . $row->pokemon_id . '>' . $mon_name[$row->pokemon_id]['name'] . '</a>';
        $row->last_modified = '<span hidden>' . $row->last_modified . '</span>' . date('l jS \of F Y ' . $clock, $row->last_modified) . '<br><a href="https://maps.google.com/?q=' . $row->latitude . ',' . $row->longitude . '">Location</a>';
        $jsonfile->data[]  =  $row;
        }
        print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        } else {
            echo '{"data":[]}';
            }
            ?>