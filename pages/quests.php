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
            <th>Map:</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
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
                    <td><a href='#' class='pop'><img class='pic' height='42' width='42' src='<?= $row->image ?>'  title='<?= $row->name?>'/></a></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><?= $row->name ?> </td>
                    <td><img height='42' width='42' src='<?= $row->type?>'/><?= $row->text?> <?= $row->item?></td>
                    <td><?= $row->task?></td>
                    <td><button type="button" class="btn btn-secondary" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<a href='https://maps.google.com/?q=<?= $row->lat?>,<?= $row->lon?>'><img class='map' src='https://maps.googleapis.com/maps/api/staticmap?center=<?= $row->lat?>,<?= $row->lon?>&zoom=15&scale=1&size=150x100&maptype=roadmap&key=AIzaSyCGhAi9BF_UjqwHRK4mrcOhWJGLViRuyZQ&format=png&visual_refresh=true&markers=size:small%7Ccolor:0xff0000%7Clabel:%7C<?= $row->lat?>,<?= $row->lon?>
'></a>">
  Show Map
</button></td>
        </tr> <?php }
                        } else {
                            echo $quest;
        }?> </tbody>
</table>
</div>
</div>
</div>
<!-- END OF QUESTS TABLE -->