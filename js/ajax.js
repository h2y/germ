var ajaxcontent = 'content';
var ajaxignore = ['#', '/wp-', '.pdf', '.zip', '.rar'];

var ajaxtrack_analytics = false;
var ajaxscroll_top = true;

var ajaxloading_code = '<div class="spinner"></div>';
var ajaxreloadDocumentReady = false;

var ajaxisLoad = false;
var ajaxstarted = false;

jQuery(document).ready(function() {
    ajaxloadPageInit("");
});


window.onpopstate = function() {
    //location.reload();
    var url = document.location.toString();
    if(ajaxstarted === true && ajaxcheck_ignore(url) ) {
        ajaxloadPage(url, true);
    }
};

function ajaxloadPageInit(scope) {
    jQuery(scope + "a").click(function(event) {
        if (ajaxcheck_ignore(this.href, this)) {
            event.preventDefault();

            document.title = 'Loading...';

            this.blur();

            try {
                ajaxclick_code(this);
            } catch (err) {}
            ajaxloadPage(this.href);
        }

        if(typeof(bodyChangeColor)==='function')
            bodyChangeColor();
    });

}

function ajaxloadPage(url, noPush, getData) {
    if (!ajaxisLoad) {
        if (ajaxscroll_top) {
            jQuery('html,body').animate({
                scrollTop: 0
            }, 1500);
        }
        ajaxisLoad = true;
        ajaxstarted = true;
        var nohttp = url.replace(/^https?:\/\//, ""),
            firstsla = nohttp.indexOf("/"),
            pathpos = url.indexOf(nohttp),
            path = url.substring(pathpos + firstsla);
            
        if(!noPush) {
            if(history.pushState) {
                var stateObj = {
                    foo: 1000 + Math.random() * 1001
                };
                history.pushState(stateObj, "", path);
            }
        }
        jQuery('body').append(ajaxloading_code);
        jQuery('#' + ajaxcontent).fadeTo("slow", 0.4, function() {
            jQuery('#' + ajaxcontent).fadeIn("slow", function() {
                jQuery.ajax({
                    type: "GET",
                    url: url,
                    data: getData,
                    cache: true,
                    dataType: "html",
                    success: function(data) {
                        ajaxisLoad = false;

                        var suzu = data.split('</title>', 2);
                        if (suzu.length >= 2) {
                            data = suzu[1];
                            var newTitle = suzu[0].split('<title>')[1];
                            //document.title = newTitle;
                            jQuery(document).attr('title', (jQuery("<div/>").html(newTitle).text()));
                        }

                        if (ajaxtrack_analytics) {
                            if (typeof _gaq !== "undefined") {
                                if (typeof getData === "undefined") {
                                    getData = "";
                                } else {
                                    getData = "?" + getData;
                                }
                                _gaq.push(['_trackPageview', path + getData]);
                            }
                        }
                        data = data.split('id="' + ajaxcontent + '"')[1];
                        if (!data) {
                            //该页面并非Germ的页面,无法写入当前页面中
                            location.href = url;
                            return;
                        }
                        data = data.substring(data.indexOf('>') + 1);
                        var depth = 1;
                        var output = '';

                        while (depth > 0) {
                            temp = data.split('</div>')[0];
                            i = 0;
                            pos = temp.indexOf("<div");
                            while (pos !== -1) {
                                i++;
                                pos = temp.indexOf("<div", pos + 1);
                            }
                            depth = depth + i - 1;
                            output = output + data.split('</div>')[0] + '</div>';
                            data = data.substring(data.indexOf('</div>') + 6);
                        }
                        document.getElementById(ajaxcontent).innerHTML = output;
                        jQuery('#' + ajaxcontent).css({position:'absolute',left:'20000px'}).show();
                        ajaxloadPageInit("#" + ajaxcontent + " ");

                        if (ajaxreloadDocumentReady) {
                            jQuery(document).trigger("ready");
                        }
                        try {
                            ajaxreload_code();
                        } catch (err) {}
                        jQuery('#' + ajaxcontent).hide().css({position:'',left:''}).fadeTo("slow", 1);
                        jQuery('.spinner').remove();
                    },
                    
                    error: function() {
                        document.location = url;
                    }
                });
            });
        });
    }
}

function ajaxcheck_ignore(url, dom) {
    //非本域
    if (dom && dom.href.indexOf(ajax.home) !== 0 && dom.href.indexOf('/') !== 0)
        return false;
    //target=_blank
    if (dom && dom.target === "_blank")
        return false;
    //黑名单
    for (var i in ajaxignore)
        if (url.indexOf(ajaxignore[i]) >= 0)
            return false;

    return true;
}

function ajaxreload_code() {
    if (jQuery('article.full-width').length)
        jQuery('#container').addClass('full-width');
    else
        jQuery('#container').removeClass('full-width');
        
    if(window.initgallary)
        window.initgallary();
    
    if (window.initSlim)
        window.initSlim();
    
    if(window.favorite_link_init)    
        window.favorite_link_init();
    
    if(window.add_views)
        window.add_views();

    if(window.jdenticon)
        window.jdenticon();

    if(window.comment_reply_buttons)
        window.comment_reply_buttons();
}


function ajaxclick_code(thiss) {
    jQuery('ul.nav li').each(function() {
        jQuery(this).removeClass('current-menu-item');
    });
    jQuery(thiss).parents('li').addClass('current-menu-item');
}

//背景变色
var body_width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
if(body_width>650) {
    var rndColor = 200;
    var bodyChangeColor = function() {
        rndColor += 60 + Math.floor(241*Math.random());
        if(rndColor>=360) rndColor-=360;
        document.body.style.backgroundColor = 'hsl('+rndColor+',20%,70%)';
    };
    bodyChangeColor();
}
