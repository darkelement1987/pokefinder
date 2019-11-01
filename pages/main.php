<?php
global $moncount;
global $stopcount;
global $raidcount;
global $questcount;
global $title;
global $maplink;
?>
  <div class="container-fluid">
  <div class="jumbotron jumbotron-fluid">
    <h1 class="display-6">Welcome to <?= $title?></h1>
    <p class="lead">Current game activity in your area:</p> 
  <div class="row">
  <div class="media p-3 m-1">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/pokeball_sprite.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Pokemon</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $moncount ?></span></h5></p>   
    </div>
  </div>
  <div class="media p-3 m-1">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/decrypted_assets/png/m_hat_teamrocket_bundle_icon.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Team Rocket</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $stopcount ?></span></h5></p>   
    </div>
  </div>
    <div class="media p-3 m-1">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/QuestIconProfessor.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Quests</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $questcount ?></span></h5></p>   
    </div>
  </div>
  <div class="media p-3 m-1">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/tx_raid_coin.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Raids</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $raidcount ?></span></h5></p>   
    </div>
  </div>
  <?php if(!empty($maplink)){?>
  <div class="media p-3 m-1">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/ic_map.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Map</strong></h4>
      <h5><a href="<?= $maplink ?>" class="btn btn-secondary btn-lg active" role="button" aria-pressed="true" target="_blank">GO</a></h5></p>   
    </div>
  </div><? }?>
</div>
</div>
</div>