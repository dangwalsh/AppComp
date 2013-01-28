/**
 * @author dwalsh
 */

// perform fadein and set sidebar height
$(window).load( function () {
	$('html').fadeIn().removeClass('js');
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var h = f.offset().top - n.offset().top;
	s.height(h);
});

// adjust sidebar height when window is resized
$(window).resize( function () {
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var sh = f.offset().top - n.offset().top;
	s.height(sh);
});

// adjust sidebar height when window is scrolled
$(window).scroll( function () {
	var n = $('nav');
	var f = $('footer');
	var s = $('#sidebar');
	var h = f.offset().top - n.offset().top;
	s.height(h);
});

// event handlers for page browsing
$(document).ready( function() {	
	// erase errors when user starts typing
	$('#name').click(function() {
		$('#response')[0].innerHTML = "";
		$('#name').removeClass('missing');
	});
	
	$('#pass').click(function() {
		$('#response')[0].innerHTML = "";
		$('#pass').removeClass('missing');
	});
	
	// allow user to log back in
	$('#reset').click(function() {
		window.location = "index.php";
	});
	
	// verify username and password and create user object or display error
	$('#submit').click(function() {
		
		$('#name').removeClass('missing');
		$('#pass').removeClass('missing');
		var name = $('#name').val();
		var pass = $('#pass').val();
		
		if (name == "") {
			$('#name').addClass('missing');
		}
		if (pass == "") {
			$('#pass').addClass('missing');
		}
		
		getUser(name, pass);
	});
	
	// change the color of the navigation bar to match the page--before getting new content from DB
	setNavColor();
	
	// show the table of the page that is clicked	
	$('#navigationT tr td').click(function(e) {
		getPageTable(e);
	});
	
	// get the key word and perform content search when clicked
	$('#search_btn').click(function() {
		var word = $('#search_field').val();
		searchPageTable(word);
	});
	
	// once a new table is populated the event handlers must be appended
	controlSidebar();
});

