window.$ = jQuery;


window.favorite_link_init = function() {
    jQuery(".favorite").click(function() {
        var rateHolder = jQuery(this).children('.love-count'),
            heart = jQuery(this).children('.fa');
        if (jQuery(this).hasClass('done')) {
            rateHolder.html('已经点过赞啦');
            heart.hide().fadeIn(500);
            return false;
        } else {
            jQuery(this).addClass('done');
            heart.hide().fadeIn(700);
            rateHolder.hide().fadeIn(300);
            var num = +rateHolder.html();
            rateHolder.html('谢谢喜欢');
            setTimeout(function(){ rateHolder.html(num+1); }, 2000);

            var id = jQuery(this).data("id"),
                action = jQuery(this).data('action');
            var ajax_data = {
                action: "mzw_like",
                um_id: id,
                um_action: action
            };
            jQuery.post(window.ajax.ajax_url, ajax_data);
            return false;
        }
    });
};
window.favorite_link_init();


//网站描述 打字效果
jQuery.fn.typing = function(n) {
    var _this = $(this);
    var rnd_saying = _this.hasClass('saying-title');
    if(!rnd_saying) return;

    var options = {
        speed: 200,
        delSpeed: 100,
        show: 5000
    };
    $.extend(options, n);
    var index = 1;
    var direction = 1;
    _this.css('border-right', '1px solid #000');

    var text = $(this).text().trim(),
        text2 = text;

    setTimeout(init, options.speed);
    function init() {
        _this.html(text.substr(0, index));
        
        if (index >= text.length) {
            direction = -1;
            setTimeout(init, options.show);
        } else if (index <= 0) {
            direction = 1;
            setTimeout(init, options.speed);
            if(rnd_saying && text2!=text) {
                text = text2;
                refresh_saying();
            }
        } else if(direction>0)
            setTimeout(init, options.speed);
        else
            setTimeout(init, options.delSpeed);

        index += direction;
    }

    refresh_saying();
    function refresh_saying() {
        $.get('https://api.hzy.pw/saying/v1/ciba', function(json){
            text2 = json.cnFix;
        })
        .fail(function() {
            setTimeout(refresh_saying, 2000);
        });
    }
};

if($(window).width()>650)
    $('#header .desc span').typing({show: 3000});


