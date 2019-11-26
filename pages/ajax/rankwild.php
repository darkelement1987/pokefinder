<?php
include '../../includes.php';

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

$query = 'SELECT pokemon_id, form, count, rank FROM ( SELECT pokemon_id, form, count, row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank FROM ( SELECT pokemon_id, form, COUNT(pokemon_id) AS COUNT FROM pokemon GROUP BY pokemon_id, form ) a ) b WHERE pokemon_id is not null ORDER BY rank asc, pokemon_id asc';
$result = $conn->query($query);

if($result && $result->num_rows >= 1 ) {
    $data=[];
    $seenquery = 'SELECT pokemon_id, form FROM pokemon WHERE disappear_time > utc_timestamp();';
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