var selectMon = function ()
{

	if (document.getElementById("findgen").checked == false && document.getElementById("findall").checked == false)
	{
		document.searchmon.findall.disabled = false;
		document.searchmon.monster.disabled = false;
		document.searchmon.generation.disabled = true;
	}

	if (document.getElementById("findgen").checked == true && document.getElementById("findall").checked == false)
	{
		document.searchmon.findall.disabled = true;
		document.searchmon.monster.disabled = true;
		document.searchmon.generation.disabled = false;
	}

	if (document.getElementById("findgen").checked == false && document.getElementById("findall").checked == true)
	{
		document.searchmon.findall.disabled = false;
		document.searchmon.findgen.disabled = true;
		document.searchmon.monster.disabled = true;
		document.searchmon.generation.disabled = true;
	}
	else
	{
		document.searchmon.findgen.disabled = false;
	}

	if (document.getElementById("findboost").checked == true)
	{
		document.searchmon.boosted.disabled = false;
	}
	else
	{
		document.searchmon.boosted.disabled = true;
	}

};

$(document).ready(function ()
{
	$('.monfind').select2();
});

$(document).ready(function ()
{
	$('#mon_table').DataTable(
	{
		order: [
			[13, "desc"]
		],

		columnDefs: [
		{
			type: 'time-uni',
			targets: 12
		},
		{
			type: 'time-uni',
			targets: 13
		},
		{
			"targets": [0],
			"visible": false
		}],


		"pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		"drawCallback": function (settings)
		{
			updateSelect();
		},
		paging: true,
		searching: true,
		responsive: true,
        processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Pokémon",
			"infoEmpty": "Showing 0 to 0 of 0 Pokémon",
			"infoFiltered": "(filtered from _MAX_ total Pokémon)",
			"emptyTable": "No Pokémon available in table",
			"zeroRecords": "No matching Pokémon found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page"
		},
		initComplete: function ()
		{
			$('#mon_table tfoot tr').appendTo('#mon_table thead');
			this.api().columns([0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]).every(function ()
			{
				var column = this;
				var select = $('<select class="monresult" style="width:100%;" id="dropcol' + this.index() + '"><option value="">All</option></select>')
					.appendTo($(column.footer()).empty())
					.on('change', function ()
					{
						var val = $.fn.dataTable.util.escapeRegex(
							$(this).val()
						);

						column
							.search(val ? '^' + val + '$' : '', true, false)
							.draw();
					});

				column.data().unique().sort().each(function (d, j)
				{
					var val = $('<div/>').html(d).text();
					if (column.search() === '^' + val + '$')
					{
						select.append('<option value="' + val + '" selected="selected">' + val + '</option>');
					}
					else
					{
						select.append('<option value="' + val + '">' + val + '</option>');
					}
				});
			});
		},

		"dom": '<"top"l>rt<"bottom"p><"clear">'

	});
	$('.monresult').select2();
});