jQuery(document).ready(function($) {

    //顶部二级菜单展开
    $('#main-nav li.menu-item-has-children').hover(function() {
        $(this).find('.sub-menu').stop().show('normal');
    }, function() {
        $(this).find('.sub-menu').stop().hide('normal');
    });

    var $commentform = $('#commentform'),
        txt1 = '<div id="loading"><i class="fa fa-circle-o-notch fa-spin"></i> 正在提交, 请稍候...</div>',
        txt2 = '<div id="error">#</div>',
        txt3 = '">提交成功',
        edt1 = ', 刷新页面之前可以<a rel="nofollow" class="comment-reply-link" href="#edit" onclick=\'return addComment.moveForm("',
        edt2 = ')\'>重新编辑</a>',
        cancel_edit = '取消编辑',
        edit,
        num = 1,
        $comments = $('#comments-title span'),
        $cancel = $('#cancel-comment-reply-link'),
        cancel_text = $cancel.text(),
        $submit = $('#commentform #submit');
    $submit.attr('disabled', false),
        $body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body'),
        comm_array = [];
    comm_array.push('');
    $('#comment').after(txt1 + txt2);
    $('#loading').hide();
    $('#error').hide();
    $(document).on("submit", "#commentform",
        function() {
            if (edit) $('#comment').after('<input type="text" name="edit_id" id="edit_id" value="' + edit + '" style="display:none;" />');
            editcode();
            $submit.attr('disabled', true).fadeTo('slow', 0.5);
            $('#loading').slideDown();
            $.ajax({
                url: ajax.ajax_url,
                data: $(this).serialize() + "&action=ajax_comment",
                type: $(this).attr('method'),
                error: function(request) {
                    $('#loading').hide();
                    $("#error").slideDown().html(request.responseText);
                    setTimeout(function() {
                            $submit.attr('disabled', false).fadeTo('slow', 1);
                            $('#error').slideUp();
                        },
                        3000);
                },
                success: function(data) {
                    $('#loading').hide();
                    comm_array.push($('#comment').val());
                    $('textarea').each(function() {
                        this.value = ''
                    });
                    var t = addComment,
                        cancel = t.I('cancel-comment-reply-link'),
                        temp = t.I('wp-temp-form-div'),
                        respond = t.I(t.respondId),
                        post = t.I('comment_post_ID').value,
                        parent = t.I('comment_parent').value;
                    if (!edit && $comments.length) {
                        n = parseInt($comments.text().match(/\d+/));
                        $comments.text($comments.text().replace(n, n + 1));
                    }
                    new_htm = '" id="new_comm_' + num + '"></';
                    new_htm = (parent == '0') ? ('\n<ol style="clear:both;" class="commentlist' + new_htm + 'ol>') : ('\n<ul class="children' + new_htm + 'ul>');
                    ok_htm = '\n<div class="ajax-notice" id="success_' + num + txt3;
                    div_ = (document.body.innerHTML.indexOf('div-comment-') == -1) ? '' : ((document.body.innerHTML.indexOf('li-comment-') == -1) ? 'div-' : '');
                    ok_htm = ok_htm.concat(edt1, div_, 'comment-', parent, '", "', parent, '", "respond", "', post, '", ', num, edt2);
                    ok_htm += '</span><span></span>\n';
                    ok_htm += '</div>\n';
                    $('#respond').before(new_htm);
                    $('#new_comm_' + num).append(data);
                    $('#new_comm_' + num + ' li').append(ok_htm);
                    $body.animate({
                            scrollTop: $('#new_comm_' + num).offset().top - 200
                        },
                        900);
                    countdown();
                    num++;
                    edit = '';
                    $('*').remove('#edit_id');
                    cancel.style.display = 'none';
                    cancel.onclick = null;
                    t.I('comment_parent').value = '0';
                    if (temp && respond) {
                        temp.parentNode.insertBefore(respond, temp);
                        temp.parentNode.removeChild(temp)
                    }
                }
            });
            return false;
        });
    addComment = {
        moveForm: function(commId, parentId, respondId, postId, num) {
            var t = this,
                div,
                comm = t.I(commId),
                respond = t.I(respondId),
                cancel = t.I('cancel-comment-reply-link'),
                parent = t.I('comment_parent'),
                post = t.I('comment_post_ID');
            if (edit) exit_prev_edit();
            num ? (t.I('comment').value = comm_array[num], edit = t.I('new_comm_' + num).innerHTML.match(/(comment-)(\d+)/)[2], $new_sucs = $('#success_' + num), $new_sucs.hide(), $new_comm = $('#new_comm_' + num), $new_comm.hide(), $cancel.text(cancel_edit)) : $cancel.text(cancel_text);
            t.respondId = respondId;
            postId = postId || false;
            if (!t.I('wp-temp-form-div')) {
                div = document.createElement('div');
                div.id = 'wp-temp-form-div';
                div.style.display = 'none';
                respond.parentNode.insertBefore(div, respond)
            }!comm ? (temp = t.I('wp-temp-form-div'), t.I('comment_parent').value = '0', temp.parentNode.insertBefore(respond, temp), temp.parentNode.removeChild(temp)) : comm.parentNode.insertBefore(respond, comm.nextSibling);
            $body.animate({
                    scrollTop: $('#respond').offset().top - 180
                },
                400);
            if (post && postId) post.value = postId;
            parent.value = parentId;
            cancel.style.display = '';
            cancel.onclick = function() {
                if (edit) exit_prev_edit();
                var t = addComment,
                    temp = t.I('wp-temp-form-div'),
                    respond = t.I(t.respondId);
                t.I('comment_parent').value = '0';
                if (temp && respond) {
                    temp.parentNode.insertBefore(respond, temp);
                    temp.parentNode.removeChild(temp);
                }
                this.style.display = 'none';
                this.onclick = null;
                return false;
            };
            try {
                t.I('comment').focus();
            } catch (e) {}
            return false;
        },
        I: function(e) {
            return document.getElementById(e);
        }
    };

    function exit_prev_edit() {
        $new_comm.show();
        $new_sucs.show();
        $('textarea').each(function() {
            this.value = ''
        });
        edit = '';
    }
    var wait = 15,
        submit_val = $submit.val();

    function countdown() {
        if (wait > 0) {
            $submit.val(wait);
            wait--;
            setTimeout(countdown, 1000);
        } else {
            $submit.val(submit_val).attr('disabled', false).fadeTo('slow', 1);
            wait = 15;
        }
    }

    function editcode() {
        var a = "",
            b = $("#comment").val(),
            start = b.indexOf("<code>"),
            end = b.indexOf("</code>");
        if (start > -1 && end > -1 && start < end) {
            a = "";
            while (end != -1) {
                a += b.substring(0, start + 6) + b.substring(start + 6, end).replace(/<(?=[^>]*?>)/gi, "&lt;").replace(/>/gi, "&gt;");
                b = b.substring(end + 7, b.length);
                start = b.indexOf("<code>") == -1 ? -6 : b.indexOf("<code>");
                end = b.indexOf("</code>");
                if (end == -1) {
                    a += "</code>" + b;
                    $("#comment").val(a)
                } else if (start == -6) {
                    myFielde += "&lt;/code&gt;"
                } else {
                    a += "</code>"
                }
            }
        }
        var b = a ? a : $("#comment").val(),
            a = "",
            start = b.indexOf("<pre>"),
            end = b.indexOf("</pre>");
        if (start > -1 && end > -1 && start < end) {
            a = a
        } else return;
        while (end != -1) {
            a += b.substring(0, start + 5) + b.substring(start + 5, end).replace(/<(?=[^>]*?>)/gi, "&lt;").replace(/>/gi, "&gt;");
            b = b.substring(end + 6, b.length);
            start = b.indexOf("<pre>") == -1 ? -5 : b.indexOf("<pre>");
            end = b.indexOf("</pre>");
            if (end == -1) {
                a += "</pre>" + b;
                $("#comment").val(a)
            } else if (start == -5) {
                myFielde += "&lt;/pre&gt;"
            } else {
                a += "</pre>"
            }
        }
    }

    function grin(a) {
        var b;
        a = " " + a + " ";
        if (document.getElementById("comment") && document.getElementById("comment").type == "textarea") {
            b = document.getElementById("comment")
        } else {
            return false
        }
        if (document.selection) {
            b.focus();
            sel = document.selection.createRange();
            sel.text = a;
            b.focus()
        } else if (b.selectionStart || b.selectionStart == "0") {
            var c = b.selectionStart;
            var d = b.selectionEnd;
            var e = d;
            b.value = b.value.substring(0, c) + a + b.value.substring(d, b.value.length);
            e += a.length;
            b.focus();
            b.selectionStart = e;
            b.selectionEnd = e
        } else {
            b.value += a;
            b.focus()
        }
    }
});

