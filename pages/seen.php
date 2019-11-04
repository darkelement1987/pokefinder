<?php
global $clock;
global $conn;
global $assetRepo;
$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$dex = json_decode(file_get_contents('https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/description.json'), true);
$stats = json_decode(file_get_contents('https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/monsters.json'), true);

if(isset($_GET['pokemon'])){
$pokemon = $_GET['pokemon'];
if(empty($pokemon) || !is_numeric($pokemon) || $pokemon < 1 && $pokemon > 809 ){echo 'NO VALID/EMPTY ID';} else {

if(isset($_GET['form'])){$form=$_GET['form'];} else {$form='0';}

$raidquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from raid where pokemon_id=" . $pokemon . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(end, '+00:00', @@global.time_zone)) as last_seen from raid where pokemon_id=" . $pokemon . " order by last_seen desc limit 1");
$raidrow = $raidquery->fetch_assoc();
$raidquery->close();

$monquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from pokemon where pokemon_id=" . $pokemon . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(disappear_time, '+00:00', @@global.time_zone)) as last_seen, latitude, longitude from pokemon where pokemon_id=" . $pokemon . " order by last_seen desc limit 1");
$monrow = $monquery->fetch_assoc();
$monquery->close();

if(!$raidrow || empty($raidrow)){$raidrow['count']='0';$raidrow['last_seen']='never';} else {$raidrow['last_seen'] = date('l jS \of F Y ' . $clock, $raidrow['last_seen']);}
if(!$monrow || empty($monrow)){$monrow['count']='0';$monrow['last_seen']='never';} else {$monrow['last_seen'] = date('l jS \of F Y ' . $clock, $monrow['last_seen']);}

$img = $assetRepo . 'pokemon_icon_' . str_pad($pokemon, 3, 0, STR_PAD_LEFT) . '_' . str_pad($form, 2, 0, STR_PAD_LEFT) . '.png';

if($pokemon < 808){
if(empty($dex[$pokemon-1]['description'])){$desc='No description available';} else { $desc = $dex[$pokemon-1]['description']; }
if(empty($stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['name'])){$type1='?';} else { $type1 = $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['emoji']. $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['name']; }
if(empty($stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['name'])){$type2='';} else { $type2 = ' / ' . $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['emoji'] . $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['name']; }
} else {
    if(empty($dex[$pokemon-90]['description'])){$desc='No description available';} else { $desc = $dex[$pokemon-90]['description']; }
if(empty($dex[$pokemon-90]['types'][0])){$type1='?';} else { $type1 = ucfirst($dex[$pokemon-90]['types'][0]); }
if(empty($dex[$pokemon-90]['types'][1])){$type2='';} else { $type2 = ' / '. ucfirst($dex[$pokemon-90]['types'][1]); }
}

$file_headers = @get_headers($img);
if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $img = 'https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/pokemon_icons/pokemon_icon_000.png';
}
?>
<div class="container">
<div class="jumbotron-fluid">
  <h4 class="display-6"><img src="<?= $img?>" class="dexmon"> <b><?= $mon_name[$pokemon]['name']?></b></h4>
  <p class="lead"><?= $desc?></p>
  <p class="lead"><b><?=$type1 . $type2?></b></p>
  <hr class="my-4">

<h4 class="display-6">Recently seen</h4>
<p class="lead">Spawned <span class="badge badge-secondary"><?= $monrow['count']?></span> times<br>
Last time: <?= $monrow['last_seen']?>
<hr class="my-4">
<i class="fas fa-arrow-circle-left"></i> <a href="#" onclick="goBack()"> Return to Pokedex</a>
</p>
</div>
</div>

<?php }} else {echo 'NO ID GIVEN';}?>