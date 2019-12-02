<?php
if(isset($_GET['mode'])){
    if($_GET['mode']!='raid' && $_GET['mode']!='pokemon' && $_GET['mode']!='0' && $_GET['mode']!='100'){
        $mode=' (Wild Pokemon)';
        $id='rankTableWild';
        } else {
            if($_GET['mode']=='pokemon'){
                $mode=' (Wild Pokemon)';
                $id='rankTableWild';
                }    
            if($_GET['mode']=='raid'){
                $mode=' (In Raids)';
                $id='rankTableRaid';
                }
            if($_GET['mode']=='0'){
                $mode=' (0%)';
                $id='rankTable0';
                }  
            if($_GET['mode']=='100'){
                $mode=' (100%)';
                $id='rankTable100';
                }  
        }
} else {
    $mode=' (Wild Pokemon)';
    $id='rankTableWild';
}
?>
<h3>Pokemon Seen Ranks<?=$mode?></h3>
<p>
[<a href="index.php?page=rank&mode=pokemon">Wild</a>][<a href="index.php?page=rank&mode=raid">Raids</a>][<a href="index.php?page=rank&mode=0">0%</a>][<a href="index.php?page=rank&mode=100">100%</a>]
</p>
<table id="<?=$id?>" class="table table-striped table-bordered w-auto table-fit">
  <thead class="thead-dark">
    <tr>
      <th>#</th>
      <th>Pokemon</th>
      <th>Form</th>
      <th>Seen x</th>
      <th>Seen now</th>
    </tr>
  </thead>
  <tbody>
</tbody>
</table>