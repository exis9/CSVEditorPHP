// Enable/disable row 
$(document).on("click", "a[rel=editrow]", function(e) { 
	//$("a[rel=editrow]").click(function(e) { 
	e.preventDefault();
	// get id clicked a and extract the linenumber
	const linenum = this.id.split("-")[1];

	// change button icon and row background color according to state
	let rowIsEnabled;
	if ($(this).children().attr("class") === "fa fa-unlock-alt") {
		rowIsEnabled = true;
		$(this).children().attr("class", "fa fa-lock");
	}
	else {
		rowIsEnabled = false;
		$(this).children().attr("class", "fa fa-unlock-alt");
	}
	$("#row-"+ linenum).toggleClass("success");

	// Toggle (disable/enable) every input field in row
	$("input[rel=input-"+ linenum + "]").each(function( i ) {
		$(this).prop("disabled", rowIsEnabled);
	});
});    
	
// Delete row
$(document).on("click", "a[rel=deleterow]", function(e) { 
//    $("a[rel=deleterow]").click(function(e) { 
	e.preventDefault();
	// get id clicked a and extract the linenumber
	const linenum = this.id.split("-")[1];
	// change background color of row to indicate that row is unlocked/locked
	$("#row-"+ linenum).hide();
});

// Add row
$("#addrow").on('click', function(e) { 
	e.preventDefault();
	// get linenumber of last row
	const linenum = parseInt($("#csvtable tbody tr:last").attr("id").split("-")[1]);
	$("#csvtable tbody").append(makeTableRow(linenum+1, g_columns, true));
});
	
// Cancel (reload page)
$("#cancel").on('click', function(e) { 
	e.preventDefault();
	location.reload(true);
});

$('#saveFile').on('click', ()=>{
	function download(url, filename) {
		fetch(url)
			.then(response => response.blob())
			.then(blob => {
			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = filename;
			link.click();
		})
		.catch(console.error);
	}
	download('csv/items.csv', 'items.csv')
})

// Save
$("#save").on('click', function(e) { 
	const escapeCells = (s) => {
		s = s.replace(/,/g, '')
		s = s.replace(/"/g, '')
		s = s.replace(/\n/g, '')
		return s
	}
	e.preventDefault();
	
	let csvlines = {};
	let columncnt = 0;
	let linecnt = 0;
	// Loop through all (visible only) table rows and make data
	$("[rel=row]:visible").each(function() {
		const linenum = this.id.split("-")[1];
		let thisline = {};
		columncnt = 0;
		$("input[rel=input-"+ linenum + "]").each(function() {
			thisline['col-'+columncnt] = escapeCells( $(this).val() )
			columncnt++;
		});
		csvlines['line-'+linecnt] = thisline;
		linecnt++;
	});
	let csvdata = {csvfile: g_csvfile, lines: linecnt, columns: columncnt, data: csvlines};
	//alert(JSON.stringify(csvdata));
	//return false
	// Write data to file and show result to user
	$.ajax({
		url: "savetocsv.php",
		method: "POST",
		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		async: false,
		data: JSON.stringify(csvdata),
		cache: false,
		success: function(response) {
			makeMessage("<h4>" + response.responseText + "</h4>保存しました！", "success", "message");
			// reload page in 3 sec
			setTimeout(function(){location.reload();}, 2500);
		},
		error: function(response) {
			makeMessage("<h4>ERROR</h4>" + response.status + " " + response.statusText, "danger", "message");
		}
	});        
});


function makeMessage(messagetext, messagetype, messageid){
	const h = "<div class=\"alert alert-" + messagetype + " alert-dismissible\" role=\"alert\">"
		+ "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>"
		+ messagetext + "</div>";
	$("#"+messageid).html(h);
	return;
}

function makeTableRow(linenum, columns, isenabled) {
	let h = "<tr rel=\"row\" id=\"row-" + linenum + "\" class=\"" + (isenabled===true ? "success" : "") + "\">";
	for (let columncnt=0; columncnt<columns; columncnt++) {
		h += `<td><label class="input-sizer"><input class="cEditInput" type="text" rel="input-${linenum}" onInput="this.parentNode.dataset.value = this.value" size="4" value=""></label></td>`
	}
	h += "<td>";
	h += " <a href=\"#\" rel=\"editrow\" id=\"editrow-" + linenum + "\" title=\"Edit row\" class=\"btn btn-default btn-sm\"><i class=\"fa " + (isenabled===true ? "fa-unlock-alt" : "fa-lock") + "\"></i></a>";
	h += " <a href=\"#\" rel=\"deleterow\" id=\"deleterow-" + linenum + "\" title=\"Delete row\" class=\"btn btn-default btn-sm\"><i class=\"fa fa-trash\"></i></a>";
	h += "</td>";
	h += "</tr>";
	return h;
}