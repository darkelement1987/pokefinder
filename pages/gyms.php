<?php
global $conn;
global $clock;
global $gmaps;

$monname = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);
?>

<?php if(!isset($_GET['gym'])){
?>
<h3>Gyms</h3>
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
</tbody>
</table>
<?php } else {
    $query = "SELECT * FROM gym WHERE gym_id='" . $_GET['gym'] . "'";
    $result = $conn->query($query);
    ?>

<h3>Gym:</h3>
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
<?php }?>