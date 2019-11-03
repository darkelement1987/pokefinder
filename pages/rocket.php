<?php
$rocket = getRocket();
global $clock;
?>
<!-- START OF ROCKET STOPS TABLE -->
    <h3 class="display-6">Team Rocket</h3>
<div class="table-responsive-sm">
<table id="rocket_table" class="table table-striped table-bordered w-auto">
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Pic:</th>
            <th>Stop:</th>
            <th>Stop:</th>
            <th>Gender/Type:</th>
            <th>End:</th>
            <th>15% Chance:</th>
            <th>85% Chance:</th>
            <th>100% Chance:</th>
            <th style="display:none;"></th>
            <th style="display:none;"></th>
            <th style="display:none;"></th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($rocket)) {
            foreach ($rocket as $row) {
                ?>
                <tr>
                    <td></td>
                    <td><img class='pic' height='42' width='42' src='<?= $row->image ?>'/></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?></td>
                    <td><img height='42' width='42' src='images/<?= $row->rgender ?>.png'/><span class="genderhide"> <?= $row->rgender ?> </span><img height='42' width='42' src='images/<?= $row->rtype ?>.png'/><span class="typehide"> <?= $row->rtype ?> </span></td>
                    <td><?= date($clock, $row->stop) ?></td>
                    <td><?php if ($row->secreward == 'true'){?><?= $row->secondrow0 ?><?= $row->secondrow1 ?><?= $row->secondrow2 ?><?php } else { echo '-';}?></td>
                    <td><?php if ($row->secreward == 'true'){?><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?><?php } else { echo '-';}?></td>
                    <td><?php if ($row->secreward == 'false'){?><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?><?php } else { echo '-';}?></td>
                    <td style="display:none;"><?php if ($row->secreward == 'true'){?><?= $row->secondname0 ?><?= $row->secondname1 ?><?= $row->secondname2 ?><?php } else { echo '-';}?></td>
                    <td style="display:none;"><?php if ($row->secreward == 'true'){?><?= $row->firstname0 ?><?= $row->firstname1 ?><?= $row->firstname2 ?><?php } else { echo '-';}?></td>
                    <td style="display:none;"><?php if ($row->secreward == 'false'){?><?= $row->firstname0 ?><?= $row->firstname1 ?><?= $row->firstname2 ?><?php } else { echo '-';}?></td>
        </tr> <?php }
                        } else {
                            echo $rocket;
        }?> </tbody>
</table>
</div>
<!-- END OF ROCKET STOPS TABLE -->