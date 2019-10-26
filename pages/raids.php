<?php
$raids = getRaids();
global $clock;
?>
<!-- START OF RAIDS TABLE -->
<div class="table-responsive-sm">
<table id="raid_table" class="table table-striped table-bordered w-auto">
<h3>Results:</h3>
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Gym Name</th>            
            <th>Raid Boss</th>
            <th>Moves</th>			
            <th>CP</th>
            <th>Level</th>
            <th>Egg Spawn</th>
            <th>Raid Start - End</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($raids)) {
            foreach ($raids as $row) {
                ?>
                <tr>
                    <td></td>
                    <td><a href='https://www.google.com/maps?q=<?= $row->latitude?>,<?= $row->longitude ?>'><?= $row->name ?></a></td>
                    <td><?= $row->bossname ?></td>
                    <td><?= $row->move_1 . $row->move_2 ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= str_repeat('★', $row->level) ?></td>    
                    <td><?= $row->spawn?></td>
                    <td> <?= $row->time_start ?> - <?= $row->time_end ?></td>
        </tr> <?php }
                        } else {
                            echo $raids;
        }?> </tbody>
</table>
</div>
<!-- END OF RAIDS TABLE -->
