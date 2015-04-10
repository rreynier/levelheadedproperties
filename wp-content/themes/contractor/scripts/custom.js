///////////
//VAR SETUP
///////////
var theDocument = jQuery(document),
	theWindow = jQuery(window),
	theHtml = jQuery('html'),
	theBody = jQuery('body'),
	htmlBody = jQuery("html,body"),
	headerContainer = jQuery('#headerContainer'),
	header = jQuery('#header'),
	widgetPanel = jQuery('#widgetPanel'),
	sidebar = jQuery('#sidebar'),
	widgetPanelToggle = jQuery('#widgetPanelToggle'),
	searchForm = jQuery('#header #searchform'),
	headerImages = jQuery('#headerImages'),
	headerImage = headerImages.children();
	postFilter = jQuery('#postFilter'),
	theFilter = postFilter.children(),
	postsContainer = jQuery('.postsContainer'),
	thePost = postsContainer.children(),
	menuToggle = jQuery('#menuToggle'),
	menuContainer = jQuery('#menusContainer'),
	stickyPosts = jQuery('#stickyPosts'),
	nextPrevItem = jQuery('.nextPrevItem'),
	commentForm = jQuery('#respond .comment-form'),
	scrollDown = jQuery('#scroll-down');


/////////////
//SCROLL DOWN
/////////////
scrollDown.click(function(){
	var scrollHere = jQuery('#contentContainer').offset().top;
	
	htmlBody.stop(true,true).animate({scrollTop:scrollHere},1500);
});
	
	
///////////////////////////
//AJAX POST/PAGE PAGINATION
///////////////////////////
theDocument.on('click','.postinate a',function() {    
	var url = jQuery(this).attr('href'),
		entryContainer = jQuery("#entryContainer"),
		entry = jQuery(".entry"),
		entryHeight = entryContainer.outerHeight(),
		entryTop = (entry.offset().top - 15);
			
	//SET ARTIFICAL HEIGHT
	entryContainer.css({height:entryHeight+"px"});	
	
	//SCROLL TO TOP OF ENTRY
	if(entryTop < jQuery(window).scrollTop()){
		htmlBody.animate({scrollTop:entryTop},800);
	}
	
	//FADE OUT ENTRY
	jQuery('.postinate').fadeOut(500);
	entry.fadeOut(500,function(){
	
		//LOAD STUFF
		entryContainer.html("<div id='pageLoading'></div>").load(url + " #entryContainer",'', function() {
		
    		//FADE IN ENTRY
    		jQuery(".entry, .postinate").hide().fadeIn(500);
    		
    		//SET NATURAL HEIGHT
    		entryContainer.css({height:"auto"}).children('#entryContainer').unwrap();
   		});
	});
	
	return false;
});	
	

//////////////
//CHECK IF iOS
//////////////
if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
	//ASSIGN CLASS
	theBody.addClass('ios-device');
}


////////////////////////
//QUOTE CONTENT FUNCTION
////////////////////////
function quoteContent(){
	jQuery('.quote-content').not('.checked-quote').each(function(){
		var thisQuote = jQuery(this);
		
		if( (thisQuote.height() + 10) <  thisQuote.prop('scrollHeight') ) {		
			thisQuote.addClass('checked-quote').wrap('<div class="quote-wrapper"></div>').parent().prepend('<a href="#" class="toggle-quote"></a>');
		}
	});
}
//RUN FUNCTION
quoteContent();


///////////////
//QUOTE + CLICK
///////////////
theDocument.on('click','.toggle-quote',function(){

	var thisToggle = jQuery(this),
		thisWrapper = thisToggle.parent(),
		thisQuote = thisWrapper.find('.quote-content');
	
	thisToggle.toggleClass('toggle-on');
	thisWrapper.toggleClass('open-wrapper');
	thisQuote.toggleClass('open-quote');
	
	return false;
});


////////////////////////
//ASIDE CONTENT FUNCTION
////////////////////////
function asideContent(){
	jQuery('.aside-content').not('.checked-aside').each(function(){
		var thisAside = jQuery(this);
		
		if( (thisAside.height() + 10) <  thisAside.prop('scrollHeight') ) {		
			thisAside.addClass('checked-aside').wrap('<div class="aside-wrapper"></div>').parent().prepend('<a href="#" class="toggle-aside"></a>');
		}
	});
}
//RUN FUNCTION
asideContent();


