<?php
global $conn;
global $clock;

$monname = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$query = "select pokemon_id, form, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as disappear_time, count(pokemon.pokemon_id) as count from pokemon group by pokemon_id, form having count <= 1 order by disappear_time desc";
$result = $conn->query($query);?>
<h3>New Pokedex entries</h3>
<div class="table-responsive-sm">
<table id="newDexTable" class="table table-striped table-bordered w-auto display compact">
  <thead>
    <tr>
      <th>Pokemon</th>
      <th>Form</th>
      <th>Last seen</th>
      <th>Disappeared</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    $data=[];
    while ($row = $result->fetch_object() ) {
        if($row->form > 0){
            $row->formname = formName($row->pokemon_id,$row->form);
        } else {
                $row->formname = '-';
            }
            $row->monname = '<img src="' . monPic('pokemon', $row->pokemon_id, $row->form) . '" height="46" width="46"> <a href="index.php?page=seen&pokemon=' . $row->pokemon_id . '&form=' . $row->form . '">' . $monname[$row->pokemon_id]['name'] . '</a>';
            $row->last_modified = '<span hidden>' . $row->last_modified . '</span>' . date('l jS \of F Y ' . $clock, $row->last_modified);
            $row->disappear_time = '<span hidden>' . $row->disappear_time . '</span>' . date('l jS \of F Y ' . $clock, $row->disappear_time);
            $jsonfile->data[]  =  $row;
    }
}
$save = json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('pages/ajax/newentries.json', $save)
?>
</tbody>
</table>
</div>