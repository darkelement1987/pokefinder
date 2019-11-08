<?php
global $clock;
global $conn;
global $assetRepo;
global $mapkey;
$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
$dex = json_decode(file_get_contents('https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/description.json'), true);
$stats = json_decode(file_get_contents('https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/monsters.json'), true);

if(isset($_GET['pokemon'])){
$pokemon = $_GET['pokemon'];
if(empty($pokemon) || !is_numeric($pokemon) || $pokemon < 1 && $pokemon > 809 ){echo 'NO VALID/EMPTY ID';} else {

if(isset($_GET['form'])){$form=$_GET['form'];} else {$form='0';}
if(!empty($stats[$pokemon. '_' . $form]['form']['name'])){
$formname = $stats[$pokemon. '_' . $form]['form']['name'];} else {
    $formname = 'Unknown form';
}

$totalquery = $conn->query("select count(*) as total from pokemon");
$totalrow = $totalquery->fetch_assoc();
$totalquery->close();

if(!isset($_GET['form'])){
$monquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from pokemon where pokemon_id=" . $pokemon . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_seen, latitude, longitude from pokemon where pokemon_id=" . $pokemon . " order by last_seen desc limit 1");
$monrow = $monquery->fetch_assoc();
$monquery->close();
$monname = $mon_name[$pokemon]['name'];

$raidmonquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from raid where pokemon_id=" . $pokemon . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(last_scanned, '+00:00', @@global.time_zone)) as last_seen from raid where pokemon_id=" . $pokemon . " order by last_seen desc limit 1");
$raidmonrow = $raidmonquery->fetch_assoc();
$raidmonquery->close();
$raidmonname = $mon_name[$pokemon]['name'];
} else {
    $monquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from pokemon where pokemon_id=" . $pokemon . " and form=" . $form . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(last_modified, '+00:00', @@global.time_zone)) as last_seen, latitude, longitude from pokemon where pokemon_id=" . $pokemon . " and form=" . $form . " order by last_seen desc limit 1");
    $monrow = $monquery->fetch_assoc();
    $monquery->close();
    $monname = $mon_name[$pokemon]['name'] . ' (' . $formname . ')';
    
    $raidmonquery = $conn->query("select pokemon_id as pid, (select count(pokemon_id) from raid where pokemon_id=" . $pokemon . " and form=" . $form . ") as count, UNIX_TIMESTAMP(CONVERT_TZ(last_scanned, '+00:00', @@global.time_zone)) as last_seen from raid where pokemon_id=" . $pokemon . " order by last_seen desc limit 1");
    $raidmonrow = $raidmonquery->fetch_assoc();
    $raidmonquery->close();
    $raidmonname = $mon_name[$pokemon]['name'] . ' (' . $formname . ')';
    }

$monseen = $monrow['count'];
$raidmonseen = $raidmonrow['count'];
$total = $totalrow['total'];
$spawnrate = number_format((($monseen / $total)*100), 2, '.', '');
$lat = $monrow['latitude'];
$lon = $monrow['longitude'];
$last = $monrow['last_seen'];
$raidlast = $raidmonrow['last_seen'];

if($monseen>0){
$rarity = 'Common';

switch ($rarity) {
    case $spawnrate == 0.00:
    $rarity = 'New Spawn';
        break;
    case $spawnrate < 0.01:
    $rarity = 'Ultra Rare';
        break;
    case $spawnrate < 0.03:
    $rarity = 'Very Rare';
        break;
    case $spawnrate < 0.5:
    $rarity = 'Rare';
        break;
    case $spawnrate < 0.1:
    $rarity = 'Uncommon';
        break;
}
} else {
    $rarity = 'New Spawn';
}

if(!$monrow || empty($monrow)){$monseen='0';$last='-';} else {$last = date('l jS \of F Y ' . $clock, $last);}
if(!$raidmonrow || empty($raidmonrow)){$raidmonseen='0';$raidlast='-';} else {$raidlast = date('l jS \of F Y ' . $clock, $raidlast);}

$img = $assetRepo . 'pokemon_icon_' . str_pad($pokemon, 3, 0, STR_PAD_LEFT) . '_' . str_pad($form, 2, 0, STR_PAD_LEFT) . '.png';

// Perform check because of missing 719 - 807 in json
if($pokemon < 808){
if(empty($dex[$pokemon-1]['description'])){$desc='No description available';} else { $desc = $dex[$pokemon-1]['description']; }
if(empty($stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['name'])){$type1='Unknown type(s)';} else { $type1 = $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['emoji']. $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][0]['name']; }
if(empty($stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['name'])){$type2='';} else { $type2 = ' / ' . $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['emoji'] . $stats[str_pad($pokemon, 1, 0, STR_PAD_LEFT) . '_' . str_pad($form, 1, 0, STR_PAD_LEFT)]['types'][1]['name']; }
} else {
    if(empty($dex[$pokemon-90]['description'])){$desc='No description available';} else { $desc = $dex[$pokemon-90]['description']; }
if(empty($dex[$pokemon-90]['types'][0])){$type1='Unknown type(s)';} else { $type1 = ucfirst($dex[$pokemon-90]['types'][0]); }
if(empty($dex[$pokemon-90]['types'][1])){$type2='';} else { $type2 = ' / '. ucfirst($dex[$pokemon-90]['types'][1]); }
}

// Replace mon image with placeholder sprite if image is not in assets yet
$file_headers = @get_headers($img);
if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
    $img = 'https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/pokemon_icons/pokemon_icon_000.png';
}
?>

<div class="container">
<div class="jumbotron-fluid">
  <h4 class="display-6"><img src="<?= $img?>" class="dexmon"> <b><?= $monname?></b></h4>
  <p class="lead"><?= $desc?></p>
  <p class="lead"><b><?=$type1 . $type2?></b></p>
  <hr class="my-4">

<h4 class="display-6">Recently seen</h4>
<p class="lead">
Wild: <span class="badge badge-secondary"><?= $monseen?></span> times<br>
Raids: <span class="badge badge-secondary"><?= $raidmonseen?></span> times<br>
Recently wild: <?= $last?><br>
Recently in raids: <?=$raidlast?><br>
Rarity: <?= $rarity?><br>
Spawnrate: <?= $spawnrate?>%
<hr class="my-4">
<?php if(!empty($mapkey)){
    if($monseen>0){?>
<h4 class="display-6">Location last seen</h4>
<a href="https://maps.google.com/?q=<?=$lat. ',' .$lon?>"><img src="https://open.mapquestapi.com/staticmap/v5/map?locations=<?=$lat. ',' .$lon?>&key=<?=$mapkey?>&zoom=15&size=250,200" style="border: 1px solid grey;" class="minimap"></a><br>
<hr class="my-4">
    <?php }}?>
<i class="fas fa-arrow-circle-left"></i> <a href="#" onclick="goBack()"> Return</a>
</p>
</div>
</div>

<?php }} else {echo 'NO ID GIVEN';}?>
