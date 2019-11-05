<?php
global $assetRepo;
global $conn;

$url = 'https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/description.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$json = json_decode($data); // decode the JSON feed

$query = 'SELECT DISTINCT pokemon_id FROM pokemon';
$result = $conn->query($query);
$seen = [];
if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
    $seen[] = $row->pokemon_id;
    }
}

?>
<div class="container">
<div class="row" id="pokedex">
<h3>Pokedex</h3>
<input class="form-control" id="myInput" type="text" placeholder="Search.."><br>
<?php foreach ($json as $entry) {
    $monid = str_pad($entry->pkdx_id, 1, 0, STR_PAD_LEFT);
    $monsprite = str_pad($entry->pkdx_id, 3, 0, STR_PAD_LEFT);
    $monname = $entry->name;
    ?>  
<div class="col">
<center>
<a href="index.php?page=seen&pokemon=<?= $monid?>">
<img <?php if (!in_array($monid, $seen)) {?>class="unseen"<?php } else {?>class="dexentry"<?php }?> src="https://assets.pokemon.com/assets/cms2/img/pokedex/detail/<?= $monsprite?>.png">
</a>
<br>
<?= $monname?></center>
</div>
<?php }?>    
  </div>
</div>