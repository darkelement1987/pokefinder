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
		"drawCallback": function (settings)
		{
			updateSelect();
		},
		paging: true,
		searching: true,
		responsive: true,
		language:
		{
			"search": "Filter results:",
			"info": "Showing _START_ to _END_ of _TOTAL_ Pokémon",
			"infoEmpty": "Showing 0 to 0 of 0 Pokémon",
			"infoFiltered": "(filtered from _MAX_ total Pokémon)",
			"emptyTable": "No Pokémon available in table",
			"zeroRecords": "No matching Pokémon found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
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

		"dom": '<"top"i>rt<"bottom"p><"clear">'

	});
	$('.monresult').select2();
});

$(document).ready(function ()
{
	$('#rocket_table').DataTable(
	{
		order: [
			[8, "asc"]
		],

		columnDefs: [
		{
			type: 'time-uni',
			targets: 8
		},
		{
			className: 'control',
			orderable: false,
			targets: 0
		},
		{
			responsivePriority: 1,
			targets: [2, 5, 8]
		},
		{
			responsivePriority: 2,
			targets: [0, 1, 3, 6, 7]
		},
		{
			className: "stopname",
			"targets": [2]
		},
        {
			className: "stopnamehidden",
			"targets": [3]
		}],


		"pageLength": 10,
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
			"info": "Showing _START_ to _END_ of _TOTAL_ Pokémon",
			"infoEmpty": "Showing 0 to 0 of 0 Pokémon",
			"infoFiltered": "(filtered from _MAX_ total Pokémon)",
			"emptyTable": "No Pokémon available in table",
			"zeroRecords": "No matching Pokémon found",
			"searchPlaceholder": "Enter info",
			"lengthMenu": "Show _MENU_ Pokemon per page",
		},
		"dom": '<"top"f>rt<"bottom"p><"clear">'

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