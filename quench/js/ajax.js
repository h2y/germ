var ajaxcontent = 'content';
var ajaxsearch_class = 'searchform';
var ajaxignore_string = new String('#, /wp-, .pdf, .zip, .rar'); 
var ajaxignore = ajaxignore_string.split(', ');

var ajaxtrack_analytics = false
var ajaxscroll_top = true
	
var ajaxloading_code = '<div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
var ajaxloading_error_code = '<div class="box"><p style="padding:20px;">出错啦，请刷新当前页面。</p></div>';
var ajaxreloadDocumentReady = false;

var ajaxisLoad = false;
var ajaxstarted = false;
var ajaxsearchPath = null;
var ajaxua = jQuery.browser;

jQuery(document).ready(function() {	
	ajaxloadPageInit("");
});


window.onpopstate = function(event) {
	//We now have a smart multi-ignore feature controlled by the admin panel
	if (ajaxstarted === true && ajaxcheck_ignore(document.location.toString()) == true) {	
		ajaxloadPage(document.location.toString(),1);
	}
};

function ajaxloadPageInit(scope){
	jQuery(scope + "a").click(function(event){
		//if its not an admin url, or doesnt contain #
		if (this.href.indexOf(home) >= 0 && ajaxcheck_ignore(this.href) == true){
			// stop default behaviour
			event.preventDefault();

			// remove click border
			this.blur();

			// get caption: either title or name attribute
			var caption = this.title || this.name || "";

			// get rel attribute for image groups
			var group = this.rel || false;

			//Load click code - pass reference.
			try {
				ajaxclick_code(this);
			} catch(err) {
			}

			// display the box for the elements href
			ajaxloadPage(this.href);
		}
	});
	
	jQuery('.' + ajaxsearch_class).each(function(index) {
		if (jQuery(this).attr("action")) {
			//Get the current action so we know where to submit to
			ajaxsearchPath = jQuery(this).attr("action");

			//bind our code to search submit, now we can load everything through ajax :)
			//jQuery('#searchform').name = 'searchform';
			jQuery(this).submit(function() {
				submitSearch(jQuery(this).serialize());
				return false;
			});
		} else {
		}
	});
	
	if (jQuery('.' + ajaxsearch_class).attr("action")) {} else {
	}
}

