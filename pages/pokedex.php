<?php
global $assetRepo;
global $conn;

$url = 'https://raw.githubusercontent.com/KartulUdus/Professor-Poracle/master/src/util/description.json'; // path to your JSON file
$data = file_get_contents($url); // put the contents of the file into a variable
$characters = json_decode($data); // decode the JSON feed
?>
<div class="container">
  <div class="row" id="pokedex">
  <h3>Pokedex</h3>
  <input class="form-control" id="myInput" type="text" placeholder="Search.."><br>
<?php foreach ($characters as $character) {
    $monquery = $conn->query("select count(*) as count from pokemon where pokemon_id=" . str_pad($character->pkdx_id, 1, 0, STR_PAD_LEFT));
    $monrow = $monquery->fetch_assoc();
    $monquery->close();
    $count=$monrow['count'];
    ?>  
    <div class="col">
<center>
<a href="index.php?page=seen&pokemon=<?php echo str_pad($character->pkdx_id, 1, 0, STR_PAD_LEFT)?>"><img <?php if ($count <1){?>class="unseen" <?php }?>src="https://assets.pokemon.com/assets/cms2/img/pokedex/detail/<?php echo str_pad($character->pkdx_id, 3, 0, STR_PAD_LEFT)?>.png" class="dexentry"></a>
<br>
<?php echo $character->name;?></center>
</div>
<?php }?>    
  </div>
  
  <script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#pokedex div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
</div>