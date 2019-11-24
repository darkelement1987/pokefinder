<?php
global $conn;

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

if(isset($_GET['mode'])){
    if($_GET['mode']!='raid' && $_GET['mode']!='pokemon' && $_GET['mode']!='0' && $_GET['mode']!='100'){
        $mode=' (Wild Pokemon)';
        $type='pokemon';
        $end='disappear_time';
        $query = 'SELECT pokemon_id, form, count, rank FROM ( SELECT pokemon_id, form, count, row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank FROM ( SELECT pokemon_id, form, COUNT(pokemon_id) AS COUNT FROM pokemon GROUP BY pokemon_id, form ) a ) b WHERE pokemon_id is not null ORDER BY rank asc, pokemon_id asc';
        } else {
            if($_GET['mode']=='pokemon'){
                $mode=' (Wild Pokemon)';
                $type='pokemon';
                $end='disappear_time';
                $query = 'SELECT pokemon_id, form, count, rank FROM ( SELECT pokemon_id, form, count, row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank FROM ( SELECT pokemon_id, form, COUNT(pokemon_id) AS COUNT FROM pokemon GROUP BY pokemon_id, form ) a ) b WHERE pokemon_id is not null ORDER BY rank asc, pokemon_id asc';
            }    
            if($_GET['mode']=='raid'){
                $mode=' (In Raids)';
                $type='raid';
                $end='end';
                $query = 'SELECT pokemon_id, form, count, rank FROM ( SELECT pokemon_id, form, count, row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank FROM ( SELECT pokemon_id, form, COUNT(pokemon_id) AS COUNT FROM raid GROUP BY pokemon_id, form ) a ) b WHERE pokemon_id is not null ORDER BY rank asc, pokemon_id asc';
            }
            if($_GET['mode']=='0'){
                $mode=' (0%)';
                $type='pokemon';
                $end='individual_attack=0 AND individual_defense=0 AND individual_stamina=0 AND disappear_time';
                $query = 'select row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank, pokemon_id, form, count(*) as count from pokemon where individual_attack=0 and individual_defense=0 and individual_stamina=0 group by pokemon_id, form order by count desc, pokemon_id asc';
            }  
            if($_GET['mode']=='100'){
                $mode=' (100%)';
                $type='pokemon';
                $end='individual_attack=15 AND individual_defense=15 AND individual_stamina=15 AND disappear_time';
                $query = 'select row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank, pokemon_id, form, count(*) as count from pokemon where individual_attack=15 and individual_defense=15 and individual_stamina=15 group by pokemon_id, form order by count desc, pokemon_id asc';
            }  
        }
} else {
    $mode=' (Wild Pokemon)';
    $type='pokemon';
    $end='disappear_time';
    $query = 'SELECT pokemon_id, form, count, rank FROM ( SELECT pokemon_id, form, count, row_number() OVER ( ORDER BY COUNT DESC, pokemon_id ASC) AS rank FROM ( SELECT pokemon_id, form, COUNT(pokemon_id) AS COUNT FROM pokemon GROUP BY pokemon_id, form ) a ) b WHERE pokemon_id is not null ORDER BY rank asc, pokemon_id asc';
}

$result = $conn->query($query);?>
<h3>Pokemon Seen Ranks<?=$mode?></h3>
<p>
[<a href="index.php?page=rank&mode=pokemon">Wild</a>][<a href="index.php?page=rank&mode=raid">Raids</a>][<a href="index.php?page=rank&mode=0">0%</a>][<a href="index.php?page=rank&mode=100">100%</a>]
</p>
<div class="table-responsive-sm">
<table id="rankTable" class="table table-striped table-bordered w-auto">
  <thead>
    <tr>
      <th>#</th>
      <th>Pokemon</th>
      <th>Form</th>
      <th>Seen x</th>
      <th>Seen now</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    $data=[];
    $seenquery = 'SELECT pokemon_id, form FROM ' . $type . ' WHERE ' . $end . ' > utc_timestamp();';
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
    $row->monname = '<img src="' . monPic('pokemon', $row->pokemon_id, $row->form) . '" height="46" width="46"> <a href="index.php?page=seen&pokemon=' . $row->pokemon_id . '&form=' . $row->form . '">' . $mon_name[$row->pokemon_id]['name'] . '</a>';
    $jsonfile->data[]  =  $row;
    }
}
$save = json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('pages/ajax/rank.json', $save)
?>
</tbody>
</table>
</div>