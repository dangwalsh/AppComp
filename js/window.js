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
	
	///////////// LOGIN PAGE ////////////////
	
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
	
	// verify username and password and create 
	//user object or display error
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
	
	///////////// MAIN PAGE ////////////////
	
	// change the color of the navigation bar to match 
	// the page--before getting new content from DB
	setNavColor();
	
	// show the table of the page that is clicked	
	$('nav tr td').click(function(e) {
		getPageTable(e);
	});

	// get the key word and perform content search when clicked
	$('#search_btn').click(function() {
		var word = $('#search_field').val();
		searchPageTable(word);
	});
	
	// once a new table is populated the event handlers 
	// must be appended
	controlSidebar();
	controlTable();	
});

// control the display of the content browser
function controlSidebar()
{
	//$('#contentT div').hide();
	$('#contentT div div').addClass('collapsed');
	$('#contentT div p.sub').addClass('collapsed');

	$('#contentT p.cat').click(function() {
		$(this).next().toggle('fast', null);
		$('#contentT div p.sub').next().hide().removeClass('expanded');
		$('#contentT div p.sub img').removeClass('rotate');
	});
	
	$('#contentT div div').hide();
	$('#contentT div p.sub').click(function() {
		$(this).removeClass('expanded');
		$(this).next().toggle('fast', null);
		$(this).find('>:first-child').toggleClass('rotate');
	});
	
	$('#contentT li').click(function() {
		$('#contentT div div').hide();		
		$(this).parent().parent().show();
		$('#contentT div div').removeClass('expanded');
		$(this).parent().parent().addClass('expanded');
		$('#contentT div p.sub').removeClass('expanded');
		$(this).parent().parent().prev().addClass('expanded');
		$('#contentT div p.sub img').removeClass('rotate');
		$(this).parent().parent().prev().find('>:first-child').addClass('rotate');
		$('#contentT li').removeClass('selected');
		$(this).addClass('selected');
	});
	
	$('#contentT div.title li').click(function(e) {
		if(pageTable != 'admin') {
			getContent(e);
		} else if (e.target.id == 'staff'){
			getStaffSummary(e);	
		} else if (e.target.id == 'courses'){
			getCourseSummary(e);	
		}	
	});
}

function controlTable() 
{
	$('#main').on('click', 'table tr td:first-child', function(e) {
		var t = $(this).parent().parent().parent();  		
   		getDetail(e, t);
	});	
}
