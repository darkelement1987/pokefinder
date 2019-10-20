<?php
$mons = getMons();
global $clock;
?>
<!-- START OF RESULT TABLE -->
<table <?php if($_POST){?>id="mon_table"<?php }?> class="table table-striped table-bordered table-sm" style="width:100%" <?php if(!$_POST){?>hidden<?php }?>>
<?php if($_POST){?><h3>Results:</h3><?php }?>
    <thead class="thead-dark">
        <tr>
            <th style="display:none";>ID:</th>
            <th>Pokemon:</th>
            <th>IV:</th>
            <th>CP:</th>
            <th>Boosted by:</th>
            <th>Level:</th>
            <th>Gender:</th>
            <th>Form:</th>
            <th>Attack</th>
            <th>Defense:</th>
            <th>Stamina:</th>
            <th>Catch Rate:</th>
            <th>Disappears:</th>
            <th>Scanned:</th>
            <th>Google Maps:</th>
        </tr>
    </thead>
        <tfoot>
            <tr>
                <th style="display:none";>ID:</th>
                <th>Pokemon:</th>
                <th>IV:</th>
                <th>CP:</th>
                <th>Boosted by:</th>
                <th>Level:</th>
                <th>Gender:</th>
                <th>Form:</th>
                <th>Attack</th>
                <th>Defense:</th>
                <th>Stamina:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    <tbody>
        <?php if($_POST){if (is_array($mons)) {
            foreach ($mons as $row) {
                if(isset($_POST['iv'])){$miniv = $_POST['iv'];} else {$miniv = '0';}
                if(isset($_POST['cp'])){$mincp = $_POST['cp'];} else {$mincp = '0';}
                if(isset($_POST['lvl'])){$minlvl = $_POST['lvl'];} else {$minlvl = '0';}
                if(isset($_POST['name'])){$monname = $_POST['name'];} else {$monname = '0';}
                if($row->iv >= $miniv && $row->cp >= $mincp && $row->level >= $minlvl){
                ?>
                <tr>
                    <td style="display:none";><?= $row->id ?></td>
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->name ?></td>
                    <td><?= $row->ivoutput ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= $row->weather_boosted_condition ?></td>
                    <td><?= $row->level ?></td>
                    <td><?= $row->gender ?></td>
                    <td><?= $row->form ?></td>
                    <td><?= $row->individual_attack ?></td>
                    <td><?= $row->individual_defense ?></td>
                    <td><?= $row->individual_stamina ?></td>
                    <td><?= $row->catch_prob_1 ?><?= $row->catch_prob_2 ?><?= $row->catch_prob_3 ?></td>
                    <td><?= date($clock, $row->disappear_time) ?></td>
                    <td><?= date($clock, $row->last_modified) ?></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->latitude ?>,<?= $row->longitude ?>'>MAP</a></td>
        </tr> <?php }}
                        } else {
                            echo $mons;
                        }} ?> </tbody>
</table>
<!-- END OF RESULT TABLE -->