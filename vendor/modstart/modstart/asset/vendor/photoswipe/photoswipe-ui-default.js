!function(e,t){"function"==typeof define&&define.amd?define(t):"object"==typeof exports?module.exports=t():e.PhotoSwipeUI_Default=t()}(this,function(){"use strict";return function(o,s){function e(e){if(S)return!0;e=e||window.event,x.timeToIdle&&x.mouseUsed&&!_&&A();for(var t,n,o=(e.target||e.srcElement).getAttribute("class")||"",l=0;l<U.length;l++)(t=U[l]).onTap&&-1<o.indexOf("pswp__"+t.name)&&(t.onTap(),n=!0);n&&(e.stopPropagation&&e.stopPropagation(),S=!0,e=s.features.isOldAndroid?600:30,setTimeout(function(){S=!1},e))}function t(e,t,n){s[(n?"add":"remove")+"Class"](e,"pswp__"+t)}function n(){var e=1===x.getNumItemsFn();e!==F&&(t(m,"ui--one-slide",e),F=e)}function l(){t(b,"share-modal--hidden",y)}function r(){return(y=!y)?(s.removeClass(b,"pswp__share-modal--fade-in"),setTimeout(function(){y&&l()},300)):(l(),setTimeout(function(){y||s.addClass(b,"pswp__share-modal--fade-in")},30)),y||M(),0}function i(e){var t=(e=e||window.event).target||e.srcElement;return o.shout("shareLinkClick",e,t),!!t.href&&(!!t.hasAttribute("download")||(window.open(t.href,"pswp_share","scrollbars=yes,resizable=yes,toolbar=no,location=yes,width=550,height=420,top=100,left="+(window.screen?Math.round(screen.width/2-275):100)),y||r(),!1))}function a(e){for(var t=0;t<x.closeElClasses.length;t++)if(s.hasClass(e,"pswp__"+x.closeElClasses[t]))return!0}function u(e){(e=(e=e||window.event).relatedTarget||e.toElement)&&"HTML"!==e.nodeName||(clearTimeout(K),K=setTimeout(function(){L.setIdle(!0)},x.timeToIdleOutside))}function c(e){var t,n=e.vGap;!o.likelyTouchDevice||x.mouseUsed||screen.width>x.fitControlsWidth?(t=x.barsSize,x.captionEl&&"auto"===t.bottom?(h||((h=s.createEl("pswp__caption pswp__caption--fake")).appendChild(s.createEl("pswp__caption__center")),m.insertBefore(h,f),s.addClass(m,"pswp__ui--fit")),x.addCaptionHTMLFn(e,h,!0)?(e=h.clientHeight,n.bottom=parseInt(e,10)||44):n.bottom=t.top):n.bottom="auto"===t.bottom?0:t.bottom,n.top=t.top):n.top=n.bottom=0}function p(){function e(e){if(e)for(var t=e.length,n=0;n<t;n++){l=e[n],r=l.className;for(var o=0;o<U.length;o++)i=U[o],-1<r.indexOf("pswp__"+i.name)&&(x[i.option]?(s.removeClass(l,"pswp__element--disabled"),i.onInit&&i.onInit(l)):s.addClass(l,"pswp__element--disabled"))}}var l,r,i;e(m.children);var t=s.getChildByClass(m,"pswp__top-bar");t&&e(t.children)}var d,m,f,h,w,g,b,v,_,C,T,I,E,F,x,S,k,K,L=this,O=!1,R=!0,y=!0,z={barsSize:{top:44,bottom:"auto"},closeElClasses:["item","caption","zoom-wrap","ui","top-bar"],timeToIdle:4e3,timeToIdleOutside:1e3,loadingIndicatorDelay:1e3,addCaptionHTMLFn:function(e,t){return e.title?(t.children[0].innerHTML=e.title,!0):(t.children[0].innerHTML="",!1)},closeEl:!0,captionEl:!0,fullscreenEl:!0,zoomEl:!0,shareEl:!0,counterEl:!0,arrowEl:!0,preloaderEl:!0,tapToClose:!1,tapToToggleControls:!0,clickToCloseNonZoomable:!0,shareButtons:[{id:"facebook",label:"Share on Facebook",url:"https://www.facebook.com/sharer/sharer.php?u={{url}}"},{id:"twitter",label:"Tweet",url:"https://twitter.com/intent/tweet?text={{text}}&url={{url}}"},{id:"pinterest",label:"Pin it",url:"http://www.pinterest.com/pin/create/button/?url={{url}}&media={{image_url}}&description={{text}}"},{id:"download",label:"Download image",url:"{{raw_image_url}}",download:!0}],getImageURLForShare:function(){return o.currItem.src||""},getPageURLForShare:function(){return window.location.href},getTextForShare:function(){return o.currItem.title||""},indexIndicatorSep:" / ",fitControlsWidth:1200},M=function(){for(var e,t,n,o,l="",r=0;r<x.shareButtons.length;r++)e=x.shareButtons[r],t=x.getImageURLForShare(e),n=x.getPageURLForShare(e),o=x.getTextForShare(e),l+='<a href="'+e.url.replace("{{url}}",encodeURIComponent(n)).replace("{{image_url}}",encodeURIComponent(t)).replace("{{raw_image_url}}",t).replace("{{text}}",encodeURIComponent(o))+'" target="_blank" class="pswp__share--'+e.id+'"'+(e.download?"download":"")+">"+e.label+"</a>",x.parseShareButtonOut&&(l=x.parseShareButtonOut(e,l));b.children[0].innerHTML=l,b.children[0].onclick=i},D=0,A=function(){clearTimeout(K),D=0,_&&L.setIdle(!1)},P=function(e){I!==e&&(t(T,"preloader--active",!e),I=e)},U=[{name:"caption",option:"captionEl",onInit:function(e){f=e}},{name:"share-modal",option:"shareEl",onInit:function(e){b=e},onTap:function(){r()}},{name:"button--share",option:"shareEl",onInit:function(e){g=e},onTap:function(){r()}},{name:"button--zoom",option:"zoomEl",onTap:o.toggleDesktopZoom},{name:"counter",option:"counterEl",onInit:function(e){w=e}},{name:"button--close",option:"closeEl",onTap:o.close},{name:"button--arrow--left",option:"arrowEl",onTap:o.prev},{name:"button--arrow--right",option:"arrowEl",onTap:o.next},{name:"button--fs",option:"fullscreenEl",onTap:function(){d.isFullscreen()?d.exit():d.enter()}},{name:"preloader",option:"preloaderEl",onInit:function(e){T=e}}];L.init=function(){var t;s.extend(o.options,z,!0),x=o.options,m=s.getChildByClass(o.scrollWrap,"pswp__ui"),(C=o.listen)("onVerticalDrag",function(e){R&&e<.95?L.hideControls():!R&&.95<=e&&L.showControls()}),C("onPinchClose",function(e){R&&e<.9?(L.hideControls(),t=!0):t&&!R&&.9<e&&L.showControls()}),C("zoomGestureEnded",function(){(t=!1)&&!R&&L.showControls()}),C("beforeChange",L.update),C("doubleTap",function(e){var t=o.currItem.initialZoomLevel;o.getZoomLevel()!==t?o.zoomTo(t,e,333):o.zoomTo(x.getDoubleTapZoom(!1,o.currItem),e,333)}),C("preventDragEvent",function(e,t,n){var o=e.target||e.srcElement;o&&o.getAttribute("class")&&-1<e.type.indexOf("mouse")&&(0<o.getAttribute("class").indexOf("__caption")||/(SMALL|STRONG|EM)/i.test(o.tagName))&&(n.prevent=!1)}),C("bindEvents",function(){s.bind(m,"pswpTap click",e),s.bind(o.scrollWrap,"pswpTap",L.onGlobalTap),o.likelyTouchDevice||s.bind(o.scrollWrap,"mouseover",L.onMouseOver)}),C("unbindEvents",function(){y||r(),k&&clearInterval(k),s.unbind(document,"mouseout",u),s.unbind(document,"mousemove",A),s.unbind(m,"pswpTap click",e),s.unbind(o.scrollWrap,"pswpTap",L.onGlobalTap),s.unbind(o.scrollWrap,"mouseover",L.onMouseOver),d&&(s.unbind(document,d.eventK,L.updateFullscreen),d.isFullscreen()&&(x.hideAnimationDuration=0,d.exit()),d=null)}),C("destroy",function(){x.captionEl&&(h&&m.removeChild(h),s.removeClass(f,"pswp__caption--empty")),b&&(b.children[0].onclick=null),s.removeClass(m,"pswp__ui--over-close"),s.addClass(m,"pswp__ui--hidden"),L.setIdle(!1)}),x.showAnimationDuration||s.removeClass(m,"pswp__ui--hidden"),C("initialZoomIn",function(){x.showAnimationDuration&&s.removeClass(m,"pswp__ui--hidden")}),C("initialZoomOut",function(){s.addClass(m,"pswp__ui--hidden")}),C("parseVerticalMargin",c),p(),x.shareEl&&g&&b&&(y=!0),n(),x.timeToIdle&&C("mouseUsed",function(){s.bind(document,"mousemove",A),s.bind(document,"mouseout",u),k=setInterval(function(){2===++D&&L.setIdle(!0)},x.timeToIdle/2)}),x.fullscreenEl&&!s.features.isOldAndroid&&((d=d||L.getFullscreenAPI())?(s.bind(document,d.eventK,L.updateFullscreen),L.updateFullscreen(),s.addClass(o.template,"pswp--supports-fs")):s.removeClass(o.template,"pswp--supports-fs")),x.preloaderEl&&(P(!0),C("beforeChange",function(){clearTimeout(E),E=setTimeout(function(){o.currItem&&o.currItem.loading?o.allowProgressiveImg()&&(!o.currItem.img||o.currItem.img.naturalWidth)||P(!1):P(!0)},x.loadingIndicatorDelay)}),C("imageLoadComplete",function(e,t){o.currItem===t&&P(!0)}))},L.setIdle=function(e){t(m,"ui--idle",_=e)},L.update=function(){O=!(!R||!o.currItem)&&(L.updateIndexIndicator(),x.captionEl&&(x.addCaptionHTMLFn(o.currItem,f),t(f,"caption--empty",!o.currItem.title)),!0),y||r(),n()},L.updateFullscreen=function(e){e&&setTimeout(function(){o.setScrollOffset(0,s.getScrollY())},50),s[(d.isFullscreen()?"add":"remove")+"Class"](o.template,"pswp--fs")},L.updateIndexIndicator=function(){x.counterEl&&(w.innerHTML=o.getCurrentIndex()+1+x.indexIndicatorSep+x.getNumItemsFn())},L.onGlobalTap=function(e){var t=(e=e||window.event).target||e.srcElement;S||(e.detail&&"mouse"===e.detail.pointerType?a(t)?o.close():s.hasClass(t,"pswp__img")&&(1===o.getZoomLevel()&&o.getZoomLevel()<=o.currItem.fitRatio?x.clickToCloseNonZoomable&&o.close():o.toggleDesktopZoom(e.detail.releasePoint)):(x.tapToToggleControls&&(R?L.hideControls():L.showControls()),x.tapToClose&&(s.hasClass(t,"pswp__img")||a(t))&&o.close()))},L.onMouseOver=function(e){e=(e=e||window.event).target||e.srcElement;t(m,"ui--over-close",a(e))},L.hideControls=function(){s.addClass(m,"pswp__ui--hidden"),R=!1},L.showControls=function(){R=!0,O||L.update(),s.removeClass(m,"pswp__ui--hidden")},L.supportsFullscreen=function(){var e=document;return!!(e.exitFullscreen||e.mozCancelFullScreen||e.webkitExitFullscreen||e.msExitFullscreen)},L.getFullscreenAPI=function(){var e,t=document.documentElement,n="fullscreenchange";return t.requestFullscreen?e={enterK:"requestFullscreen",exitK:"exitFullscreen",elementK:"fullscreenElement",eventK:n}:t.mozRequestFullScreen?e={enterK:"mozRequestFullScreen",exitK:"mozCancelFullScreen",elementK:"mozFullScreenElement",eventK:"moz"+n}:t.webkitRequestFullscreen?e={enterK:"webkitRequestFullscreen",exitK:"webkitExitFullscreen",elementK:"webkitFullscreenElement",eventK:"webkit"+n}:t.msRequestFullscreen&&(e={enterK:"msRequestFullscreen",exitK:"msExitFullscreen",elementK:"msFullscreenElement",eventK:"MSFullscreenChange"}),e&&(e.enter=function(){if(v=x.closeOnScroll,x.closeOnScroll=!1,"webkitRequestFullscreen"!==this.enterK)return o.template[this.enterK]();o.template[this.enterK](Element.ALLOW_KEYBOARD_INPUT)},e.exit=function(){return x.closeOnScroll=v,document[this.exitK]()},e.isFullscreen=function(){return document[this.elementK]}),e}}});