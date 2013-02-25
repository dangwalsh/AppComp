/**
 * @author dwalsh
 */

//preload assets to be used on this page
function MM_preloadImages()
{
	var d = document;
	if (d.images) {
		if (!d.MM_p) d.MM_p = new Array();
		var i, j = d.MM_p.length, a = MM_preloadImages.arguments;
		for (i = 0; i < a.length; i++) {
			if (a[i].indexOf("#") != 0) {
				d.MM_p[j] = new Image;
				d.MM_p[j++].src = a[i];
			}
		}
	}
}

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
	if ($('#log')) {
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
	} 
	// change the color of the nav bar to match the page
	// before getting new content from DB!!!
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
	controlGraph();
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
		} else if (e.target.id == 'dashboard'){
			buildDashboardPage(e);
		} else {
			buildSummaryPage(e);
		}
	});
}

function controlTable() 
{
	$('#main').on('click', 'table tbody tr td:first-child', function(e) {
		var t = $(this).parent().parent().parent();  		
   		buildDetailPage(e, t);
	});	
	$('#main').on('click', 'table tfoot tr td select.selector', function(e) {
		var q = $(this);
		var v = q.val();
		var t = q.parent().parent().parent().parent().prop('id');
		var s = e.target.id;
		getCorresponding(t, s, v);
	});
	$('#main').on('click', '#courseT tfoot tr td button', function(e) {
		var n = $('#course_num').val();
		var s = $('#main h1').prop('id');
		addEntry('courseT', n, s);
	});
	$('#main').on('click', '#projectT tfoot tr td button', function(e) {
		var n = $('#proj_num').val();
		var s = $('#main h1').prop('id');
		addEntry('projectT', n, s);
	});
}

function controlGraph()
{
	$('#main').on('mouseover', 'svg #plot rect', function(e) {
		$(this).attr('fill', '#159DD7');
	}).on('mouseout', 'svg #plot rect', function(e) {
		$(this).attr('fill', '#058DC7');
	});
}
