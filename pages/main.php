<?php
global $moncount;
global $stopcount;
global $raidcount;
global $questcount;
?>
<!-- START OF MAP RAME -->
<div class="mainpage">
<div class="container mt-3">
  <h2>PokeFinder</h2>
  <p>Activity in your area:</p>
  <div class="media border p-3">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/pokeball_sprite.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Pokemon</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $moncount ?></span></h5></p>   
    </div>
  </div>
</div>
<div class="container mt-3">
  <div class="media border p-3">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/decrypted_assets/png/m_hat_teamrocket_bundle_icon.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Team Rocket</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $stopcount ?></span></h5></p>   
    </div>
  </div>
<div class="container mt-3">
  <div class="media border p-3">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/QuestIconProfessor.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Quests</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $questcount ?></span></h5></p>   
    </div>
  </div>
</div>
<div class="container mt-3">
  <div class="media border p-3">
    <img src="https://raw.githubusercontent.com/ZeChrales/PogoAssets/master/static_assets/png/tx_raid_coin.png" class="mr-3 mt-3 rounded-circle" style="width:60px; height:60px;">
    <div class="media-body">
      <p><h4><strong>Raids</strong></h4>
      <h5>Available: <span class="badge badge-success"><?= $raidcount ?></span></h5></p>   
    </div>
  </div>
</div>

</div>
<!-- END OF MAP FRAME -->
