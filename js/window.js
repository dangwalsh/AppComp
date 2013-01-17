/**
 * @author dwalsh
 */

$(window).load( function () {
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var h = f.offset().top - n.offset().top;
	s.height(h);
});

$(window).resize( function () {
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var sh = f.offset().top - n.offset().top;
	s.height(sh);
});

$(window).scroll( function () {
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var h = f.offset().top - n.offset().top;
	s.height(h);
});

// event handlers for page browsing
$(document).ready( function() {
	// allow user to log back in
	$('#reset').click(function() {
		window.location = "http://development/reset.php";
	});
	// verify username and password
	$('#login').click(function() {
		var name = $('#name').val();
		var pass = $('#pass').val();
		getUser(name, pass);
	});
	// show the table of the page that is clicked	
	$('#navigationT tr td').click(
		function(e) {
			getPageTable(e);
		}
	);
	// once a new table is populated the event handlers must be appended
	controlSidebar();
});

