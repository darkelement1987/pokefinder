<?php
$quest = getQuest();
global $clock;
?>
<!-- START OF QUESTS TABLE -->
  <div class="container-fluid">
  <div class="jumbotron jumbotron-fluid">
    <h3 class="display-6">Quests</h1>
<div class="table-responsive-sm">
<table id="quest_table" class="table table-striped table-bordered w-auto">
    <thead class="thead-dark">
        <tr>
            <th>Pic:</th>
            <th>Stop:</th>
            <th>Reward:</th>
            <th>Quest:</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Filters:</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <?php if (is_array($quest)) {
            foreach ($quest as $row) {
                ?>
                <tr>
                    <td><img class='pic' height='42' width='42' src='<?= $row->image ?>'/></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?> </td>
                    <td><img height='42' width='42' src='<?= $row->type?>'/><?= $row->text?> <?= $row->item?></td>
                    <td><?= $row->task?></td>
        </tr> <?php }
                        } else {
                            echo $quest;
        }?> </tbody>
</table>
</div>
</div>
<!-- END OF QUESTS TABLE -->
