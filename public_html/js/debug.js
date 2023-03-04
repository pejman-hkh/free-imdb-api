let html = '';
for( let x in data ) {
	let val = data[x];
	html += '<tr><td>'+val[2]+'</td><td>'+val[0]+'</td><td>'+val[1]+'</td></tr>';
}

$("body").after('<div class="debug"><table class="table">'+html+'</table></div>');
