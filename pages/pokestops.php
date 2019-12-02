<?php
global $conn;
global $clock;
global $gmaps;

?>

<?php if(!isset($_GET['pokestop'])){?>
<h3>Pokestops</h3>
<table id="stopsTable" class="table table-striped table-bordered w-auto table-fit">
  <thead class="thead-dark">
    <tr>
      <th>Pic</th>
      <th>Stop</th>
      <th>Team Rocket</th>
      <th>Quest</th>
      <th>Lured</th>
    </tr>
  </thead>
  <tbody>
</tbody>
</table>
<?php } else {
    $query = "SELECT * from pokestop WHERE pokestop_id='" . $_GET['pokestop'] . "'";
    $result = $conn->query($query);
    ?>

<h3>Pokestop:</h3>
<table class="table table-striped table-bordered w-auto table-fit">
  <tbody>
<tr>
<th><b>Column:</b></th>
<td><b>Value:</b></td>
</tr>

<?php if($result && $result->num_rows >= 1 ) {
    while ($row = $result->fetch_object() ) {
        $row->image = '<img src="' . $row->image . '" class="stopimg">';
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