$(document).on("click", ".post-share>a", function(e) {
    e.preventDefault();
    if ($(this).parent().hasClass('share-on')) {
        $(this).parent().removeClass('share-on')
        $(this).next().hide();
    } else {
        $(this).parent().addClass('share-on')
        $(this).next().show();
    }
    return false;
});
$(document).on("click", ".post-share ul li a", function(e) {
    $(this).parent().parent().parent().removeClass('share-on')
    $(this).parent().parent().hide();
});

var ajaxBinded = false;

$(document).ready(function(e) {
    initgallary();
    refresh_qrimg();
    $('#qr').hover(function() {
        $('#qrimg').stop().fadeIn('normal');
    }, function() {
        $('#qrimg').stop().fadeOut('normal');
    });
});

function refresh_qrimg() {
    $('#qrimg').attr('src', 'https://api.lwl12.com/img/qrcode/get?ct='+location.href+'&w=140&h=140');
}

function initgallary() {
    $('.flexslider').flexslider({
        animation: "slide",
        controlNav: "thumbnails",
        smoothHeight: true,
        touch: true
    });
}


jQuery(window).scroll(function() {
    jQuery(this).scrollTop() > 100 ? jQuery("#gotop").css({
        bottom: "110px"
    }) : jQuery("#gotop").css({
        bottom: "-110px"
    })
});
jQuery("#gotop").click(function() {
    return jQuery("body,html").animate({
        scrollTop: 0
    }, 800), !1
});

//侧边栏悬停
var rollbox = $('#sidebar .widget'),
    rolllen = rollbox.length;
var asr_1 = parseInt(ajax.fly1),
    asr_2 = parseInt(ajax.fly2);
