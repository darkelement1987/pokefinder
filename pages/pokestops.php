<?php
global $conn;
global $clock;
global $gmaps;

?>

<?php if(!isset($_GET['pokestop'])){
    $query = "SELECT pokestop_id, guid, UNIX_TIMESTAMP(CONVERT_TZ(lure_expiration, '+00:00', @@global.time_zone)) as lure_expiration, UNIX_TIMESTAMP(CONVERT_TZ(incident_expiration, '+00:00', @@global.time_zone)) as incident_expiration, quest_type, image, name from pokestop left join trs_quest on pokestop.pokestop_id = trs_quest.guid";
    $result = $conn->query($query);
?>
<h3>Pokestops</h3>
<div class="table-responsive-sm">
<table id="stopsTable" class="table table-striped table-bordered w-auto display compact">
  <thead>
    <tr>
      <th>Pic</th>
      <th>Stop</th>
      <th>Team Rocket</th>
      <th>Quest</th>
      <th>Lured</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    $data=[];
    while ($row = $result->fetch_object() ) {
        if(date('Y-m-d ' . $clock, $row->incident_expiration) > date("Y-m-d H:i:s")){$row->rocket='Yes';} else {$row->rocket='No';}
        if($row->quest_type !== NULL){$row->quest='Yes';} else {$row->quest='No';}
        if($row->image == NULL){$row->image='<img src="images/Unknown.png" class="pic" height="46" width="46">';} else {$row->image='<img src="' . $row->image . '" class="pic" height="46" width="46">';}
        if($row->name == NULL){$row->name='Unknown';} else {$row->name='<a href="index.php?page=pokestops&pokestop=' . $row->pokestop_id . '">' . $row->name . '</a>';}
        if(!empty($row->lure_expiration)){$row->lure='Lured until ' . date($clock, $row->lure_expiration);} else {$row->lure='No';}
        $jsonfile->data[]  =  $row;
    }
}
$save = json_encode($jsonfile,  JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents('pages/ajax/pokestops.json', $save)
?>
</tbody>
</table>
</div>
<?php } else {
    $query = "SELECT * from pokestop WHERE pokestop_id='" . $_GET['pokestop'] . "'";
    $result = $conn->query($query);
    ?>

<h3>Pokestop:</h3>
<p><small><?= $_GET['pokestop']?></small></p>
<div class="table-responsive-sm">

<table class="table table-striped table-bordered w-auto">
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
</div>
<?php }?>