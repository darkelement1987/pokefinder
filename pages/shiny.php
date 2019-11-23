<?php
global $conn;
global $clock;

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$query = "select pokemon.pokemon_id, pokemon.disappear_time, pokemon.latitude, pokemon.longitude, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_modified from pokemon, trs_stats_detect_raw WHERE pokemon.encounter_id = trs_stats_detect_raw.type_id AND trs_stats_detect_raw.is_shiny=1";
$result = $conn->query($query);?>
<h3>Shinies found</h3>
<div class="table-responsive-sm">
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="display:inline-block;">
  <strong>Disclaimer:</strong> These Pokemon are not shiny guaranteed!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<table id="shinyTable" class="table table-striped table-bordered w-auto display compact">
  <thead>
    <tr>
      <th>Pokemon</th>
      <th>Last seen</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
?>
<tr>
<td class="align-middle"><img src="<?=monPic('shiny',$row->pokemon_id, 0)?>" height="46" width="46"> <a href="index.php?page=seen&pokemon=<?=$row->pokemon_id?>" height="32" width="32"> <?=$mon_name[$row->pokemon_id]['name']?></a></td>
<td class="align-middle"><span hidden><?=$row->last_modified?></span><?=date('l jS \of F Y ' . $clock, $row->last_modified)?><br><a href='https://maps.google.com/?q=<?= $row->latitude ?>,<?= $row->longitude ?>'>Location</a></td>
</tr>
<?php
    }
}
?>
</tbody>
</table>
</div>