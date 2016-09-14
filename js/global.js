window.$ = jQuery;

/*
 * jQuery FlexSlider v2.6.0
 * Copyright 2012 WooThemes
 * Contributing Author: Tyler Smith
 */
(function($){var focused=true;$.flexslider=function(e,o){var b=$(e);b.vars=$.extend({},$.flexslider.defaults,o);var i=b.vars.namespace,d=window.navigator&&window.navigator.msPointerEnabled&&window.MSGesture,j=("ontouchstart" in window||d||window.DocumentTouch&&document instanceof DocumentTouch)&&b.vars.touch,c="click touchend MSPointerUp keyup",a="",n,h=b.vars.direction==="vertical",k=b.vars.reverse,m=b.vars.itemWidth>0,g=b.vars.animation==="fade",l=b.vars.asNavFor!=="",f={};$.data(e,"flexslider",b);f={init:function(){b.animating=false;b.currentSlide=parseInt(b.vars.startAt?b.vars.startAt:0,10);if(isNaN(b.currentSlide)){b.currentSlide=0}b.animatingTo=b.currentSlide;b.atEnd=b.currentSlide===0||b.currentSlide===b.last;b.containerSelector=b.vars.selector.substr(0,b.vars.selector.search(" "));b.slides=$(b.vars.selector,b);b.container=$(b.containerSelector,b);b.count=b.slides.length;b.syncExists=$(b.vars.sync).length>0;if(b.vars.animation==="slide"){b.vars.animation="swing"}b.prop=h?"top":"marginLeft";b.args={};b.manualPause=false;b.stopped=false;b.started=false;b.startTimeout=null;b.transitions=!b.vars.video&&!g&&b.vars.useCSS&&function(){var r=document.createElement("div"),q=["perspectiveProperty","WebkitPerspective","MozPerspective","OPerspective","msPerspective"];for(var p in q){if(r.style[q[p]]!==undefined){b.pfx=q[p].replace("Perspective","").toLowerCase();b.prop="-"+b.pfx+"-transform";return true}}return false}();b.ensureAnimationEnd="";if(b.vars.controlsContainer!==""){b.controlsContainer=$(b.vars.controlsContainer).length>0&&$(b.vars.controlsContainer)}if(b.vars.manualControls!==""){b.manualControls=$(b.vars.manualControls).length>0&&$(b.vars.manualControls)}if(b.vars.customDirectionNav!==""){b.customDirectionNav=$(b.vars.customDirectionNav).length===2&&$(b.vars.customDirectionNav)}if(b.vars.randomize){b.slides.sort(function(){return Math.round(Math.random())-0.5});b.container.empty().append(b.slides)}b.doMath();b.setup("init");if(b.vars.controlNav){f.controlNav.setup()}if(b.vars.directionNav){f.directionNav.setup()}if(b.vars.keyboard&&($(b.containerSelector).length===1||b.vars.multipleKeyboard)){$(document).bind("keyup",function(q){var p=q.keyCode;if(!b.animating&&(p===39||p===37)){var r=p===39?b.getTarget("next"):p===37?b.getTarget("prev"):false;b.flexAnimate(r,b.vars.pauseOnAction)}})}if(b.vars.mousewheel){b.bind("mousewheel",function(r,t,q,p){r.preventDefault();var s=t<0?b.getTarget("next"):b.getTarget("prev");b.flexAnimate(s,b.vars.pauseOnAction)})}if(b.vars.pausePlay){f.pausePlay.setup()}if(b.vars.slideshow&&b.vars.pauseInvisible){f.pauseInvisible.init()}if(b.vars.slideshow){if(b.vars.pauseOnHover){b.hover(function(){if(!b.manualPlay&&!b.manualPause){b.pause()}},function(){if(!b.manualPause&&!b.manualPlay&&!b.stopped){b.play()}})}if(!b.vars.pauseInvisible||!f.pauseInvisible.isHidden()){b.vars.initDelay>0?b.startTimeout=setTimeout(b.play,b.vars.initDelay):b.play()}}if(l){f.asNav.setup()}if(j&&b.vars.touch){f.touch()}if(!g||g&&b.vars.smoothHeight){$(window).bind("resize orientationchange focus",f.resize)}b.find("img").attr("draggable","false");setTimeout(function(){b.vars.start(b)},200)},asNav:{setup:function(){b.asNav=true;b.animatingTo=Math.floor(b.currentSlide/b.move);b.currentItem=b.currentSlide;b.slides.removeClass(i+"active-slide").eq(b.currentItem).addClass(i+"active-slide");if(!d){b.slides.on(c,function(r){r.preventDefault();var q=$(this),p=q.index();var s=q.offset().left-$(b).scrollLeft();if(s<=0&&q.hasClass(i+"active-slide")){b.flexAnimate(b.getTarget("prev"),true)}else{if(!$(b.vars.asNavFor).data("flexslider").animating&&!q.hasClass(i+"active-slide")){b.direction=b.currentItem<p?"next":"prev";b.flexAnimate(p,b.vars.pauseOnAction,false,true,true)}}})}else{e._slider=b;b.slides.each(function(){var p=this;p._gesture=new MSGesture;p._gesture.target=p;p.addEventListener("MSPointerDown",function(q){q.preventDefault();if(q.currentTarget._gesture){q.currentTarget._gesture.addPointer(q.pointerId)}},false);p.addEventListener("MSGestureTap",function(s){s.preventDefault();var r=$(this),q=r.index();if(!$(b.vars.asNavFor).data("flexslider").animating&&!r.hasClass("active")){b.direction=b.currentItem<q?"next":"prev";b.flexAnimate(q,b.vars.pauseOnAction,false,true,true)}})})}}},controlNav:{setup:function(){if(!b.manualControls){f.controlNav.setupPaging()}else{f.controlNav.setupManual()}},setupPaging:function(){var s=b.vars.controlNav==="thumbnails"?"control-thumbs":"control-paging",q=1,t,p;b.controlNavScaffold=$('<ol class="'+i+"control-nav "+i+s+'"></ol>');if(b.pagingCount>1){for(var r=0;r<b.pagingCount;r++){p=b.slides.eq(r);if(undefined===p.attr("data-thumb-alt")){p.attr("data-thumb-alt","")}altText=""!==p.attr("data-thumb-alt")?altText=' alt="'+p.attr("data-thumb-alt")+'"':"";t=b.vars.controlNav==="thumbnails"?'<img src="'+p.attr("data-thumb")+'"'+altText+"/>":'<a href="#">'+q+"</a>";if("thumbnails"===b.vars.controlNav&&true===b.vars.thumbCaptions){var u=p.attr("data-thumbcaption");if(""!==u&&undefined!==u){t+='<span class="'+i+'caption">'+u+"</span>"}}b.controlNavScaffold.append("<li>"+t+"</li>");q++}}b.controlsContainer?$(b.controlsContainer).append(b.controlNavScaffold):b.append(b.controlNavScaffold);f.controlNav.set();f.controlNav.active();b.controlNavScaffold.delegate("a, img",c,function(v){v.preventDefault();if(a===""||a===v.type){var x=$(this),w=b.controlNav.index(x);if(!x.hasClass(i+"active")){b.direction=w>b.currentSlide?"next":"prev";b.flexAnimate(w,b.vars.pauseOnAction)}}if(a===""){a=v.type}f.setToClearWatchedEvent()})},setupManual:function(){b.controlNav=b.manualControls;f.controlNav.active();b.controlNav.bind(c,function(p){p.preventDefault();if(a===""||a===p.type){var r=$(this),q=b.controlNav.index(r);if(!r.hasClass(i+"active")){q>b.currentSlide?b.direction="next":b.direction="prev";b.flexAnimate(q,b.vars.pauseOnAction)}}if(a===""){a=p.type}f.setToClearWatchedEvent()})},set:function(){var p=b.vars.controlNav==="thumbnails"?"img":"a";b.controlNav=$("."+i+"control-nav li "+p,b.controlsContainer?b.controlsContainer:b)},active:function(){b.controlNav.removeClass(i+"active").eq(b.animatingTo).addClass(i+"active")},update:function(p,q){if(b.pagingCount>1&&p==="add"){b.controlNavScaffold.append($('<li><a href="#">'+b.count+"</a></li>"))}else{if(b.pagingCount===1){b.controlNavScaffold.find("li").remove()}else{b.controlNav.eq(q).closest("li").remove()}}f.controlNav.set();b.pagingCount>1&&b.pagingCount!==b.controlNav.length?b.update(q,p):f.controlNav.active()}},directionNav:{setup:function(){var p=$('<ul class="'+i+'direction-nav"><li class="'+i+'nav-prev"><a class="'+i+'prev" href="#">'+b.vars.prevText+'</a></li><li class="'+i+'nav-next"><a class="'+i+'next" href="#">'+b.vars.nextText+"</a></li></ul>");if(b.customDirectionNav){b.directionNav=b.customDirectionNav}else{if(b.controlsContainer){$(b.controlsContainer).append(p);b.directionNav=$("."+i+"direction-nav li a",b.controlsContainer)}else{b.append(p);b.directionNav=$("."+i+"direction-nav li a",b)}}f.directionNav.update();b.directionNav.bind(c,function(q){q.preventDefault();var r;if(a===""||a===q.type){r=$(this).hasClass(i+"next")?b.getTarget("next"):b.getTarget("prev");b.flexAnimate(r,b.vars.pauseOnAction)}if(a===""){a=q.type}f.setToClearWatchedEvent()})},update:function(){var p=i+"disabled";if(b.pagingCount===1){b.directionNav.addClass(p).attr("tabindex","-1")}else{if(!b.vars.animationLoop){if(b.animatingTo===0){b.directionNav.removeClass(p).filter("."+i+"prev").addClass(p).attr("tabindex","-1")}else{if(b.animatingTo===b.last){b.directionNav.removeClass(p).filter("."+i+"next").addClass(p).attr("tabindex","-1")}else{b.directionNav.removeClass(p).removeAttr("tabindex")}}}else{b.directionNav.removeClass(p).removeAttr("tabindex")}}}},pausePlay:{setup:function(){var p=$('<div class="'+i+'pauseplay"><a href="#"></a></div>');if(b.controlsContainer){b.controlsContainer.append(p);b.pausePlay=$("."+i+"pauseplay a",b.controlsContainer)}else{b.append(p);b.pausePlay=$("."+i+"pauseplay a",b)}f.pausePlay.update(b.vars.slideshow?i+"pause":i+"play");b.pausePlay.bind(c,function(q){q.preventDefault();if(a===""||a===q.type){if($(this).hasClass(i+"pause")){b.manualPause=true;b.manualPlay=false;b.pause()}else{b.manualPause=false;b.manualPlay=true;b.play()}}if(a===""){a=q.type}f.setToClearWatchedEvent()})},update:function(p){p==="play"?b.pausePlay.removeClass(i+"pause").addClass(i+"play").html(b.vars.playText):b.pausePlay.removeClass(i+"play").addClass(i+"pause").html(b.vars.pauseText)}},touch:function(){var A,x,v,B,E,C,w,q,D,z=false,s=0,r=0,u=0;if(!d){w=function(F){if(b.animating){F.preventDefault()}else{if(window.navigator.msPointerEnabled||F.touches.length===1){b.pause();B=h?b.h:b.w;C=Number(new Date);s=F.touches[0].pageX;r=F.touches[0].pageY;v=m&&k&&b.animatingTo===b.last?0:m&&k?b.limit-(b.itemW+b.vars.itemMargin)*b.move*b.animatingTo:m&&b.currentSlide===b.last?b.limit:m?(b.itemW+b.vars.itemMargin)*b.move*b.currentSlide:k?(b.last-b.currentSlide+b.cloneOffset)*B:(b.currentSlide+b.cloneOffset)*B;A=h?r:s;x=h?s:r;e.addEventListener("touchmove",q,false);e.addEventListener("touchend",D,false)}}};q=function(F){s=F.touches[0].pageX;r=F.touches[0].pageY;E=h?A-r:A-s;z=h?Math.abs(E)<Math.abs(s-x):Math.abs(E)<Math.abs(r-x);var G=500;if(!z||Number(new Date)-C>G){F.preventDefault();if(!g&&b.transitions){if(!b.vars.animationLoop){E=E/(b.currentSlide===0&&E<0||b.currentSlide===b.last&&E>0?Math.abs(E)/B+2:1)}b.setProps(v+E,"setTouch")}}};D=function(H){e.removeEventListener("touchmove",q,false);if(b.animatingTo===b.currentSlide&&!z&&!(E===null)){var G=k?-E:E,F=G>0?b.getTarget("next"):b.getTarget("prev");if(b.canAdvance(F)&&(Number(new Date)-C<550&&Math.abs(G)>50||Math.abs(G)>B/2)){b.flexAnimate(F,b.vars.pauseOnAction)}else{if(!g){b.flexAnimate(b.currentSlide,b.vars.pauseOnAction,true)}}}e.removeEventListener("touchend",D,false);A=null;x=null;E=null;v=null};e.addEventListener("touchstart",w,false)}else{e.style.msTouchAction="none";e._gesture=new MSGesture;e._gesture.target=e;e.addEventListener("MSPointerDown",p,false);e._slider=b;e.addEventListener("MSGestureChange",y,false);e.addEventListener("MSGestureEnd",t,false);function p(F){F.stopPropagation();if(b.animating){F.preventDefault()}else{b.pause();e._gesture.addPointer(F.pointerId);u=0;B=h?b.h:b.w;C=Number(new Date);v=m&&k&&b.animatingTo===b.last?0:m&&k?b.limit-(b.itemW+b.vars.itemMargin)*b.move*b.animatingTo:m&&b.currentSlide===b.last?b.limit:m?(b.itemW+b.vars.itemMargin)*b.move*b.currentSlide:k?(b.last-b.currentSlide+b.cloneOffset)*B:(b.currentSlide+b.cloneOffset)*B}}function y(I){I.stopPropagation();var H=I.target._slider;if(!H){return}var G=-I.translationX,F=-I.translationY;u=u+(h?F:G);E=u;z=h?Math.abs(u)<Math.abs(-G):Math.abs(u)<Math.abs(-F);if(I.detail===I.MSGESTURE_FLAG_INERTIA){setImmediate(function(){e._gesture.stop()});return}if(!z||Number(new Date)-C>500){I.preventDefault();if(!g&&H.transitions){if(!H.vars.animationLoop){E=u/(H.currentSlide===0&&u<0||H.currentSlide===H.last&&u>0?Math.abs(u)/B+2:1)}H.setProps(v+E,"setTouch")}}}function t(I){I.stopPropagation();var F=I.target._slider;if(!F){return}if(F.animatingTo===F.currentSlide&&!z&&!(E===null)){var H=k?-E:E,G=H>0?F.getTarget("next"):F.getTarget("prev");if(F.canAdvance(G)&&(Number(new Date)-C<550&&Math.abs(H)>50||Math.abs(H)>B/2)){F.flexAnimate(G,F.vars.pauseOnAction)}else{if(!g){F.flexAnimate(F.currentSlide,F.vars.pauseOnAction,true)}}}A=null;x=null;E=null;v=null;u=0}}},resize:function(){if(!b.animating&&b.is(":visible")){if(!m){b.doMath()}if(g){f.smoothHeight()}else{if(m){b.slides.width(b.computedW);b.update(b.pagingCount);b.setProps()}else{if(h){b.viewport.height(b.h);b.setProps(b.h,"setTotal")}else{if(b.vars.smoothHeight){f.smoothHeight()}b.newSlides.width(b.computedW);b.setProps(b.computedW,"setTotal")}}}}},smoothHeight:function(p){if(!h||g){var q=g?b:b.viewport;p?q.animate({height:b.slides.eq(b.animatingTo).height()},p):q.height(b.slides.eq(b.animatingTo).height())}},sync:function(p){var r=$(b.vars.sync).data("flexslider"),q=b.animatingTo;switch(p){case"animate":r.flexAnimate(q,b.vars.pauseOnAction,false,true);break;case"play":if(!r.playing&&!r.asNav){r.play()}break;case"pause":r.pause();break}},uniqueID:function(p){p.filter("[id]").add(p.find("[id]")).each(function(){var q=$(this);q.attr("id",q.attr("id")+"_clone")});return p},pauseInvisible:{visProp:null,init:function(){var q=f.pauseInvisible.getHiddenProp();if(q){var p=q.replace(/[H|h]idden/,"")+"visibilitychange";document.addEventListener(p,function(){if(f.pauseInvisible.isHidden()){if(b.startTimeout){clearTimeout(b.startTimeout)}else{b.pause()}}else{if(b.started){b.play()}else{if(b.vars.initDelay>0){setTimeout(b.play,b.vars.initDelay)}else{b.play()}}}})}},isHidden:function(){var p=f.pauseInvisible.getHiddenProp();if(!p){return false}return document[p]},getHiddenProp:function(){var q=["webkit","moz","ms","o"];if("hidden" in document){return"hidden"}for(var p=0;p<q.length;p++){if(q[p]+"Hidden" in document){return q[p]+"Hidden"}}return null}},setToClearWatchedEvent:function(){clearTimeout(n);n=setTimeout(function(){a=""},3000)}};b.flexAnimate=function(x,y,r,t,u){if(!b.vars.animationLoop&&x!==b.currentSlide){b.direction=x>b.currentSlide?"next":"prev"}if(l&&b.pagingCount===1){b.direction=b.currentItem<x?"next":"prev"}if(!b.animating&&(b.canAdvance(x,u)||r)&&b.is(":visible")){if(l&&t){var q=$(b.vars.asNavFor).data("flexslider");b.atEnd=x===0||x===b.count-1;q.flexAnimate(x,true,false,true,u);b.direction=b.currentItem<x?"next":"prev";q.direction=b.direction;if(Math.ceil((x+1)/b.visible)-1!==b.currentSlide&&x!==0){b.currentItem=x;b.slides.removeClass(i+"active-slide").eq(x).addClass(i+"active-slide");x=Math.floor(x/b.visible)}else{b.currentItem=x;b.slides.removeClass(i+"active-slide").eq(x).addClass(i+"active-slide");return false}}b.animating=true;b.animatingTo=x;if(y){b.pause()}b.vars.before(b);if(b.syncExists&&!u){f.sync("animate")}if(b.vars.controlNav){f.controlNav.active()}if(!m){b.slides.removeClass(i+"active-slide").eq(x).addClass(i+"active-slide")}b.atEnd=x===0||x===b.last;if(b.vars.directionNav){f.directionNav.update()}if(x===b.last){b.vars.end(b);if(!b.vars.animationLoop){b.pause()}}if(!g){var w=h?b.slides.filter(":first").height():b.computedW,v,s,p;if(m){v=b.vars.itemMargin;p=(b.itemW+v)*b.move*b.animatingTo;s=p>b.limit&&b.visible!==1?b.limit:p}else{if(b.currentSlide===0&&x===b.count-1&&b.vars.animationLoop&&b.direction!=="next"){s=k?(b.count+b.cloneOffset)*w:0}else{if(b.currentSlide===b.last&&x===0&&b.vars.animationLoop&&b.direction!=="prev"){s=k?0:(b.count+1)*w}else{s=k?(b.count-1-x+b.cloneOffset)*w:(x+b.cloneOffset)*w}}}b.setProps(s,"",b.vars.animationSpeed);if(b.transitions){if(!b.vars.animationLoop||!b.atEnd){b.animating=false;b.currentSlide=b.animatingTo}b.container.unbind("webkitTransitionEnd transitionend");b.container.bind("webkitTransitionEnd transitionend",function(){clearTimeout(b.ensureAnimationEnd);b.wrapup(w)});clearTimeout(b.ensureAnimationEnd);b.ensureAnimationEnd=setTimeout(function(){b.wrapup(w)},b.vars.animationSpeed+100)}else{b.container.animate(b.args,b.vars.animationSpeed,b.vars.easing,function(){b.wrapup(w)})}}else{if(!j){b.slides.eq(b.currentSlide).css({zIndex:1}).animate({opacity:0},b.vars.animationSpeed,b.vars.easing);b.slides.eq(x).css({zIndex:2}).animate({opacity:1},b.vars.animationSpeed,b.vars.easing,b.wrapup)}else{b.slides.eq(b.currentSlide).css({opacity:0,zIndex:1});b.slides.eq(x).css({opacity:1,zIndex:2});b.wrapup(w)}}if(b.vars.smoothHeight){f.smoothHeight(b.vars.animationSpeed)}}};b.wrapup=function(p){if(!g&&!m){if(b.currentSlide===0&&b.animatingTo===b.last&&b.vars.animationLoop){b.setProps(p,"jumpEnd")}else{if(b.currentSlide===b.last&&b.animatingTo===0&&b.vars.animationLoop){b.setProps(p,"jumpStart")}}}b.animating=false;b.currentSlide=b.animatingTo;b.vars.after(b)};b.animateSlides=function(){if(!b.animating&&focused){b.flexAnimate(b.getTarget("next"))}};b.pause=function(){clearInterval(b.animatedSlides);b.animatedSlides=null;b.playing=false;if(b.vars.pausePlay){f.pausePlay.update("play")}if(b.syncExists){f.sync("pause")}};b.play=function(){if(b.playing){clearInterval(b.animatedSlides)}b.animatedSlides=b.animatedSlides||setInterval(b.animateSlides,b.vars.slideshowSpeed);b.started=b.playing=true;if(b.vars.pausePlay){f.pausePlay.update("pause")}if(b.syncExists){f.sync("play")}};b.stop=function(){b.pause();b.stopped=true};b.canAdvance=function(r,p){var q=l?b.pagingCount-1:b.last;return p?true:l&&b.currentItem===b.count-1&&r===0&&b.direction==="prev"?true:l&&b.currentItem===0&&r===b.pagingCount-1&&b.direction!=="next"?false:r===b.currentSlide&&!l?false:b.vars.animationLoop?true:b.atEnd&&b.currentSlide===0&&r===q&&b.direction!=="next"?false:b.atEnd&&b.currentSlide===q&&r===0&&b.direction==="next"?false:true};b.getTarget=function(p){b.direction=p;if(p==="next"){return b.currentSlide===b.last?0:b.currentSlide+1}else{return b.currentSlide===0?b.last:b.currentSlide-1}};b.setProps=function(s,p,q){var r=function(){var t=s?s:(b.itemW+b.vars.itemMargin)*b.move*b.animatingTo,u=function(){if(m){return p==="setTouch"?s:k&&b.animatingTo===b.last?0:k?b.limit-(b.itemW+b.vars.itemMargin)*b.move*b.animatingTo:b.animatingTo===b.last?b.limit:t}else{switch(p){case"setTotal":return k?(b.count-1-b.currentSlide+b.cloneOffset)*s:(b.currentSlide+b.cloneOffset)*s;case"setTouch":return k?s:s;case"jumpEnd":return k?s:b.count*s;case"jumpStart":return k?b.count*s:s;default:return s}}}();return u*-1+"px"}();if(b.transitions){r=h?"translate3d(0,"+r+",0)":"translate3d("+r+",0,0)";q=q!==undefined?q/1000+"s":"0s";b.container.css("-"+b.pfx+"-transition-duration",q);b.container.css("transition-duration",q)}b.args[b.prop]=r;if(b.transitions||q===undefined){b.container.css(b.args)}b.container.css("transform",r)};b.setup=function(q){if(!g){var r,p;if(q==="init"){b.viewport=$('<div class="'+i+'viewport"></div>').css({overflow:"hidden",position:"relative"}).appendTo(b).append(b.container);b.cloneCount=0;b.cloneOffset=0;if(k){p=$.makeArray(b.slides).reverse();b.slides=$(p);b.container.empty().append(b.slides)}}if(b.vars.animationLoop&&!m){b.cloneCount=2;b.cloneOffset=1;if(q!=="init"){b.container.find(".clone").remove()}b.container.append(f.uniqueID(b.slides.first().clone().addClass("clone")).attr("aria-hidden","true")).prepend(f.uniqueID(b.slides.last().clone().addClass("clone")).attr("aria-hidden","true"))}b.newSlides=$(b.vars.selector,b);r=k?b.count-1-b.currentSlide+b.cloneOffset:b.currentSlide+b.cloneOffset;if(h&&!m){b.container.height((b.count+b.cloneCount)*200+"%").css("position","absolute").width("100%");setTimeout(function(){b.newSlides.css({display:"block"});b.doMath();b.viewport.height(b.h);b.setProps(r*b.h,"init")},q==="init"?100:0)}else{b.container.width((b.count+b.cloneCount)*200+"%");b.setProps(r*b.computedW,"init");setTimeout(function(){b.doMath();b.newSlides.css({width:b.computedW,marginRight:b.computedM,"float":"left",display:"block"});if(b.vars.smoothHeight){f.smoothHeight()}},q==="init"?100:0)}}else{b.slides.css({width:"100%","float":"left",marginRight:"-100%",position:"relative"});if(q==="init"){if(!j){if(b.vars.fadeFirstSlide==false){b.slides.css({opacity:0,display:"block",zIndex:1}).eq(b.currentSlide).css({zIndex:2}).css({opacity:1})}else{b.slides.css({opacity:0,display:"block",zIndex:1}).eq(b.currentSlide).css({zIndex:2}).animate({opacity:1},b.vars.animationSpeed,b.vars.easing)}}else{b.slides.css({opacity:0,display:"block",webkitTransition:"opacity "+b.vars.animationSpeed/1000+"s ease",zIndex:1}).eq(b.currentSlide).css({opacity:1,zIndex:2})}}if(b.vars.smoothHeight){f.smoothHeight()}}if(!m){b.slides.removeClass(i+"active-slide").eq(b.currentSlide).addClass(i+"active-slide")}b.vars.init(b)};b.doMath=function(){var p=b.slides.first(),s=b.vars.itemMargin,q=b.vars.minItems,r=b.vars.maxItems;b.w=b.viewport===undefined?b.width():b.viewport.width();b.h=p.height();b.boxPadding=p.outerWidth()-p.width();if(m){b.itemT=b.vars.itemWidth+s;b.itemM=s;b.minW=q?q*b.itemT:b.w;b.maxW=r?r*b.itemT-s:b.w;b.itemW=b.minW>b.w?(b.w-s*(q-1))/q:b.maxW<b.w?(b.w-s*(r-1))/r:b.vars.itemWidth>b.w?b.w:b.vars.itemWidth;b.visible=Math.floor(b.w/b.itemW);b.move=b.vars.move>0&&b.vars.move<b.visible?b.vars.move:b.visible;b.pagingCount=Math.ceil((b.count-b.visible)/b.move+1);b.last=b.pagingCount-1;b.limit=b.pagingCount===1?0:b.vars.itemWidth>b.w?b.itemW*(b.count-1)+s*(b.count-1):(b.itemW+s)*b.count-b.w-s}else{b.itemW=b.w;b.itemM=s;b.pagingCount=b.count;b.last=b.count-1}b.computedW=b.itemW-b.boxPadding;b.computedM=b.itemM};b.update=function(q,p){b.doMath();if(!m){if(q<b.currentSlide){b.currentSlide+=1}else{if(q<=b.currentSlide&&q!==0){b.currentSlide-=1}}b.animatingTo=b.currentSlide}if(b.vars.controlNav&&!b.manualControls){if(p==="add"&&!m||b.pagingCount>b.controlNav.length){f.controlNav.update("add")}else{if(p==="remove"&&!m||b.pagingCount<b.controlNav.length){if(m&&b.currentSlide>b.last){b.currentSlide-=1;b.animatingTo-=1}f.controlNav.update("remove",b.last)}}}if(b.vars.directionNav){f.directionNav.update()}};b.addSlide=function(p,r){var q=$(p);b.count+=1;b.last=b.count-1;if(h&&k){r!==undefined?b.slides.eq(b.count-r).after(q):b.container.prepend(q)}else{r!==undefined?b.slides.eq(r).before(q):b.container.append(q)}b.update(r,"add");b.slides=$(b.vars.selector+":not(.clone)",b);b.setup();b.vars.added(b)};b.removeSlide=function(p){var q=isNaN(p)?b.slides.index($(p)):p;b.count-=1;b.last=b.count-1;if(isNaN(p)){$(p,b.slides).remove()}else{h&&k?b.slides.eq(b.last).remove():b.slides.eq(p).remove()}b.doMath();b.update(q,"remove");b.slides=$(b.vars.selector+":not(.clone)",b);b.setup();b.vars.removed(b)};f.init()};$(window).blur(function(a){focused=false}).focus(function(a){focused=true});$.flexslider.defaults={namespace:"flex-",selector:".slides > li",animation:"fade",easing:"swing",direction:"horizontal",reverse:false,animationLoop:true,smoothHeight:false,startAt:0,slideshow:true,slideshowSpeed:7000,animationSpeed:600,initDelay:0,randomize:false,fadeFirstSlide:true,thumbCaptions:false,pauseOnAction:true,pauseOnHover:false,pauseInvisible:true,useCSS:true,touch:true,video:false,controlNav:true,directionNav:true,prevText:"Previous",nextText:"Next",keyboard:true,multipleKeyboard:false,mousewheel:false,pausePlay:false,pauseText:"Pause",playText:"Play",controlsContainer:"",manualControls:"",customDirectionNav:"",sync:"",asNavFor:"",itemWidth:0,itemMargin:0,minItems:1,maxItems:0,move:0,allowOneSlide:true,start:function(){},before:function(){},after:function(){},end:function(){},added:function(){},removed:function(){},init:function(){}};$.fn.flexslider=function(a){if(a===undefined){a={}}if(typeof a==="object"){return this.each(function(){var e=$(this),c=a.selector?a.selector:".slides > li",d=e.find(c);if(d.length===1&&a.allowOneSlide===true||d.length===0){d.fadeIn(400);if(a.start){a.start(e)}}else{if(e.data("flexslider")===undefined){new $.flexslider(this,a)}}})}else{var b=$(this).data("flexslider");switch(a){case"play":b.play();break;case"pause":b.pause();break;case"stop":b.stop();break;case"next":b.flexAnimate(b.getTarget("next"),true);break;case"prev":case"previous":b.flexAnimate(b.getTarget("prev"),true);break;default:if(typeof a==="number"){b.flexAnimate(a,true)}}}}})(jQuery);







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
    var options = {
        speed: 200,
        show: 5000
    };
    $.extend(options, n);
    var _this = $(this);
    var index = 0;
    var direction = 1;
    var str = [];
    var text = $(this).text();
    for(var i=0; i<=text.length; i++) {
        str[i] = text.substr(0, i);
    }
    _this.css('border-right', '1px solid #000');
    setTimeout(init, options.speed);

    function init() {
        _this.text(str[index]);
        if (index >= (str.length - 1)) {
            direction = -1;
            setTimeout(init, options.show);
        } else if (index < 0) {
            index = 0;
            direction = 1;
            setTimeout(init, options.speed);
        } else {
            setTimeout(init, options.speed);
        }
        index += direction;
    }
};

$('#header .desc span').typing({
    range: 200,
    show: 5000
});


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

jQuery(document).on("click", ".open-nav", function() {
    if (jQuery('body').hasClass('has-opened')) {
        jQuery('body').removeClass('has-opened');
        jQuery('#mobile-nav').delay(500).hide(100);
    } else {
        jQuery('#mobile-nav').show();
        jQuery('body').addClass('has-opened');
    }
});
jQuery(document).on("touchstart", ".has-opened #wrap", function() {
    jQuery('body').removeClass('has-opened');
    jQuery('#mobile-nav').delay(500).hide(100);
});

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
    $.get('https://api.hzy.pw/saying/v1/youdao', function(json) {
        var html = '<i class="fa fa-paw" aria-hidden="true"></i> ' + json.cnFix;
        $saying.hide().html(html).attr('title', json.en).fadeIn('slow');
    });
};
if($saying.length) {
    saying_refresh();
    $saying.click(saying_refresh);
}