///////////////
//ASIDE + CLICK
///////////////
theDocument.on('click','.toggle-aside',function(){

	var thisToggle = jQuery(this),
		thisWrapper = thisToggle.parent(),
		thisAside = thisWrapper.find('.aside-content');
	
	thisToggle.toggleClass('toggle-on');
	thisWrapper.toggleClass('open-wrapper');
	thisAside.toggleClass('open-aside');
	
	return false;
});


/////////////////////		
//COMMENT REPLY STUFF
/////////////////////	
jQuery('#reply-title').click(function(){
	commentForm.stop(true,true).slideToggle(300);
});
jQuery('.comment-reply-link').click(function(){
	commentForm.stop(true,true).show();
});
jQuery('#cancel-comment-reply-link').click(function(e){
	commentForm.stop(true,true).hide();
	e.stopPropagation();
});


///////////////////		
//STICKY POST STUFF
///////////////////	
if ( nextPrevItem.length > 0 ) {
	theBody.addClass('with-sticky-post');
}
if ( nextPrevItem.length == 1 ) {
	jQuery('#stickyPosts').addClass('fill-sticky-post');
}


//////////////////////		
//OVERFLOW TEXT SCROLL
//////////////////////	
var restEffect = '';
jQuery('.overflow-scroll').stop(true,true).hover(function(){
	var showThis = jQuery(this),
		showThisWidth = showThis.width(),
		thisScrollWidth = showThis[0].scrollWidth;
	
	if(thisScrollWidth > showThisWidth) {		
		showThis.css({width:showThisWidth});
		function overflowScroll(){
			showThis.stop(true,true).css({textIndent:"0"});
			var spaceNeeded = (thisScrollWidth - (showThisWidth*.75)),
				scrollSpeed = (spaceNeeded * 30);
			showThis.stop(true,true).delay(1000).animate({textIndent:"-"+spaceNeeded+"px"},scrollSpeed,function(){
				restEffect = setTimeout(overflowScroll, 1500);
			});
		}
		overflowScroll();
	}
},function(){
	jQuery(this).stop(true,true).css({textIndent:"0",width:"auto"});
	clearTimeout(restEffect);
});


//////////////
//FILTER CLICK
//////////////
var filterItems;
theFilter.click(function(){
	
	//RESET ACTIVE FILTER
	theFilter.removeClass('activeFilter');
	
	//ASSIGN VARS
	var thisFilter = jQuery(this),
		filterClass = thisFilter.attr('class'),
		postItems = jQuery('.postsContainer > div');
	
	//ASSIGN ACTIVE FILTER
	thisFilter.addClass('activeFilter');
	
	//ASSIGN ALL ITEMS TO filterItems VAR
    if (!filterItems) { 
    	filterItems = postItems.detach();
    }
    
    //EMPTY CONTAINER
    jQuery('.postsContainer').empty();
    jQuery('.postsContainer:first-child').siblings('.postsContainer').remove();
    
    //SHOW ALL ITEMS
    if (filterClass == 'category-all') {
        filterItems.appendTo('.postsContainer:first-child');        
        filterItems = null;
                        
    //SHOW FILTER ITEMS ONLY
    } else {
        filterItems.filter('.' + filterClass).appendTo('.postsContainer:first-child');
                        
        //RUN FUNCTIONs
		if( theBody.hasClass('auto-load-posts') ) {
			autoLoadPosts();
		}
    }

    return false;
});


