<?php
function monMad() {
  $mon_name = json_decode(file_get_contents('../json/pokedex.json'), true);
  require('config.php');
  $result = $conn->query("SELECT * FROM pokemon WHERE disappear_time > utc_timestamp()");
  if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $disappear_time = explode(" ", $row["disappear_time"]);
          $iv = round((($row["individual_attack"]+$row["individual_defense"]+$row["individual_stamina"])/45)*100,2);
          echo "<tr>";
          echo "<td> <img height='42' width='42' src='" . $assetRepo . "pokemon_icon_"; if ($row["pokemon_id"] < 100 && $row["pokemon_id"] > 9) { echo '0' . $row["pokemon_id"]; } elseif ($row["pokemon_id"] < 10) { echo '00' . $row["pokemon_id"]; } else { echo $row["pokemon_id"]; }; echo "_00.png'</img> " . $mon_name[$row["pokemon_id"]]['name'] ."</td>";
          echo "<td> <a href='https://maps.google.com/?q=" . $row["latitude"] . "," . $row["longitude"] . "'>MAP</a></td>";
          echo "<td> " . $iv . "%</td>";
          echo "<td> " . $row["cp"] . "</td>";
          echo "<td> " . $disappear_time[1] . "</td>";
          echo "</tr>";
      }
  } else {
      echo "ERROR: No Results Found!";
  }
}

function raidMad() {
  require('config.php');
  $result = $conn->query("SELECT * FROM raid,gymdetails WHERE raid.gym_id=gymdetails.gym_id AND raid.end > utc_timestamp() ORDER BY raid.end ASC;");
  if (mysqli_num_rows($result) > 0) {
      while($row = mysqli_fetch_assoc($result)) {
          $Time_Start = explode(" ", $row["start"]);
          $Time_End = explode(" ", $row["end"]);
          echo "<tr>";
          echo "<td> <img height='42' width='42' src='" . $assetRepo . "pokemon_icon_"; if ($row["pokemon_id"] < 100 && $row["pokemon_id"] > 9) { echo '0' . $row["pokemon_id"]; } elseif ($row["pokemon_id"] < 10) { echo '00' . $row["pokemon_id"]; } else { echo $row["pokemon_id"]; }; echo "_00.png'</td>";
          echo "<td> " . $row["name"] . "</td>";
          echo "<td> " . $row["cp"] . "</td>";
          echo "<td> " . $row["level"] . " ★</td>";
          echo "<td> " . substr($Time_Start[1],0,strrpos($Time_Start[1],':')) .  " - " . substr($Time_End[1],0,strrpos($Time_Start[1],':')) . "</td>";
          echo "</tr>";
      }
  } else {
      echo "ERROR: No Results Found!";
  }
}

function index() {
}

?>