$(document).ready(function ()
{
	$('#rocket_table').DataTable(
	{
		order: [
			[5, "asc"]
		],

		columnDefs: [
		{
			type: 'time-uni',
			targets: 5
		},
		{
			className: 'control',
			orderable: false,
			targets: 0
		},
		{
			responsivePriority: 1,
			targets: [2, 5, 6]
		},
		{
			responsivePriority: 2,
			targets: [0, 4, 5, 8]
		},
		{
			responsivePriority: 3,
			targets: 1
		},
		{
			className: "stopname",
			"targets": [2]
		},
        {
			className: "stopnamehidden",
			"targets": [3]
		},
        {
            visible: false,
            targets: [9,10,11]
        }],


		"pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		autoWidth: true,
		paging: true,
		searching: true,
		responsive:
		{
			details:
			{
				type: 'column'
			}
		},
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ entries",
			"infoEmpty": "Showing 0 to 0 of 0 entries",
			"infoFiltered": "(filtered from _MAX_ total entries)",
			"emptyTable": "No entries available in table",
			"zeroRecords": "No matching entries found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});

$(document).ready(function() {
    $('.questselect').select2();
});

$(document).ready( function () {
  
  var table = $('#quest_table').DataTable(
  {
		order: [
			[2, "asc"]
		],


		"pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		autoWidth: true,
		paging: true,
		searching: true,
		responsive: true,
		processing: true,
		language:
		{
			"search": "Global search:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Quests",
			"infoEmpty": "Showing 0 to 0 of 0 Quests",
			"infoFiltered": "(filtered from _MAX_ total Quests)",
			"emptyTable": "No Quests available in table",
			"zeroRecords": "No matching Quests found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Quests per page",
		},
		dom: '<"top"fl>rt<"bottom"p><"clear">',
            initComplete: function () {
            $('#quest_table tfoot tr').appendTo('#quest_table thead');
            count = 0;
            this.api().columns([1,2,3]).every( function () {
                var title = this.header();
                //replace spaces with dashes
                title = $(title).html().replace(/[\W]/g, '');
                var column = this;
                var select = $('<select id="' + title + '" class="select2" ></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                      //Get the "text" property from each selected data 
                      //regex escape the value and store in array
                      var data = $.map( $(this).select2('data'), function( value, key ) {
                        return value.text ? '^' + $.fn.dataTable.util.escapeRegex(value.text) + '$' : null;
                                 });
                      
                      //if no data selected use ""
                      if (data.length === 0) {
                        data = [""];
                      }
                      
                      //join array into string with regex or (|)
                      var val = data.join('|');
                      
                      //search for the option(s) selected
                      column
                            .search( val ? val : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+j+'">'+d+'</option>' );
                } );
              
              //use column title as selector and placeholder
              $('#' + title).select2({
                multiple: true,
                closeOnSelect: false,
                placeholder: "Select a " + title
              });
              
              //initially clear select otherwise first option is selected
              $('.select2').val(null).trigger('change');
            } );
			}
  }
  );
} );

$(document).ready(function ()
{
	$('#raid_table').DataTable(
	{
		order: [
			[7, "desc"]
		],


		"pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
		autoWidth: true,
		paging: true,
		searching: true,
        responsive: {
            details: true
        },
		columnDefs: [
		{
			type: 'time-uni',
			targets: 7
		},
		{
			className: 'control',
			orderable: false,
			targets: 0
		},
		{
			responsivePriority: 1,
			targets: [2, 3, 8]
		},
		{
			responsivePriority: 2,
			targets: [4, 5, 6, 7]
		},
		{
			responsivePriority: 3,
			targets: 1
		}],
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Raids",
			"infoEmpty": "Showing 0 to 0 of 0 Raids",
			"infoFiltered": "(filtered from _MAX_ total Raids)",
			"emptyTable": "No Raids available in table",
			"zeroRecords": "No matching Raids found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});

function updateSelect()
{
	var table = $('#mon_table').DataTable();
	var select = $('.monresult');
	$(select).each(function (i)
	{
		if ($(this).val() === '')
		{
			var that = this;
			var ii = i + 1;
			var options = [];
			var blank = new Option('All', '', true, true);
			options.push(blank);
			var data = table.column(ii,
			{
				search: 'applied'
			}).data().sort().unique();
			$.each(data, function (ix, v)
			{
				if (ii === 1 || 11)
				{
					v = v.replace(/<[^>]*>?/gm, '');
				}
				var option = new Option(v, v, false,
					false);
				options.push(option);
			});
			$(this).empty();
			$.each(options, function (index, value)
			{
				$(that).append(value);
			});
		}
	});
}

// Limit number of paginate buttons showing at bottom of a table
$.fn.DataTable.ext.pager.numbers_length = 6;

function goBack() {
  window.history.back();
}

$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#pokedex div").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});

$(document).ready(function ()
{
	$('#formTable').DataTable(
	{
		order: [
			[0, "asc"]
		],


        paging:false,
		autoWidth: true,
        ordering: false,
		searching: false,
        responsive: {
            details: true
        },
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Forms",
			"infoEmpty": "Showing 0 to 0 of 0 Forms",
			"infoFiltered": "(filtered from _MAX_ total Forms)",
			"emptyTable": "This Pokémon has no alternative forms",
			"zeroRecords": "No matching Forms found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Forms per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});

$(document).ready(function ()
{
	$('#evoTable').DataTable(
	{
		order: [
			[0, "asc"]
		],


        paging:false,
		autoWidth: true,
        ordering: false,
		searching: false,
        responsive: {
            details: true
        },
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Evolutions",
			"infoEmpty": "Showing 0 to 0 of 0 Evolutions",
			"infoFiltered": "(filtered from _MAX_ total Evolutions)",
			"emptyTable": "This Pokémon has no evolutions",
			"zeroRecords": "No matching Evolutions found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Evolutions per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});

$(document).ready(function ()
{
	$('#rankTable').DataTable(
	{
		order: [
			[0, "asc"]
		],


        paging:true,
		autoWidth: true,
        ordering: true,
		searching: true,
        responsive: {
            details: true
        },
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Pokemon",
			"infoEmpty": "Showing 0 to 0 of 0 Pokemon",
			"infoFiltered": "(filtered from _MAX_ total Pokemon)",
			"emptyTable": "No Pokemon available in table",
			"zeroRecords": "No matching Pokemon found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});

$(document).ready(function ()
{
	$('#shinyTable').DataTable(
	{
		order: [
			[1, "desc"]
		],


        paging:true,
		autoWidth: true,
        ordering: true,
		searching: true,
        responsive: {
            details: true
        },
		processing: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Pokemon",
			"infoEmpty": "Showing 0 to 0 of 0 Pokemon",
			"infoFiltered": "(filtered from _MAX_ total Pokemon)",
			"emptyTable": "No Pokemon available in table",
			"zeroRecords": "No matching Pokemon found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
		},
		"dom": '<"top"fl>rt<"bottom"p><"clear">'

	});
});