//////////////////////
//LOAD MORE AJAX STUFF
//////////////////////
jQuery(document).on('click',"#loadMore a",function(e){

	if (!jQuery(e.target).is(".loading")) {
		
		//VAR SETUP
	   	var thisLink = jQuery(this),
	   		url = thisLink.attr("href");
		
		//SHOW LOADING TEXT
		thisLink.parent().addClass('loading');		
		
		//ADD DIV FOR NEW ITEMS
		jQuery('#ajaxContainer').append('<div id="newStuff"></div>');
		
		//LOAD NEW ITEMS
		jQuery('#newStuff').load(url + " #ajaxContainer > div",function(){
			
			//REMOVE OLD LINK
			jQuery('.loading').remove();
			
			//RUN FUNCTIONs
			quoteContent();
			asideContent();
			
			//UNWRAP
			jQuery('#newStuff > div').unwrap();
			
			//CHECK IF FILTER ACTIVE
			if(filterItems){
				filterItems.appendTo('.postsContainer:first-child');
				filterItems = null;
				jQuery('.activeFilter').click();
			} else {
				jQuery('.activeFilter').click();
			}
			
			//AUTO LOAD POSTS
			if( theBody.hasClass('auto-load-posts') ) {
				autoLoadPosts();
			}
			
		});
    }
    
    return false;
});


//////////////////////////
//AUTO LOAD POSTS FUNCTION
//////////////////////////
function autoLoadPosts(){

	var	loarMore = jQuery('#loadMore a').not('.inView');
	
	theBody.addClass('auto-load-posts');
	
	if(loarMore.length > 0){
		var docViewTop = jQuery(window).scrollTop(),
	    	docViewBottom = docViewTop + jQuery(window).height(),
	    	elemTop = loarMore.offset().top;	
	
		if(docViewBottom + 400 >= elemTop){
			loarMore.addClass('inView').click();
		}
	}
}


////////////////////////
//RESPONSIVE MENU TOGGLE
////////////////////////
menuToggle.click(function(){
	menuToggle.toggleClass('opened closed');
	menuContainer.stop(true,true).slideToggle(300);
	return false;
});


///////////////////////////
//HEADER SLIDESHOW FUNCTION
///////////////////////////
function headerSlideshow(){
	var firstBg = headerImage.first();
		activeBg = jQuery('.activeBg');
	
	if(!activeBg.length > 0){
		firstBg.addClass('activeBg');
		var activeBg = firstBg;
	}

	var nextBg = activeBg.next();
	
	if(nextBg.length > 0){
		activeBg.removeClass('activeBg');
		nextBg.addClass('activeBg').stop(true,true).fadeIn(3000,function(){
			activeBg.hide();
		});
	} else {
		activeBg.removeClass('activeBg');
		firstBg.addClass('activeBg').stop(true,true).fadeIn(3000,function(){
			activeBg.hide();
		});
	}
};


/////////////////////
//WIDGET PANEL TOGGLE
/////////////////////
widgetPanelToggle.click(function(){		
	widgetPanel.add(searchForm).stop(true,true).fadeToggle(300);
	theBody.toggleClass('panelOpen');
	theHtml.animate({scrollTop: 0}, 300);
	
	if( scrollDown.length > 0 ) {
		scrollDown.toggleClass('fading-out');
	}
	
	showWidgets();
	
	return false;
});

///////////////
//SHOW WIDGETS
///////////////
function showWidgets(){
	jQuery('#sidebar .widget').each(function(){
		var thisItem = jQuery(this),
			i = thisItem.index();
		
		if (theBody.hasClass('panelOpen')){
			thisItem.stop(true,true).delay(i*100).animate({opacity:'1',left:'0'},500);
		} else {
			thisItem.stop(true,true).animate({opacity:'0',left:'-10px'},300);
		}	
	});
}


//////////////
//WIDGET SPACE
//////////////
function widgetSpace(){
	var headerHeight = header.outerHeight();
		
	widgetPanel.css({top:headerHeight+'px'});
}
//RUN FUNCTION
widgetSpace();


//////////////////////////
//STANDARD HEADER FUNCTION
//////////////////////////
function standardDisplay(){
		
	headerContainer.css({paddingBottom:'0'});
	
	var winHeight = jQuery(window).height(),
		bodyHeight = theBody.height(),
		fillerSpace = winHeight - bodyHeight;

	if(fillerSpace > 0){
		headerContainer.css({paddingBottom:fillerSpace+'px'});
	}
}


