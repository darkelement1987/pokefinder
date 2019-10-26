<?php
$quest = getQuest();
global $clock;
?>
<!-- START OF QUESTS TABLE -->
<div class="table-responsive-sm">
<table id="quest_table" class="table table-striped table-bordered w-auto">
<h3>Quests:</h3>
    <thead class="thead-dark">
        <tr>
            <th>Pic:</th>
            <th>Stop:</th>
            <th>Reward:</th>
            <th>Quest:</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($quest)) {
            foreach ($quest as $row) {
                ?>
                <tr>
                    <td><img height='42' width='42' src='<?= $row->image ?>'/></td>
                    <td><?= $row->name?></td>
                    <td><img height='42' width='42' src='<?= $row->type?>'/><?= $row->text?></td>
                    <td><?= $row->task?></td>
        </tr> <?php }
                        } else {
                            echo $quest;
        }?> </tbody>
</table>
</div>
<!-- END OF QUESTS TABLE -->
