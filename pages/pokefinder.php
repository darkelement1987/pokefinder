<?php
$mons = getMons();
global $clock;
?>
<?php if(!$_POST){?><h3>Search:</h3><?php }?>
<form action="index.php#found" method="post" name="searchmon" id="searchmon" <?php if($_POST){?>hidden<?php }?>><input name="page" type="hidden" value="test" />
<table style="border-style:solid; border-width:1px; border-color:Gainsboro; margin-left:10px;" cellpadding="5" >
<tbody>

<style>.select2-container {
width: 100% !important;
}</style>
<script>

$(document).ready(function() { 
        $('.monfind').select2({ width: 'resolve' });           
});

$(document).ready(function() {
    $('.monfind').select2();
      
});

$(window).bind("pageshow", function() {
document.getElementById("searchmon").reset();
});
</script>
<tr>
<td>POKEMON</td>
<td>
<script type="text/javascript" language="javascript">
function toggleSelect()
{

if (document.getElementById("findgen").checked == false && document.getElementById("findall").checked == false)
{
document.searchmon.findall.disabled = false;
document.searchmon.monster.disabled = false;
document.searchmon.generation.disabled = true;
};

if (document.getElementById("findgen").checked == true && document.getElementById("findall").checked == false)
{
document.searchmon.findall.disabled = true;
document.searchmon.monster.disabled = true;
document.searchmon.generation.disabled = false;
};

if (document.getElementById("findgen").checked == false && document.getElementById("findall").checked == true)
{
document.searchmon.findall.disabled = false;
document.searchmon.findgen.disabled = true;
document.searchmon.monster.disabled = true;
document.searchmon.generation.disabled = true;
} else {
    document.searchmon.findgen.disabled = false;
};

if (document.getElementById("findboost").checked == true)
{
document.searchmon.boosted.disabled = false;
} else {
    document.searchmon.boosted.disabled = true;
};

}
</script>
<select class="monfind" id="monster" name="monster" style="width:100%;" <?php if($_POST){?>disabled<?php }?>>
<script>
let dropdown = $('#monster');

dropdown.empty();

dropdown.append('<option selected="true" disabled>Pokemon</option>');
dropdown.prop('selectedIndex', 0);

const url = './json/dex.json';

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
<td><input type="checkbox" id="findall" name="findall" onchange="toggleSelect()" value="1" <?php if($_POST){?>disabled<?php }?> /> ALL POKEMON</td>
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
<td><input type="checkbox" id="findgen" name="findgen" onchange="toggleSelect()" value="1" <?php if($_POST){?>disabled<?php }?> /> BY GENERATION</td>
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
<td><input type="checkbox" id="findboost" name="findboost" onchange="toggleSelect()" value="1" <?php if($_POST){?>disabled<?php }?> /> BY BOOST</td>
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
<?php if($_POST){?><h3>Filter results:</h3><?php }?>
<table cellpadding="3" cellspacing="0" border="0" <?php if(!$_POST){?>hidden<?php }?>>
        <thead>
            <tr>
                <th>Target</th>
                <th>Search text</th>
            </tr>
        </thead>
        <tbody>
            <tr id="filter_col2" data-column="1">
                <td>NAME</td>
                <td align="center"><input type="text" class="column_filter" id="col1_filter"></td>
            </tr>
            <tr id="filter_col3" data-column="2">
                <td>IV</td>
                <td align="center"><input type="text" class="column_filter" id="col2_filter"></td>
            </tr>
            <tr id="filter_col4" data-column="3">
                <td>CP</td>
                <td align="center"><input type="text" class="column_filter" id="col3_filter"></td>
            </tr>
            <tr id="filter_col5" data-column="4">
                <td>BOOSTED BY</td>
                <td align="center"><input type="text" class="column_filter" id="col4_filter"></td>
            </tr>
            <tr id="filter_col6" data-column="5">
                <td>LEVEL</td>
                <td align="center"><input type="text" class="column_filter" id="col5_filter"></td>
            </tr>
            <tr id="filter_col7" data-column="6">
                <td>GENDER</td>
                <td align="center"><input type="text" class="column_filter" id="col6_filter"></td>
            </tr>
            <tr id="filter_col8" data-column="7">
                <td>FORM</td>
                <td align="center"><input type="text" class="column_filter" id="col7_filter"></td>
            </tr>
            <tr id="filter_col9" data-column="8">
                <td>ATTACK</td>
                <td align="center"><input type="text" class="column_filter" id="col8_filter"></td>
            </tr>
            <tr id="filter_col10" data-column="9">
                <td>DEFENSE</td>
                <td align="center"><input type="text" class="column_filter" id="col9_filter"></td>
            </tr>
            <tr id="filter_col11" data-column="10">
                <td>STAMINA</td>
                <td align="center"><input type="text" class="column_filter" id="col10_filter"></td>
            </tr>
        </tbody>