///////////////////////
//LARGE HEADER FUNCTION
///////////////////////
function largeDisplay(){

	headerContainer.css({paddingBottom:'0px'});
	
	var	winHeight = jQuery(window).height(),
		topContentStuff = jQuery('#topContentStuff'),
		topContentPos = topContentStuff.offset().top + topContentStuff.outerHeight(true),
		fillerSpace = winHeight - topContentPos;
	
	headerContainer.css({paddingBottom:fillerSpace+'px'});
}


//////////////////////////
//DETERMINE HEADER DISPLAY
//////////////////////////
if(theBody.hasClass('large-display')) {
	largeDisplay();
} else {
	standardDisplay();
}


/////////////////////
//DEMO HEADER DISPLAY
/////////////////////
if(theBody.hasClass('standard-display')){
	//SET DEMO BUTTON TO WORK
	jQuery('#some-button').click(function(){
		theBody.toggleClass('large-display');
		
		if(theBody.hasClass('large-display')) {
			largeDisplay();
		} else {
			standardDisplay();
		}
	});
}


///////////////////////////////////
//ANIMATED STANDARD HEADER FUNCTION
///////////////////////////////////
/*
function deadSpaceAnimated(){
	var browserHeight = jQuery(window).height(),
		bodyHeight = theBody.height(),
		deadSpace = browserHeight - bodyHeight;

	if(deadSpace > 0){
		headerContainer.stop(true,true).animate({paddingBottom:deadSpace+'px'},1000,function(){
			//RUN FUNCTION
			autoLoadPosts();
		});
	} else {
		headerContainer.stop(true,true).animate({paddingBottom:'0px'},1000,function(){
			//RUN FUNCTION
			autoLoadPosts();
		});
	}
}
*/


///////////////
//SHOW GALLERY
///////////////
function showGallery(){
	jQuery('#attachmentGallery li, .gallery .gallery-item').each(function(){
		var thisItem = jQuery(this),
			i = thisItem.index();
		
		thisItem.stop(true,true).delay(i*100).animate({opacity:'1',left:'0'},500);
	});
}


///////////////////
//PARALLAX FUNCTION
///////////////////
function parallaxin(){
	if( !theBody.hasClass('ios-device') ) {
		var docScroll = theDocument.scrollTop(),
			docHeight = theDocument.height(),
			imageOpacity = 1 - ( (docScroll/docHeight) * 2);
		
		if( theBody.hasClass('panelOpen') || theBody.hasClass('standard-display') ){
			//header.css({'top':"0"});
			headerImages.css({'opacity':1});
		} else {
			//header.css({'top':docScroll * .25 +"px"});
			headerImages.css({'opacity':imageOpacity});	
		}
		
		headerImage.css({'top':docScroll * .15 +"px"});
	}
}

//////////////
//WINDOW STUFF
//////////////
theWindow.resize(function(){

	//IF RESPONSIVE MENU NOT ACTIVE - SHOW MENU
	if(!menuToggle.is(':visible')){
		menuContainer.show();
	}
	//IF RESPONSIVE MENU ACTIVE & OPEN - SHOW MENU
	if(menuToggle.is(':visible') && menuToggle.hasClass('opened')) {
		menuContainer.show();
	}
	//IF RESPONSIVE MENU ACTIVE & CLOSED - HIDE MENU
	if(menuToggle.is(':visible') && menuToggle.hasClass('closed')) {
		menuContainer.hide();
	} 
	
	//DETERMINE HEADER DISPLAY
	if(theBody.hasClass('large-display')) {
		largeDisplay();
	} else {
		standardDisplay();
	}
		
	//RUN FUNCTIONs
	widgetSpace();
	quoteContent();
	asideContent();
		
}).load(function(){

	//RUN FUNCTION
	showGallery();
	
	//DETERMINE HEADER DISPLAY
	if(theBody.hasClass('large-display')) {
		largeDisplay();
	} else {
		standardDisplay();
	}
	
}).scroll(function(){

	//REMOVE SCROLL DOWN
	if( theBody.hasClass('large-display') && !scrollDown.hasClass('fading-out') && scrollDown.length > 0 ) {
		scrollDown.addClass('fading-out');
		setTimeout(function() {
			scrollDown.remove();
		}, 300);
	}
});