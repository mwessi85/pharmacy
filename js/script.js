$(function() {
	$( "#expiry_date" ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$(function() {
	$( "#date" ).datepicker({ dateFormat: 'yy-mm-dd' });
});


function areYouSure (msg) {
	var bool = window.confirm(msg);
	return bool;
}
