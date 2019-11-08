<?php
global $assetRepo;
global $conn;
global $maxpokemon;

$url = 'https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/monsters.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$json = json_decode($data); // decode the JSON feed

$query = 'SELECT DISTINCT pokemon_id FROM pokemon UNION select distinct pokemon_id from raid';
$result = $conn->query($query);
$seen = [];
if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
    $seen[] = $row->pokemon_id;
    }
}

$formquery = 'SELECT DISTINCT form FROM pokemon UNION select distinct form from raid';
$formresult = $conn->query($formquery);
$formseen = [];
if($formresult && $formresult->num_rows >= 1 ) {
    while ($frow = $formresult->fetch_object() ) {
    $formseen[] = $frow->form;
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
    if(!empty($entry->form->name)){$monname = $entry->name . ' (' . $entry->form->name . ')'; $formid = '_' . $entry->form->id;} else {$monname = $entry->name; $formid = '_00';}
    $imgurl = 'images/pokemon/pokemon_icon_' . $monsprite . $formid . '.png';
    if(!file_exists($imgurl)){
        $imgurl='https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/pokemon_icons/pokemon_icon_000.png';
    }
    ?>  
<?php if($entry->form->name != 'Purified' && $entry->form->name != 'Shadow' && $entry->form->name != 'Normal'){?>
<div class="col" id="dexcol">
<center>
<?php if($entry->form->name == ''){?><a href="index.php?page=seen&pokemon=<?= $monid?>"><?php } else {?><a href="index.php?page=seen&pokemon=<?= $monid?>&form=<?= $entry->form->id?>"><?php }?>
<img <?php if (!in_array($monid, $seen) || !in_array($entry->form->id, $formseen)) {?>class="unseen"<?php } else {?>class="dexentry"<?php }?>src="<?=$imgurl?>">
</a>
<br>
<span id="entrytext"><?= $monname?></span></center>
</div>
<?php }}?>    
  </div>
</div>