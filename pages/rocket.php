<?php
$rocket = getRocket();
global $clock;
?>
<!-- START OF ROCKET STOPS TABLE -->
<div class="table-responsive-sm">
<table id="rocket_table" class="table table-striped table-bordered w-auto">
<h3>Results:</h3>
    <thead class="thead-dark">
        <tr>
            <th></th>
            <th>Pic:</th>
            <th>Stop:</th>
            <th>Stop:</th>
            <th>Type:</th>
            <th>End:</th>
            <th>15% Chance:</th>
            <th>85% Chance:</th>
            <th>100% Chance:</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($rocket)) {
            foreach ($rocket as $row) {
                ?>
                <tr>
                    <td></td>
                    <td><img height='42' width='42' src='<?= $row->image ?>'/></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?></td>
                    <td><img height='42' width='42' src='images/<?= $row->rgender ?>.png'/><img height='42' width='42' src='images/<?= $row->rtype ?>.png'/></td>
                    <td><?= date($clock, $row->stop) ?></td>
                    <td><?php if ($row->secreward == 'true'){?><?= $row->secondrow0 ?><?= $row->secondrow1 ?><?= $row->secondrow2 ?><?php } else { echo '-';}?></td>
                    <td><?php if ($row->secreward == 'true'){?><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?><?php } else { echo '-';}?></td>
                    <td><?php if ($row->secreward == 'false'){?><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?><?php } else { echo '-';}?></td>
        </tr> <?php }
                        } else {
                            echo $rocket;
        }?> </tbody>
</table>
</div>
<!-- END OF ROCKET STOPS TABLE -->
