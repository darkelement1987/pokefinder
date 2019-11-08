<?php
global $assetRepo;
global $conn;
global $maxpokemon;

$url = 'https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/monsters.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$json = json_decode($data); // decode the JSON feed

$query = 'SELECT distinct pokemon_id, form from pokemon union SELECT distinct pokemon_id, form from raid';
$result = $conn->query($query);
$seenmon = [];
if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
    $seenmon[] = $row->pokemon_id . '_' . $row->form;
    }
}

?>
<div class="container">
<div class="row" id="pokedex">
<h3>Pokedex</h3>
<input class="form-control" id="myInput" type="text" placeholder="Search.."><br><br>
<?php foreach ($json as $entry) {
    if(!empty($maxpokemon)){if($entry->id > $maxpokemon){break;}}
    $monid = str_pad($entry->id, 1, 0, STR_PAD_LEFT);
    $monsprite = str_pad($entry->id, 3, 0, STR_PAD_LEFT);
    if(!empty($entry->form->name)){$monname = $entry->name . '<br>(' . $entry->form->name . ')'; $formid = '_' . $entry->form->id;} else {$monname = $entry->name . '<br>(No form)'; $formid = '_00';}
    $imgurl = 'images/pokemon/pokemon_icon_' . $monsprite . $formid . '.png';
    if(!file_exists($imgurl)){
        $imgurl='https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/pokemon_icons/pokemon_icon_000.png';
    }
    ?>  
<?php if($entry->form->name != 'Purified' && $entry->form->name != 'Shadow'){?>
<div class="col" id="dexcol">
<center>
<?php if($entry->form->name == ''){?><a href="index.php?page=seen&pokemon=<?= $monid?>"><?php } else {?><a href="index.php?page=seen&pokemon=<?= $monid?>&form=<?= $entry->form->id?>"><?php }?>
<img <?php if (!in_array($entry->id . '_' . $entry->form->id, $seenmon)) {?>class="unseen"<?php } else {?>class="dexentry"<?php }?>src="<?=$imgurl?>">
</a>
<br>
<span id="entrytext"><?= $monname?></span></center>
</div>
<?php }}?>    
  </div>
</div>