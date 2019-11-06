<?php
$raids = getRaids();
global $clock;
?>
<!-- START OF RAIDS TABLE -->
    <h3 class="display-6">Raids</h3>
<div class="table-responsive-sm">
<table id="raid_table" class="table table-striped table-bordered w-auto">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Pic</th>
            <th>Gym</th>            
            <th>Boss</th>
            <th>Moves</th>			
            <th>CP</th>
            <th>Level</th>
            <th>Egg Spawn</th>
            <th>Raid Time</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($raids)) {
            foreach ($raids as $row) {
                ?>
                <tr>
                    <td></td>
                    <td><span hidden><?= $row->team?></span><img class='<?= $row->team?>' height='42' width='42' src='<?= $row->image ?>' title='Gym control: <?= $row->team?>'/></td>
                    <td><a href='https://www.google.com/maps?q=<?= $row->latitude?>,<?= $row->longitude ?>'><?= $row->name ?></a></td>
                    <td><?= $row->bossname;if(!empty($row->formname)){echo ' (' . $row->formname . ')';}?></td>
                    <td><?= $row->move_1 . $row->move_2 ?></td>
                    <td><?= $row->cp ?></td>
                    <td><span hidden><?= $row->level ?></span><?= str_repeat('<img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/premierball_sprite.png" height="28" width="28">', $row->level) ?></td>    
                    <td><?= $row->spawn?></td>
                    <td> <?= $row->time_start ?> - <?= $row->time_end ?></td>
        </tr> <?php }
                        } else {
                            echo $raids;
        }?> </tbody>
</table>
</div>
<!-- END OF RAIDS TABLE -->