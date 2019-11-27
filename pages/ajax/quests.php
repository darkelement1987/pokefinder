<?php

include '../../includes.php';

    $query = "SELECT pokestop.latitude as lat, pokestop.longitude as lon, pokestop.name, pokestop.image, trs_quest.quest_reward_type as type, trs_quest.quest_item_amount as amount, trs_quest.quest_task as task, trs_quest.quest_stardust as stardust, trs_quest.quest_pokemon_id as monid, trs_quest.quest_item_id as itemid from pokestop,trs_quest WHERE trs_quest.GUID = pokestop.pokestop_id;";
    $result = $conn->query($query);
    $mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
    $item_name = json_decode(file_get_contents('https://raw.githubusercontent.com/whitewillem/PMSF/master/static/data/items.json'), true);

    if($result && $result->num_rows >= 1 ) {
    $data=[];
    while ($row = $result->fetch_object() ) {
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
                $row->type = monPicAjax('pokemon',$row->monid,0);
                $row->text = '<br><a href="index.php?page=seen&pokemon=' . $row->monid . '">' . $mon_name[$row->monid]['name'] . '</a>';
                break;
            }
        $row->image='<img class=pic height=42 width=42 src=' . $row->image. '>';
        $row->coords = '<a href=https://maps.google.com/?q=' . $row->lat . ','. $row->lon . '>' . $row->name;
        $row->type = '<img height=42 width=42 src=' . $row->type . '>' . $row->text . ' ' . $row->item;
        $jsonfile->data[]  =  $row;
    }
}
print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);?>