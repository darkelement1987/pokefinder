<?php
$rocket = getRocket();
global $clock;
?>
<!-- START OF RESULT TABLE -->
<style>
div.top{
    float:left;
}

div.bottom{
    float:left;
}</style>
<table id="rocket_table" class="table table-striped table-bordered table-sm">
<h3>Results:</h3>
    <thead class="thead-dark">
        <tr>
            <th>Stop:</th>
            <th>Grunt Type:</th>
            <th>Grunt Gender:</th>
            <th>Scanned:</th>
            <th>Start:</th>
            <th>End:</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($rocket)) {
            foreach ($rocket as $row) {
                ?>
                <tr>
                    <td><img height='42' width='42' src='<?= $row->image ?>'/> <?= $row->name ?></td>
                    <td><b>Type:</b> <?= $row->rtype ?>
                    <?php if ($row->secreward == 'false'){?>
                    <br><b>100 % encounter chance: </b><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?>
                    <?php } else {?>
                    <br><b>85 % encounter chance: </b><?= $row->firstrow0 ?><?= $row->firstrow1 ?><?= $row->firstrow2 ?>
                    <br><b>15 % encounter chance: </b><?= $row->secondrow0 ?><?= $row->secondrow1 ?><?= $row->secondrow2 ?>
                    <?php }?>
                    </td>
                    <td><?= $row->rgender ?></td>
                    <td><?= date($clock, $row->scanned) ?></td>
                    <td><?= date($clock, $row->start) ?></td>
                    <td><?= date($clock, $row->stop) ?></td>
        </tr> <?php }
                        } else {
                            echo $rocket;
        }?> </tbody>
</table>
<!-- END OF RESULT TABLE -->