function ajaxloadPage(url, push, getData){

	if (!ajaxisLoad){
		if (ajaxscroll_top == true) {
			//Nicer Scroll to top - thanks Bram Perry
			jQuery('html,body').animate({scrollTop: 0}, 1500);
		}
		ajaxisLoad = true;
		
		//enable onpopstate
		ajaxstarted = true;
		
		//AJAX Load page and update address bar url! :)
		//get domain name...
		nohttp = url.replace("http://","").replace("https://","");
		firstsla = nohttp.indexOf("/");
		pathpos = url.indexOf(nohttp);
		path = url.substring(pathpos + firstsla);
		
		//Only do a history state if clicked on the page.
		if (push != 1) {
			//TODO: implement a method for IE
			if (typeof window.history.pushState == "function") {
				var stateObj = { foo: 1000 + Math.random()*1001 };
				history.pushState(stateObj, "ajax page loaded...", path);
			} else {
			}
		}
		
		if (!jQuery('#' + ajaxcontent)) {
		}
		jQuery('#' + ajaxcontent).append(ajaxloading_code);
		//start changing the page content.
		jQuery('#' + ajaxcontent).fadeTo("slow", 0.4,function() {
			//See the below - NEVER TRUST jQuery to sort ALL your problems - this breaks Ie7 + 8 :o
			//jQuery('#' + ajaxcontent).html(ajaxloading_code);
			
			//Nothing like good old pure JavaScript...
			//document.getElementById(ajaxcontent).innerHTML = ajaxloading_code;
			
			
			jQuery('#' + ajaxcontent).fadeIn("slow", function() {
				jQuery.ajax({
					type: "GET",
					url: url,
					data: getData,
					cache: false,
					dataType: "html",
					success: function(data) {
						ajaxisLoad = false;
						
						//get title attribute
						datax = data.split('<title>');
						titlesx = data.split('</title>');
						
						if (datax.length == 2 || titlesx.length == 2) {
							data = data.split('<title>')[1];
							titles = data.split('</title>')[0];
							
							//set the title?
							//after several months, I think this is the solution to fix &amp; issues
							jQuery(document).attr('title', (jQuery("<div/>").html(titles).text()));
						} else {
							
						}
						
						//Google analytics?
						if (ajaxtrack_analytics == true) {
							if(typeof _gaq != "undefined") {
								if (typeof getData == "undefined") {
									getData = "";
								} else {
									getData = "?" + getData;
								}
								_gaq.push(['_trackPageview', path + getData]);
							} else {
								
							}
						}
						
						///////////////////////////////////////////
						//  WE HAVE AN ADMIN PAGE NOW - GO THERE //
						///////////////////////////////////////////
                        
						
						try {
							ajaxdata_code(data);
						} catch(err) {
							
						}

						
						//get content
						data = data.split('id="' + ajaxcontent + '"')[1];
						data = data.substring(data.indexOf('>') + 1);
						var depth = 1;
						var output = '';
						
						while(depth > 0) {
							temp = data.split('</div>')[0];
							
							//count occurrences
							i = 0;
							pos = temp.indexOf("<div");
							while (pos != -1) {
								i++;
								pos = temp.indexOf("<div", pos + 1);
							}
							//end count
							depth=depth+i-1;
							output=output+data.split('</div>')[0] + '</div>';
							data = data.substring(data.indexOf('</div>') + 6);
						}

						//put the resulting html back into the page!
						
						//See the below - NEVER TRUST jQuery to sort ALL your problems - this breaks Ie7 + 8 :o
						//jQuery('#' + ajaxcontent).html(output);
						
						//Nothing like good old pure JavaScript...
						document.getElementById(ajaxcontent).innerHTML = output;

						//move content area so we cant see it.
						jQuery('#' + ajaxcontent).css("position", "absolute");
						jQuery('#' + ajaxcontent).css("left", "20000px");

						//show the content area
						jQuery('#' + ajaxcontent).show();

						//recall loader so that new URLS are captured.
						ajaxloadPageInit("#" + ajaxcontent + " ");
						
						if (ajaxreloadDocumentReady == true) {
							jQuery(document).trigger("ready");
						}
						
						///////////////////////////////////////////
						//  WE HAVE AN ADMIN PAGE NOW - GO THERE //
						///////////////////////////////////////////
						
						try {
							ajaxreload_code();
						} catch(err) {
							
							
							//we have to show something... + reset the position.
							//jQuery('#' + ajaxcontent).css("position", "");
							//jQuery('#' + ajaxcontent).css("left", "");
							//jQuery('#' + ajaxcontent).html('<br><br>There was an error loading the page.<br><br>');
						}

						//now hide it again and put the position back!
						jQuery('#' + ajaxcontent).hide();
						jQuery('#' + ajaxcontent).css("position", "");
						jQuery('#' + ajaxcontent).css("left", "");

						jQuery('#' + ajaxcontent).fadeTo("slow", 1, function() {});
					},
					error: function(jqXHR, textStatus, errorThrown) {
						//Would append this, but would not be good if this fired more than once!!
						ajaxisLoad = false;
						document.title = "Error loading requested page!";
						
						
						
						//See the below - NEVER TRUST jQuery to sort ALL your problems - this breaks Ie7 + 8 :o
						//jQuery('#' + ajaxcontent).html(ajaxloading_error_code);
						
						//Nothing like good old pure JavaScript...
						document.getElementById(ajaxcontent).innerHTML = ajaxloading_error_code;
					}
				});
			});
		});
	}
}

function submitSearch(param){
	if (!ajaxisLoad){
		ajaxloadPage(ajaxsearchPath, 0, param);
	}
}

function ajaxcheck_ignore(url) {
	for (var i in ajaxignore) {
		if (url.indexOf(ajaxignore[i]) >= 0) {
			return false;
		}
	}
	

	
	return true;
}

function ajaxreload_code() {
loadjplayer();
initgallary();
initSlim();
jQuery("img").unveil();
}

function ajaxclick_code(thiss) {
jQuery('ul.nav li').each(function() {
	jQuery(this).removeClass('current-menu-item');
});
jQuery(thiss).parents('li').addClass('current-menu-item');
}

function ajaxdata_code(dataa) {
}