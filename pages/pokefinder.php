<?php
$mons = getMons();
?>
<?php if(!$_POST){?><h3>Search:</h3><?php }?>
<form action="index.php?page=results" method="post" name="searchmon" id="searchmon" <?php if($_POST){?>hidden<?php }?>><input name="page" type="hidden" value="test" />
<table style="border-style:solid; border-width:1px; border-color:Gainsboro; margin-left:10px;" cellpadding="5" >
<tbody>
<style>.select2-container {
width: 100% !important;
}</style>
<tr>
<td>POKEMON</td>
<td>
<select class="monfind" id="monster" name="monster" style="width:100%;" <?php if($_POST){?>disabled<?php }?>>
<script>
let dropdown = $('#monster');

dropdown.empty();

dropdown.append('<option selected="true" disabled>Pokemon</option>');
dropdown.prop('selectedIndex', 0);

const url = 'json/dex.json';

$.getJSON(url, function (data) {
  $.each(data, function (key, entry) {
    dropdown.append($('<option></option>').attr('value', entry.id).text(entry.name.english));
  })
});
</script>
</select>
</td>
<td></td>
</tr>

<tr>
<td></td>
<td><input type="checkbox" id="findall" name="findall" onchange="selectMon()" value="1" <?php if($_POST){?>disabled<?php }?> /> ALL POKEMON</td>
<td></td>
</tr>

<tr>
<td>GENERATION</td>
<td><select name="generation" id="generation" disabled>
  <option value="1">Gen 1</option>
  <option value="2">Gen 2</option>
  <option value="3">Gen 3</option>
  <option value="4">Gen 4</option>
  <option value="5">Gen 5</option>
</select></td>
<td></td>
</tr>

<tr>
<td></td>
<td><input type="checkbox" id="findgen" name="findgen" onchange="selectMon()" value="1" <?php if($_POST){?>disabled<?php }?> /> BY GENERATION</td>
<td></td>
</tr>

<tr>
<td>MIN IV</td>
<td><input maxlength="3" name="iv" size="2" type="number" min="0" max="100" <?php if($_POST){?>disabled<?php }?> /> %</td>
<td></td>
</tr>

<tr>
<td>MIN CP</td>
<td><input maxlength="4" name="cp" size="4" type="number" min="1" max="4431" <?php if($_POST){?>disabled<?php }?> /></td>
<td></td>
</tr>

<tr>
<td>MIN LVL</td>
<td><input maxlength="2" name="lvl" size="2" type="number" min="1" max="35" <?php if($_POST){?>disabled<?php }?> /></td>
<td></td>
</tr>

<tr>
<td>BOOSTED BY</td>
<td><select name="boosted" disabled>
  <option value="1">Clear</option>
  <option value="2">Rainy</option>
  <option value="3">Partly Cloudy</option>
  <option value="4">Cloudy</option>
  <option value="5">Windy</option>
  <option value="6">Snow</option>
  <option value="7">Fog</option>
  <option value="8">Any Weather</option>
</select></td>
<td></td>
</tr>

<tr>
<td></td>
<td><input type="checkbox" id="findboost" name="findboost" onchange="selectMon()" value="1" <?php if($_POST){?>disabled<?php }?> /> BY BOOST</td>
<td></td>
</tr>

<tr>
<td>GENDER</td>
<td><select name="gender" <?php if($_POST){?>disabled<?php }?>>
  <option value="1">All</option>
  <option value="2">Male</option>
  <option value="3">Female</option>
  <option value="4">Genderless</option>
</select></td>
<td></td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="Find" style="float:right" <?php if($_POST){?>disabled<?php }?>/></td>
<td></td>
</tr>

</tbody>
</table>
</form>