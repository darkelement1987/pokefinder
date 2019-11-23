<?php
global $conn;
global $maxpokemon;

$url = 'https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/monsters.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$json = json_decode($data); // decode the JSON feed

$query = 'SELECT distinct pokemon_id from pokemon union SELECT distinct pokemon_id from raid';
$result = $conn->query($query);
$seenmon = [];
if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
    $seenmon[] = $row->pokemon_id;
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
    if(!empty($entry->form->name)){$monname = $entry->name . '<br>(' . $entry->form->name . ')'; $formid = '_' . $entry->form->id;} else {$monname = $entry->name; $formid = '_00';}
    $imgurl = monPic('pokemon',$monid,$entry->form->id);
    ?>  
<?php if($entry->form->name ==''){?>
<div class="col" id="dexcol">
<center>
<?php if($entry->form->name == ''){?><a href="index.php?page=seen&pokemon=<?= $monid?>"><?php } else {?><a href="index.php?page=seen&pokemon=<?= $monid?>&form=<?= $entry->form->id?>"><?php }?>
<img <?php if (!in_array($entry->id, $seenmon)) {?>class="unseen"<?php } else {?>class="dexentry"<?php }?>src="<?=$imgurl?>">
</a>
<br>
<span id="entrytext"><?= $monname?></span></center>
</div>
<?php }}?>    
  </div>
</div>