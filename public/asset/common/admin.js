!function(a){var i={};function n(t){if(i[t])return i[t].exports;var e=i[t]={i:t,l:!1,exports:{}};return a[t].call(e.exports,e,e.exports,n),e.l=!0,e.exports}n.m=a,n.c=i,n.d=function(t,e,a){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:a})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var a=Object.create(null);if(n.r(a),Object.defineProperty(a,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var i in e)n.d(a,i,function(t){return e[t]}.bind(null,i));return a},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="/asset//",n(n.s=297)}({297:function(t,e,a){!function(c){window._pageTabManager={closeFromTab:function(){var t;window!=parent.window&&(t=window.frameElement.getAttribute("data-tab-page"),window.parent._pageTabManager.close(t))},updateTitleFromTab:function(t){var e;t&&window!=parent.window&&(e=window.frameElement.getAttribute("data-tab-page"),window.parent._pageTabManager.updateTitle(e,t))}},c(window).on("load",function(){c(".ub-panel-frame > .right > .content.fixed, .ub-panel-dialog .panel-dialog-body").scroll(function(){var t=document.createEvent("HTMLEvents");t.initEvent("scroll",!1,!0),window.dispatchEvent(t)});var t=c(window).width()<600,e=c(".ub-panel-frame");t?(e.find(".left-menu-shrink").on("click",function(){e.removeClass("left-toggle")}),e.find(".left-trigger").on("click",function(){e.addClass("left-toggle")})):e.find(".left-trigger").on("click",function(){e.toggleClass("left-toggle"),window.api.base.postSuccess(window.__msAdminRoot+"util/frame",{frameLeftToggle:e.is(".left-toggle")},function(t){})});var a=e.find(".left .menu"),i=null,n=e.find("#menuSearchKeywords");n.on("keyup",function(){var t=c(this).val();i&&clearTimeout(i),i=setTimeout(function(){var r;(r=c.trim(t))?(a.find(".title").addClass("open"),a.find("[data-keywords-filter]").attr("data-keywords-filter","hide"),a.find("[data-keywords-item]").attr("data-keywords-item","hide"),a.find("[data-keywords-filter]").each(function(t,e){var a=c(e).text().trim(),i=PinyinMatch.match(a,r),n=a;!1!==i&&(i=i,n=(a=a).substring(0,i[0])+"<mark>"+a.substring(i[0],i[1]+1)+"</mark>"+a.substring(i[1]+1),c(e).attr("data-keywords-filter","show"),c(e).attr("data-keywords-item","show")),c(e).find("span").html(n)}),a.find(">.menu-item>.children>.menu-item>.children").each(function(t,e){0<c(e).find("[data-keywords-filter=show]").length&&c(e).attr("data-keywords-item","show").prev().attr("data-keywords-item","show")}),a.find(">.menu-item>.children").each(function(t,e){0<c(e).find("[data-keywords-filter=show]").length&&c(e).attr("data-keywords-item","show").prev().attr("data-keywords-item","show")})):(a.find("[data-keywords-filter]").attr("data-keywords-filter","show"),a.find("[data-keywords-item]").attr("data-keywords-item","show"),a.find("[data-keywords-filter]").each(function(t,e){var a=c(e).text().trim();c(e).find("span").html(a)}))},200)}),n.val()&&n.trigger("keyup");var r,d,o=e.find("#adminTabPage"),s=e.find("#adminTabMenu"),l=e.find("#adminMainPage"),n=e.find("#adminTabRefresh");c("html").is("[page-tabs-enable]")&&!t?(r={draging:!1,scrollLeftStart:0,startX:0,startY:0,isDragged:!1},s.on("mousedown",function(t){r.draging=!0,r.scrollLeftStart=s.scrollLeft(),r.startX=t.pageX,r.startY=t.pageY,r.isDragged=!1}),s.on("mousemove",function(t){var e;r.draging&&(10<(e=t.pageX-r.startX)*e+(t=t.pageY-r.startY)*t&&(r.isDragged=!0),s.scrollLeft(r.scrollLeftStart-e))}),s.on("mouseup",function(t){r.draging=!1}),s.on("mouseleave",function(t){r.draging=!1}),d=Object.assign(window._pageTabManager,{data:[],id:1,normalTabUrl(t){if(0<t.indexOf("_is_tab=1"))return t;let e=(t=new URL(t,document.baseURI).href).split("#"),a=e[0];return a=a+(-1<a.indexOf("?")?"&":"?")+"_is_tab=1",a+(e[1]?"#"+e[1]:"")},getIndexById:function(t){t=parseInt(t);for(var e=0;e<this.data.length;e++)if(this.data[e].id==t)return e;return null},getById:function(t){t=this.getIndexById(t);return null===t?null:this.data[t]},getByUrl:function(t){t=this.normalTabUrl(t);for(var e=0;e<this.data.length;e++)if(this.data[e].url==t)return this.data[e];return null},close:function(t){var e,a=this.getIndexById(t);null!==a&&(e=this.data[a],o.find("[data-tab-page="+t+"]").remove(),s.find("[data-tab-menu="+t+"]").remove(),e.active&&(0<a?this.active(this.data[a-1].id):a<this.data.length-1?this.active(this.data[a+1].id):this.active(0)),this.data.splice(a,1),this.updateMainPage())},updateMainPage:function(){var t=0<this.data.filter(t=>t.active).length;l.toggleClass("hidden",t),o.toggleClass("hidden",!t)},activeId:function(){for(var t=0;t<this.data.length;t++)if(this.data[t].active)return this.data[t].id;return null},activeByUrl:function(t){t=this.getByUrl(t);t&&this.active(t.id)},refresh:function(){var t=this.activeId();(t?o.find("iframe[data-tab-page="+t+"]")[0].contentWindow:window).location.reload()},active:function(t){if(t){o.find("iframe").addClass("hidden").filter("[data-tab-page="+t+"]").removeClass("hidden"),s.find("a").removeClass("active").filter("[data-tab-menu="+t+"]").addClass("active");try{s.find("[data-tab-menu="+t+"]")[0].scrollIntoView({block:"center",behavior:"smooth"})}catch(t){}for(e=0;e<this.data.length;e++)this.data[e].active=this.data[e].id==t;this.updateMainPage()}else{o.find("iframe").addClass("hidden"),s.find("a").removeClass("active");try{s.find("[data-tab-menu-main]").addClass("active")[0].scrollIntoView({block:"center",behavior:"smooth"})}catch(t){}for(var e=0;e<this.data.length;e++)this.data[e].active=!1;this.updateMainPage()}},open:function(t,e){t=this.normalTabUrl(t);var a=this.getByUrl(t);a?this.active(a.id):(o.append(`<iframe src="${t}" class="hidden" frameborder="0" data-tab-page="${this.id}"></iframe>`),s.append(`<a href="javascript:;" data-tab-menu="${this.id}" draggable="false">${e}<i class="close iconfont icon-close"></i></a>`),this.data.push({url:t,title:e,id:this.id,active:!1}),this.active(this.id),this.id++)},updateTitle:function(t,e){s.find("[data-tab-menu="+t+"]").html(e+'<i class="close iconfont icon-close"></i>')}}),a.find("a").on("click",function(){var t=c(this);if(!t.is("[data-tab-menu-ignore]")){var e=t.attr("href");if(!["javascript:;"].includes(e)){t=c.trim(t.text());return d.open(e,t),!1}}}),s.on("click","[data-tab-menu-main]",function(){if(!r.isDragged)return d.active(null),!1}),s.on("click","[data-tab-menu]",function(){r.isDragged||d.active(c(this).attr("data-tab-menu"))}),s.on("click","[data-tab-menu] i.close",function(){return d.close(c(this).parent().attr("data-tab-menu")),!1}),n.on("click",function(){return d.refresh(),!1}),c(document).on("click","[data-tab-open]",function(){var e=c(this).attr("href");if(!["javascript:;"].includes(e)){let t=c(this).attr("data-tab-title");return t=t||c(this).text(),(window.parent!==window?window.parent._pageTabManager:d).open(e,t),!1}}),window._pageTabManager=d):n.remove(),e.find("#fullScreenTrigger").on("click",function(){return MS.util.fullscreen.trigger(),!1})})}.call(this,a(6))},6:function(t,e){t.exports=window.$}});