<table id="mon_table" class="table table-striped table-bordered table-sm" style="width:100%" <?php if(!$_POST){?>hidden<?php }?>>
<?php if($_POST){?><h3>Results:</h3><?php }?>
    <thead class="thead-dark">
        <tr>
            <th style="display:none";>ID:</th>
            <th>Pokemon:</th>
            <th>IV:</th>
            <th>CP:</th>
            <th>Boosted by:</th>
            <th>Level:</th>
            <th>Gender:</th>
            <th>Form:</th>
            <th>Att:</th>
            <th>Def:</th>
            <th>Sta:</th>
            <th>Catch Rate:</th>
            <th>Disappears:</th>
            <th>Scanned:</th>
            <th>Google Maps:</th>
        </tr>
    </thead>
    <tbody>
        <?php if (is_array($mons)) {
            foreach ($mons as $row) {
                if(isset($_POST['iv'])){$miniv = $_POST['iv'];} else {$miniv = '0';}
                if(isset($_POST['cp'])){$mincp = $_POST['cp'];} else {$mincp = '0';}
                if(isset($_POST['lvl'])){$minlvl = $_POST['lvl'];} else {$minlvl = '0';}
                if(isset($_POST['name'])){$monname = $_POST['name'];} else {$monname = '0';}
                if($row->iv >= $miniv && $row->cp >= $mincp && $row->level >= $minlvl){
                ?>
                <tr>
                    <td style="display:none";><?= $row->id ?></td>
                    <td><img height='42' width='42' src='<?= $row->sprite ?>'/> <?= $row->name ?></td>
                    <td><?= $row->ivoutput ?></td>
                    <td><?= $row->cp ?></td>
                    <td><?= $row->weather_boosted_condition ?></td>
                    <td><?= $row->level ?></td>
                    <td><?= $row->gender ?></td>
                    <td><?= $row->form ?></td>
                    <td><?= $row->individual_attack ?></td>
                    <td><?= $row->individual_defense ?></td>
                    <td><?= $row->individual_stamina ?></td>
                    <td><?= $row->catch_prob_1 ?><?= $row->catch_prob_2 ?><?= $row->catch_prob_3 ?></td>
                    <td><?= date($clock, $row->disappear_time) ?></td>
                    <td><?= date($clock, $row->last_modified) ?></td>
                    <td><a href='https://maps.google.com/?q=<?= $row->latitude ?>,<?= $row->longitude ?>'>MAP</a></td>
        </tr> <?php }}
                        } else {
                            echo $mons;
                        } ?> </tbody>
</table>
<?php if(!empty($_POST)){?>
<script>

function filterColumn ( i ) {
    $('#mon_table').DataTable().column( i ).search(
        $('#col'+i+'_filter').val()
    ).draw();
}

    $(document).ready(function() {
        $('#mon_table').DataTable(
        
        {
            
            order: [
                [13, "desc"]
                ],

            columnDefs: [
            { type: 'time-uni', targets: 12 },
            { type: 'time-uni', targets: 13 },
            { "targets": [ 0 ], "visible": false}
            ],
            
            
            "pageLength": 10,
            paging: true,
            lengthChange: true,
            searching: true,
            responsive: true,
            lengthMenu: [[10, 20, 25, 50, -1], [10, 20, 25, 50, 'All']],
            
            language: {
                "search":         "Filter results:",
                "info":           "Showing _START_ to _END_ of _TOTAL_ Pokémon",
                "infoEmpty":      "Showing 0 to 0 of 0 Pokémon",
                "infoFiltered":   "(filtered from _MAX_ total Pokémon)",
                "emptyTable":     "No Pokémon available in table",
                "zeroRecords":    "No matching Pokémon found",
                "searchPlaceholder": "Enter info",
                "lengthMenu":     "Show _MENU_ Pokemon per page",
                }
            
        });
        
            $('input.column_filter').on( 'keyup click', function () {
        filterColumn( $(this).parents('tr').attr('data-column') );
    } );
        
    });
</script>
<?php }?>
