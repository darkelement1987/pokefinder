<?php
global $assetRepo;
global $conn;

$mon_name = json_decode(file_get_contents('https://raw.githubusercontent.com/cecpk/OSM-Rocketmap/master/static/data/pokemon.json'), true);

if(isset($_GET['mode'])){
    if($_GET['mode']!='raid' && $_GET['mode']!='pokemon'){
        $mode=' (Wild & Raids)';
        $query = 'select pokemon_id, count(*) as count from pokemon WHERE pokemon_id is not null group by pokemon_id union select pokemon_id, count(*) as count from raid where pokemon_id is not null group by pokemon_id order by count desc';
        } else {
            if($_GET['mode']=='pokemon'){
                $mode=' (Wild Pokemon)';
                $query = 'select pokemon_id, count(*) as count from pokemon WHERE pokemon_id is not null group by pokemon_id order by count desc';
            }    
            if($_GET['mode']=='raid'){
                $mode=' (In Raids)';
                $query = 'select pokemon_id, count(*) as count from raid WHERE pokemon_id is not null group by pokemon_id order by count desc';
            }
        }
} else {
    $mode=' (Wild & Raids)';
    $query = 'select pokemon_id, count(*) as count from pokemon WHERE pokemon_id is not null group by pokemon_id union select pokemon_id, count(*) as count from raid where pokemon_id is not null group by pokemon_id order by count desc';
}

$result = $conn->query($query);?>
<h3>Pokemon Seen Ranks<?=$mode?></h3>
[<a href="index.php?page=rank">Overall</a>][<a href="index.php?page=rank&mode=pokemon">Wild</a>][<a href="index.php?page=rank&mode=raid">Raids</a>]
<div class="table-responsive-sm">
<table id="rankTable" class="table table-striped table-bordered w-auto">
  <thead>
    <tr>
      <th>Rank</th>
      <th>Pokemon</th>
      <th>Seen x</th>
    </tr>
  </thead>
  <tbody>

<?php if($result && $result->num_rows >= 1 ) {
    $val = 1;
    while ($row = $result->fetch_object() ) {
?>
<tr>
<td><?=$val++?></td>
<td><img src="<?=$assetRepo . 'pokemon_icon_' . str_pad($row->pokemon_id, 3, 0, STR_PAD_LEFT) . '_00.png'?>" height="32" width="32"> <a href="index.php?page=seen&pokemon=<?=$row->pokemon_id?>"><?=$mon_name[$row->pokemon_id]['name']?></a></td>
<td><?=$row->count?></td>
</tr>
<?php
    }
}
?>
</tbody>
</table>
</div>