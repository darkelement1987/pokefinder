<?php
global $conn;
global $clock;
global $gmaps;

$monname = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
?>

<?php if(!isset($_GET['gym'])){
    $query = "select gym.gym_id, gym.team_id as team, gym.is_ex_raid_eligible, gym.guard_pokemon_id, gym.slots_available, gymdetails.name, gymdetails.url, raid.level, UNIX_TIMESTAMP(CONVERT_TZ(raid.end, '+00:00', @@global.time_zone)) end from gym left join gymdetails on gym.gym_id = gymdetails.gym_id left join raid on gym.gym_id = raid.gym_id";
    $result = $conn->query($query);
?>
<h3>Gyms</h3>
<div class="table-responsive-sm">
<table id="gymsTable" class="table table-striped table-bordered w-auto display compact">
  <thead>
    <tr>
      <th>Pic</th>
      <th>Gym</th>
      <th>Raid/Egg</th>
      <th>Ex</th>
      <th>Team</th>
      <th>Guard Pokemon</th>
      <th>Free spots</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    $data=[];
    while ($row = $result->fetch_object() ) {
        switch ($row->team) {
            case '0':
                $row->team = 'Uncontested';
                break;
            case '1':
                $row->team = 'Mystic';
                break;
            case '2':
                $row->team = 'Valor';
                break;
            case '3':
                $row->team = 'Instinct';
                break;
            default:
                $row->team = 'Unknown';
                break;
        }
        if(date('Y-m-d ' . $clock, $row->end) > date("Y-m-d H:i:s")){$row->raid='Yes';} else {$row->raid='No';}
        if($row->url == NULL){$row->url='<img src="images/Unknown.png" height="46" width="46" class="' . $row->team . '">';} else {$row->url='<img src="' . $row->url . '" height="46" width="46" class="' . $row->team . '">';}
        if($row->name == NULL){$row->name='Unknown';} else {$row->name='<a href="index.php?page=gyms&gym=' . $row->gym_id . '">' . $row->name . '</a>';}
        if($row->is_ex_raid_eligible > 0){$row->is_ex_raid_eligible='Yes';} else {$row->is_ex_raid_eligible='No';}
        if($row->slots_available == 0){$row->slots_available = '-';}
        $row->guard_pokemon_id = '<img src="' . monPic('pokemon', $row->guard_pokemon_id,0) . '" height="46px" width="46px"><br><a href="index.php?page=seen&pokemon=' . $row->guard_pokemon_id . '">' . $monname[$row->guard_pokemon_id]['name'] . '</a>';
        $jsonfile->data[]  =  $row;
}
}
$save = json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('pages/ajax/gyms.json', $save)
?>
</tbody>
</table>
</div>
<?php } else {
    $query = "SELECT * FROM gym WHERE gym_id='" . $_GET['gym'] . "'";
    $result = $conn->query($query);
    ?>

<h3>Gym: <?= $_GET['gym']?></h3>
<div class="table-responsive-sm">

<table class="table table-striped table-bordered w-auto">
  <tbody>
<tr>
<th><b>Column:</b></th>
<td><b>Value:</b></td>
</tr>

<?php if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
        if(!empty($gmaps)){
        $address = json_decode(file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $row->latitude . ',' . $row->longitude . '&key=' . $gmaps), true);?>
<tr>
<th>address:</th>
<td><?=$address['results'][0]['formatted_address']?></td>
</tr>
        <? }
        foreach ($row as $col => $val) {

?>
<tr>
<th><?= $col?>:</th>
<td><?= $val?></td>
</tr>

<?php }}}?>
</tbody>
</table>
</div>
<?php }?>