<?php
include '../../includes.php';

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

$query = 'select row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank, pokemon_id, form, count(*) as count from pokemon where individual_attack=15 and individual_defense=15 and individual_stamina=15 group by pokemon_id, form order by count desc, pokemon_id asc';
$result = $conn->query($query);

if($result && $result->num_rows >= 1 ) {
    $data=[];
    $seenquery = 'SELECT pokemon_id, form FROM pokemon WHERE individual_attack=15 AND individual_defense=15 AND individual_stamina=15 AND disappear_time > utc_timestamp();';
    $seenresult = $conn->query($seenquery);
    $seenmon = [];
    if($seenresult && $seenresult->num_rows >= 1 ) {
        while ($seenrow = $seenresult->fetch_object() ) {
        $seenmon[] = $seenrow->pokemon_id . '_' . $seenrow->form;
        }
    }
    while ($row = $result->fetch_object() ) {
        if (!in_array($row->pokemon_id . '_' . $row->form, $seenmon)) {$row->seen='No';} else {$row->seen='Yes';}
        
        if($row->form > 0){
            $row->formname=formName($row->pokemon_id,$row->form);
        } else {
        $row->formname = '-';
    }
    $row->monname = '<img src=' . monPicAjax('pokemon', $row->pokemon_id, $row->form) . ' height=46 width=46> <a href=index.php?page=seen&pokemon=' . $row->pokemon_id . '&form=' . $row->form . '>' . $mon_name[$row->pokemon_id]['name'] . '</a>';
    $jsonfile->data[]  =  $row;
    }
}
print json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>