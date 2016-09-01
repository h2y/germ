var ajaxcontent = 'content';
var ajaxsearch_class = 'searchform';
var ajaxignore_string = new String('#, /wp-, .pdf, .zip, .rar, /share');
var ajaxignore = ajaxignore_string.split(', ');

var ajaxtrack_analytics = false;
var ajaxscroll_top = true;

var ajaxloading_code = '<div class="spinner"></div>';
//var ajaxloading_code = '<div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
var ajaxloading_error_code = '<div class="box"><p style="padding:20px;">页面加载中......</p></div>';
var ajaxreloadDocumentReady = false;

var ajaxisLoad = false;
var ajaxstarted = false;
var ajaxsearchPath = null;
var ajaxua = jQuery.browser;

jQuery(document).ready(function() {
    ajaxloadPageInit("");
});


window.onpopstate = function(event) {
    if (ajaxstarted === true && ajaxcheck_ignore(document.location.toString()) == true) {
        ajaxloadPage(document.location.toString(), 1);
    }
};

function ajaxloadPageInit(scope) {
    jQuery(scope + "a").click(function(event) {
        if (ajaxcheck_ignore(this.href, this) == true) {
            event.preventDefault();

            this.blur();

            var caption = this.title || this.name || "";

            var group = this.rel || false;

            try {
                ajaxclick_code(this);
            } catch (err) {}
            ajaxloadPage(this.href);
        }

        bodyChangeColor();
    });

    jQuery('.' + ajaxsearch_class).each(function(index) {
        if (jQuery(this).attr("action")) {
            ajaxsearchPath = jQuery(this).attr("action");;
            jQuery(this).submit(function() {
                submitSearch(jQuery(this).serialize());
                return false;
            });
        }
    });

    jQuery('.' + ajaxsearch_class).attr("action");
}

function ajaxloadPage(url, push, getData) {

    if (!ajaxisLoad) {
        if (ajaxscroll_top == true) {
            jQuery('html,body').animate({
                scrollTop: 0
            }, 1500);
        }
        ajaxisLoad = true;
        ajaxstarted = true;
        nohttp = url.replace("http://", "").replace("https://", "");
        firstsla = nohttp.indexOf("/");
        pathpos = url.indexOf(nohttp);
        path = url.substring(pathpos + firstsla);

        if (push != 1) {
            if (typeof window.history.pushState == "function") {
                var stateObj = {
                    foo: 1000 + Math.random() * 1001
                };
                history.pushState(stateObj, "ajax page loaded...", path);
            }
        }
        jQuery('body').append(ajaxloading_code);
        jQuery('#' + ajaxcontent).fadeTo("slow", 0.4, function() {
            jQuery('#' + ajaxcontent).fadeIn("slow", function() {
                jQuery.ajax({
                    type: "GET",
                    url: url,
                    data: getData,
                    cache: false,
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

                        if (ajaxtrack_analytics == true) {
                            if (typeof _gaq != "undefined") {
                                if (typeof getData == "undefined") {
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
                            while (pos != -1) {
                                i++;
                                pos = temp.indexOf("<div", pos + 1);
                            }
                            depth = depth + i - 1;
                            output = output + data.split('</div>')[0] + '</div>';
                            data = data.substring(data.indexOf('</div>') + 6);
                        }
                        document.getElementById(ajaxcontent).innerHTML = output;
                        jQuery('#' + ajaxcontent).css("position", "absolute");
                        jQuery('#' + ajaxcontent).css("left", "20000px");
                        jQuery('#' + ajaxcontent).show();
                        ajaxloadPageInit("#" + ajaxcontent + " ");

                        if (ajaxreloadDocumentReady == true) {
                            jQuery(document).trigger("ready");
                        }
                        try {
                            ajaxreload_code();
                        } catch (err) {}
                        jQuery('#' + ajaxcontent).hide();
                        jQuery('#' + ajaxcontent).css("position", "");
                        jQuery('#' + ajaxcontent).css("left", "");
                        jQuery('#' + ajaxcontent).fadeTo("slow", 1, function() {});
                        jQuery('.spinner').remove();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ajaxisLoad = false;
                        document.title = "Loading...";
                        document.getElementById(ajaxcontent).innerHTML = ajaxloading_error_code;
                        document.location.reload();
                    }
                });
            });
        });
    }
}

function submitSearch(param) {
    if (!ajaxisLoad) {
        ajaxloadPage(ajaxsearchPath, 0, param);
    }
}

function ajaxcheck_ignore(url, dom) {
    //特殊情况（其他链接访问到该页）
    if(location.href.indexOf(ajax.home) !== 0)
        return false;
    //非本域
    if (dom.href.indexOf(ajax.home) !== 0 && dom.href.indexOf('/') !== 0)
        return false;
    //target=_blank
    if (dom.target === "_blank")
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
    if (typeof(text_autospace_init) === "function")
        text_autospace_init();
    initgallary();
    if (typeof(initSlim) === "function")
        initSlim();
    refresh_qrimg();
    add_views();
}

function ajaxclick_code(thiss) {
    jQuery('ul.nav li').each(function() {
        jQuery(this).removeClass('current-menu-item');
    });
    jQuery(thiss).parents('li').addClass('current-menu-item');
}

//背景变色
var bodyChangeColor = function(){};
var body_width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
if(body_width>650) {
    var rndColor = 200;
    bodyChangeColor = function() {
        rndColor += 60 + Math.floor(241*Math.random());
        if(rndColor>=360) rndColor-=360;
        document.body.style.backgroundColor = 'hsl('+rndColor+',20%,70%)';
        //setTimeout(changeColor, 15000);
    };
    bodyChangeColor();
}