if (asr_1 !== -24 && asr_2 !== -38 && rolllen !== 0 && $('#sidebar').css('display') !== "none") {
    var sidebar_flying = false;
    $(window).scroll(function() {
        var roll = document.documentElement.scrollTop + document.body.scrollTop;
        if (roll > rollbox.eq(rolllen - 1).offset().top + rollbox.eq(rolllen - 1).height()) {
            if ($('.widgetRoller').length == 0) {
                rollbox.parent().append('<aside class="widgetRoller"></aside>');
                rollbox.eq(asr_1 - 1).clone().appendTo('.widgetRoller');
                if (asr_1 !== asr_2)
                    rollbox.eq(asr_2 - 1).clone().appendTo('.widgetRoller')
                $('.widgetRoller').css({
                    position: 'fixed',
                    top: 10,
                    display: 'none'/*,
                    width: $('#sidebar').width()*/
                });
            }
            if (!sidebar_flying)
                $('.widgetRoller').fadeIn('normal');
            sidebar_flying = true;
        } else {
            if (sidebar_flying)
                $('.widgetRoller').fadeOut('normal');
            sidebar_flying = false;
        }
    })
};

$(document).on("click", ".commentnav a", function() {
    var baseUrl = $(this).attr("href"),
        commentsHolder = $(".commentshow"),
        id = $(this).parent().data("postid"),
        page = 1,
        concelLink = $("#cancel-comment-reply-link");
    /comment-page-/i.test(baseUrl) ? page = baseUrl.split(/comment-page-/i)[1].split(/(\/|#|&).*jQuery/)[0] : /cpage=/i.test(baseUrl) && (page = baseUrl.split(/cpage=/)[1].split(/(\/|#|&).*jQuery/)[0]);
    concelLink.click();
    page = page.split('#')[0];
    var ajax_data = {
        action: "ajax_comment_page_nav",
        um_post: id,
        um_page: page
    };
    commentsHolder.html('<div>loading..</div>');
    jQuery("body, html").animate({
            scrollTop: commentsHolder.offset().top - 150
        },
        1e3);
    //add loading
    jQuery.post(ajax.ajax_url, ajax_data,
        function(data) {
            commentsHolder.html(data);
            //remove loading
            $("body, html").animate({
                scrollTop: commentsHolder.offset().top - 50
            }, 'normal');
        });
    return false;
});


//开关手机边栏
jQuery(document).on("click", ".open-nav", function() {
    var $body = $(document.body);
    if ($body.hasClass('has-opened')) {
        $body.removeClass('has-opened');
        $('#mobile-nav').hide('fast');
    } else {
        $('#mobile-nav').show('fast');
        $body.addClass('has-opened');
    }
});

//关闭手机边栏
function closeMobileSidebar() {
    $('body').removeClass('has-opened');
    $('#mobile-nav').hide('fast');
}
jQuery(document).on("touchstart", ".has-opened #wrap", closeMobileSidebar);
jQuery(document).on("click", "#mobile-nav li>a[href]", closeMobileSidebar);


jQuery(document).ready(function($) {
    jQuery('.archives ul.archives-monthlisting').hide();
    jQuery('.archives ul.archives-monthlisting:first').show();
    //归档页面的开关
    jQuery('.archives .m-title').click(function() {
        jQuery(this).next().slideToggle('fast');
        return false;
    });
    add_views();
});

//AJAX增加访问量
function add_views() {
    var tmp = jQuery('#content>div>article');
    if (tmp.length != 1)
        return;
    tmp = tmp.attr('class');

    var suzu = tmp.match(/post-([0-9]*)/);
    if (suzu === null || suzu.length < 2)
        return;
    var post_ID = suzu[1];

    var data = {
        'action': 'add_views',
        'post_ID': post_ID
    };
    jQuery.post(ajax.ajax_url, data);
}


//页底随机名言
var $saying = $('#footer .saying-bottom');
var saying_refresh = function() {
    $saying.html('<i class="fa fa-circle-o-notch fa-spin"></i> Refrshing...');
    $.get('https://api.hzy.pw/saying/v1/ciba', function(json) {
        var html = '<i class="fa fa-paw" aria-hidden="true"></i> ' + json.cn;
        $saying.hide().html(html).attr('title', json.en).fadeIn(1200);
    });
};
if($saying.length) {
    saying_refresh();
    $saying.click(saying_refresh);
}
