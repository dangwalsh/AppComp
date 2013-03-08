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
	var b = $('#bar');
	var p = $('progress');
	var w = b.width() - 100;
	p.width(w);
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
	// change the color of the nav bar to match the page before getting new content from DB!!!
	setNavColor();	
	controlNavbar();
	controlSearchbar();
	controlSidebar();
	controlTable();
	controlGraph();
	controlForm();
	controlContent();
});
// show the table of the page that is clicked	
function controlNavbar()
{	
	$('nav tr td').click(function(e) {
		getPageTable(e);
	});	
}
// get the key word and perform content search when clicked
function controlSearchbar()
{
	$('#search_btn').click(function() {
		var word = $('#search_field').val();
		searchPageTable(word);
	});	
}
// function to add user interaction to sidebar
function controlSidebar()
{
	// set accordion to be completely collapsed
	$('#contentT div div').addClass('collapsed');
	$('#contentT div p.sub').addClass('collapsed');
	// clicking a category expands the list of subcategories
	$('#contentT p.cat').click(function() {
		$(this).next().toggle('fast', null);
		$('#contentT div p.sub').next().hide().removeClass('expanded');
		$('#contentT div p.sub img').removeClass('rotate');
	});
	// clicking a subcategory expands the list of titles and rotates the disclosure indicator down
	$('#contentT div div').hide();
	$('#contentT div p.sub').click(function() {
		$(this).removeClass('expanded');
		$(this).next().toggle('fast', null);
		$(this).find('>:first-child').toggleClass('rotate');
	});
	// clicking on a title makes it bold applies the list hilight and closes other lists and rotates their disclosure indicators back
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
	// create the appropriate element in the main window
	$('#contentT div.title li').click(function(e) {
		// if you're not on the admin page jsut retrieve the selected content
		if(pageTable != 'admin') {
			var uid = $('#userid').html();
			getContent(e, uid);
		} else {
			// generate the form to enter new content into the db
			if (e.target.id == 'Create') {
				var t = $(this).html();
				buildForm(e, t);
			// otherwise generate the dashboard page
			} else {
				buildDashboardPage(e);
			}
		}
	});
}
// function to add user interaction to tables
function controlTable() 
{
	$('#main').on('click', 'table tbody tr td:first-child', function(e) {
		var t = $(this).parent().parent().parent();  		
   		buildDetailPage(e, t);
	});	
	$('#main').on('change', 'table tfoot tr td select.selector', function(e) {
		var q = $(this);
		var v = q.val();
		var t = q.parent().parent().parent().parent().prop('id');
		var s = e.target.id;
		getCorresponding(t, s, v); 
	});
	$('#main').on('click', '#courseT tfoot tr td #upload', function(e) {
		var n = $('#course_num').val();
		var s = $('#main h1').prop('id');
		addEntry('courseT', n, s);
	});
	$('#main').on('click', '#projectT tfoot tr td #upload', function(e) {
		var n = $('#proj_num').val();
		var s = $('#main h1').prop('id');
		addEntry('projectT', n, s);
	});
	$('#main').on('click', '#courseT tbody tr td #delete', function(e) {
		var n = $(this).parent().prev().prev().prev().children().html();
		var s = $('#main h1').prop('id');
		deleteEntry('courseT', n, s);
	});
	$('#main').on('click', '#projectT tbody tr td #delete', function(e) {
		var n = $(this).parent().prev().prev().prev().children().html();
		var s = $('#main h1').prop('id');
		deleteEntry('projectT', n, s);
	});
}
// function to add user interaction to graphs
function controlGraph()
{
	$('#main').on('mouseover', 'svg #plot>rect', function(e) {
		$(this).attr('fill', '#159DD7');
		$('#' + e.target.id + "_group").show();
	}).on('mouseout', 'svg #plot>rect', function(e) {
		$(this).attr('fill', '#058DC7');
		$('#' + e.target.id + "_group").hide();
	});
	$('#main').on('change', '#progress p select', function(e) {
		var uid = $('#name h1').prop('id');
		var graph = $(this).val();
		getCategoriesGraph(uid, graph);
	});
}
// function to add user interaction to submission forms
function controlForm()
{
	var file;

	$('#main').on('change', ':file', function(e) {
    	file = this.files[0];
    	//var name = file.name;
    	//var size = file.size;
    	//var type = file.type;
    	var b = $('#bar');
		var p = $('progress');		
		p.width(b.width() - 100);
    	p.show('fast', null);		
	});
	
	$('#main').on('click', '#createForm #submit', function(e) {
		var type = $('#type span').html();
		var cat = $('#main #category').val();
		var sub = $('#main #subcategory').val();
		var title = $('#main #title').val();
		var cont = $('#main #content').val();
		var uid = $('#userid').html();
		var cid = $('#contentId').val();
		var path = '';
		if (file) {
			path = "php/uploads/" + file.name;
			handleFiles(file);
		}
		submitContent(uid, type, cat, sub, title, cont, cid);
	});
}
// function to add admin ability to edit content
function controlContent()
{
	$('#main').on('click', '#tabs li', function(e) {
		var tab = $(this).html();		
		if (tab == 'View') {
			var uid = $('#userid').html();
			getContent(e, uid);
		} else if (tab == 'Edit') {
			editContent(e);
		}
	});
}
