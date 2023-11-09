!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t(require("jquery")):"function"==typeof define&&define.amd?define(["jquery"],t):e.Mark=t(e.jQuery)}(this,function(e){"use strict";e=e&&e.hasOwnProperty("default")?e.default:e;function i(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}var r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},t=function(e,t,n){return t&&u(e.prototype,t),n&&u(e,n),e},n=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n,r=arguments[t];for(n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e},o=(t(c,[{key:"getContexts",value:function(){var n=[];return(void 0!==this.ctx&&this.ctx?NodeList.prototype.isPrototypeOf(this.ctx)?Array.prototype.slice.call(this.ctx):Array.isArray(this.ctx)?this.ctx:"string"==typeof this.ctx?Array.prototype.slice.call(document.querySelectorAll(this.ctx)):[this.ctx]:[]).forEach(function(t){var e=0<n.filter(function(e){return e.contains(t)}).length;-1!==n.indexOf(t)||e||n.push(t)}),n}},{key:"getIframeContents",value:function(e,t){var n=2<arguments.length&&void 0!==arguments[2]?arguments[2]:function(){},r=void 0;try{var i=e.contentWindow,r=i.document;if(!i||!r)throw new Error("iframe inaccessible")}catch(e){n()}r&&t(r)}},{key:"isIframeBlank",value:function(e){var t="about:blank",n=e.getAttribute("src").trim();return e.contentWindow.location.href===t&&n!==t&&n}},{key:"observeIframeLoad",value:function(e,t,n){function r(){if(!o){o=!0,clearTimeout(a);try{i.isIframeBlank(e)||(e.removeEventListener("load",r),i.getIframeContents(e,t,n))}catch(e){n()}}}var i=this,o=!1,a=null;e.addEventListener("load",r),a=setTimeout(r,this.iframesTimeout)}},{key:"onIframeReady",value:function(e,t,n){try{"complete"!==e.contentWindow.document.readyState||this.isIframeBlank(e)?this.observeIframeLoad(e,t,n):this.getIframeContents(e,t,n)}catch(e){n()}}},{key:"waitForIframes",value:function(e,t){var n=this,r=0;this.forEachIframe(e,function(){return!0},function(e){r++,n.waitForIframes(e.querySelector("html"),function(){--r||t()})},function(e){e||t()})}},{key:"forEachIframe",value:function(e,n,r){function i(){--a<=0&&t(s)}var o=this,t=3<arguments.length&&void 0!==arguments[3]?arguments[3]:function(){},a=(e=e.querySelectorAll("iframe")).length,s=0,e=Array.prototype.slice.call(e);a||i(),e.forEach(function(t){c.matches(t,o.exclude)?i():o.onIframeReady(t,function(e){n(t)&&(s++,r(e)),i()},i)})}},{key:"createIterator",value:function(e,t,n){return document.createNodeIterator(e,t,n,!1)}},{key:"createInstanceOnIframe",value:function(e){return new c(e.querySelector("html"),this.iframes)}},{key:"compareNodeIframe",value:function(e,t,n){if(e.compareDocumentPosition(n)&Node.DOCUMENT_POSITION_PRECEDING){if(null===t)return!0;if(t.compareDocumentPosition(n)&Node.DOCUMENT_POSITION_FOLLOWING)return!0}return!1}},{key:"getIteratorNode",value:function(e){var t=e.previousNode();return{prevNode:t,node:(null===t||e.nextNode())&&e.nextNode()}}},{key:"checkIframeFilter",value:function(e,t,n,r){var i=!1,o=!1;return r.forEach(function(e,t){e.val===n&&(i=t,o=e.handled)}),this.compareNodeIframe(e,t,n)?(!1!==i||o?!1===i||o||(r[i].handled=!0):r.push({val:n,handled:!0}),!0):(!1===i&&r.push({val:n,handled:!1}),!1)}},{key:"handleOpenIframes",value:function(e,t,n,r){var i=this;e.forEach(function(e){e.handled||i.getIframeContents(e.val,function(e){i.createInstanceOnIframe(e).forEachNode(t,n,r)})})}},{key:"iterateThroughNodes",value:function(t,e,n,r,i){for(var o,a=this,s=this.createIterator(e,t,r),c=[],u=[],l=void 0,h=void 0;o=void 0,o=a.getIteratorNode(s),h=o.prevNode,l=o.node;)this.iframes&&this.forEachIframe(e,function(e){return a.checkIframeFilter(l,h,e,c)},function(e){a.createInstanceOnIframe(e).forEachNode(t,function(e){return u.push(e)},r)}),u.push(l);u.forEach(function(e){n(e)}),this.iframes&&this.handleOpenIframes(c,t,n,r),i()}},{key:"forEachNode",value:function(n,r,i){var o=this,a=3<arguments.length&&void 0!==arguments[3]?arguments[3]:function(){},e=this.getContexts(),s=e.length;s||a(),e.forEach(function(e){function t(){o.iterateThroughNodes(n,e,r,i,function(){--s<=0&&a()})}o.iframes?o.waitForIframes(e,t):t()})}}],[{key:"matches",value:function(t,e){var n,e="string"==typeof e?[e]:e,r=t.matches||t.matchesSelector||t.msMatchesSelector||t.mozMatchesSelector||t.oMatchesSelector||t.webkitMatchesSelector;return!!r&&(n=!1,e.every(function(e){return!r.call(t,e)||!(n=!0)}),n)}}]),c),a=(t(s,[{key:"log",value:function(e){var t=1<arguments.length&&void 0!==arguments[1]?arguments[1]:"debug",n=this.opt.log;this.opt.debug&&"object"===(void 0===n?"undefined":r(n))&&"function"==typeof n[t]&&n[t]("mark.js: "+e)}},{key:"escapeStr",value:function(e){return e.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g,"\\$&")}},{key:"createRegExp",value:function(e){return"disabled"!==this.opt.wildcards&&(e=this.setupWildcardsRegExp(e)),e=this.escapeStr(e),Object.keys(this.opt.synonyms).length&&(e=this.createSynonymsRegExp(e)),(this.opt.ignoreJoiners||this.opt.ignorePunctuation.length)&&(e=this.setupIgnoreJoinersRegExp(e)),this.opt.diacritics&&(e=this.createDiacriticsRegExp(e)),e=this.createMergedBlanksRegExp(e),(this.opt.ignoreJoiners||this.opt.ignorePunctuation.length)&&(e=this.createJoinersRegExp(e)),"disabled"!==this.opt.wildcards&&(e=this.createWildcardsRegExp(e)),this.createAccuracyRegExp(e)}},{key:"createSynonymsRegExp",value:function(e){var t,n,r,i=this.opt.synonyms,o=this.opt.caseSensitive?"":"i",a=this.opt.ignoreJoiners||this.opt.ignorePunctuation.length?"\0":"";for(t in i)i.hasOwnProperty(t)&&(r=i[t],n="disabled"!==this.opt.wildcards?this.setupWildcardsRegExp(t):this.escapeStr(t),r="disabled"!==this.opt.wildcards?this.setupWildcardsRegExp(r):this.escapeStr(r),""!==n)&&""!==r&&(e=e.replace(new RegExp("("+this.escapeStr(n)+"|"+this.escapeStr(r)+")","gm"+o),a+"("+this.processSynomyms(n)+"|"+this.processSynomyms(r)+")"+a));return e}},{key:"processSynomyms",value:function(e){return e=this.opt.ignoreJoiners||this.opt.ignorePunctuation.length?this.setupIgnoreJoinersRegExp(e):e}},{key:"setupWildcardsRegExp",value:function(e){return(e=e.replace(/(?:\\)*\?/g,function(e){return"\\"===e.charAt(0)?"?":""})).replace(/(?:\\)*\*/g,function(e){return"\\"===e.charAt(0)?"*":""})}},{key:"createWildcardsRegExp",value:function(e){var t="withSpaces"===this.opt.wildcards;return e.replace(/\u0001/g,t?"[\\S\\s]?":"\\S?").replace(/\u0002/g,t?"[\\S\\s]*?":"\\S*")}},{key:"setupIgnoreJoinersRegExp",value:function(e){return e.replace(/[^(|)\\]/g,function(e,t,n){n=n.charAt(t+1);return/[(|)\\]/.test(n)||""===n?e:e+"\0"})}},{key:"createJoinersRegExp",value:function(e){var t=[],n=this.opt.ignorePunctuation;return Array.isArray(n)&&n.length&&t.push(this.escapeStr(n.join(""))),this.opt.ignoreJoiners&&t.push("\\u00ad\\u200b\\u200c\\u200d"),t.length?e.split(/\u0000+/).join("["+t.join("")+"]*"):e}},{key:"createDiacriticsRegExp",value:function(n){var r=this.opt.caseSensitive?"":"i",e=this.opt.caseSensitive?["aàáảãạăằắẳẵặâầấẩẫậäåāą","AÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÄÅĀĄ","cçćč","CÇĆČ","dđď","DĐĎ","eèéẻẽẹêềếểễệëěēę","EÈÉẺẼẸÊỀẾỂỄỆËĚĒĘ","iìíỉĩịîïī","IÌÍỈĨỊÎÏĪ","lł","LŁ","nñňń","NÑŇŃ","oòóỏõọôồốổỗộơởỡớờợöøō","OÒÓỎÕỌÔỒỐỔỖỘƠỞỠỚỜỢÖØŌ","rř","RŘ","sšśșş","SŠŚȘŞ","tťțţ","TŤȚŢ","uùúủũụưừứửữựûüůū","UÙÚỦŨỤƯỪỨỬỮỰÛÜŮŪ","yýỳỷỹỵÿ","YÝỲỶỸỴŸ","zžżź","ZŽŻŹ"]:["aàáảãạăằắẳẵặâầấẩẫậäåāąAÀÁẢÃẠĂẰẮẲẴẶÂẦẤẨẪẬÄÅĀĄ","cçćčCÇĆČ","dđďDĐĎ","eèéẻẽẹêềếểễệëěēęEÈÉẺẼẸÊỀẾỂỄỆËĚĒĘ","iìíỉĩịîïīIÌÍỈĨỊÎÏĪ","lłLŁ","nñňńNÑŇŃ","oòóỏõọôồốổỗộơởỡớờợöøōOÒÓỎÕỌÔỒỐỔỖỘƠỞỠỚỜỢÖØŌ","rřRŘ","sšśșşSŠŚȘŞ","tťțţTŤȚŢ","uùúủũụưừứửữựûüůūUÙÚỦŨỤƯỪỨỬỮỰÛÜŮŪ","yýỳỷỹỵÿYÝỲỶỸỴŸ","zžżźZŽŻŹ"],i=[];return n.split("").forEach(function(t){e.every(function(e){if(-1!==e.indexOf(t)){if(-1<i.indexOf(e))return!1;n=n.replace(new RegExp("["+e+"]","gm"+r),"["+e+"]"),i.push(e)}return!0})}),n}},{key:"createMergedBlanksRegExp",value:function(e){return e.replace(/[\s]+/gim,"[\\s]+")}},{key:"createAccuracyRegExp",value:function(e){var t=this,n=this.opt.accuracy,r="string"==typeof n?n:n.value,n="string"==typeof n?[]:n.limiters,i="";switch(n.forEach(function(e){i+="|"+t.escapeStr(e)}),r){case"partially":default:return"()("+e+")";case"complementary":return"()([^"+(i="\\s"+(i||this.escapeStr("!\"#$%&'()*+,-./:;<=>?@[\\]^_`{|}~¡¿")))+"]*"+e+"[^"+i+"]*)";case"exactly":return"(^|\\s"+i+")("+e+")(?=$|\\s"+i+")"}}},{key:"getSeparatedKeywords",value:function(e){var t=this,n=[];return e.forEach(function(e){t.opt.separateWordSearch?e.split(" ").forEach(function(e){e.trim()&&-1===n.indexOf(e)&&n.push(e)}):e.trim()&&-1===n.indexOf(e)&&n.push(e)}),{keywords:n.sort(function(e,t){return t.length-e.length}),length:n.length}}},{key:"isNumeric",value:function(e){return Number(parseFloat(e))==e}},{key:"checkRanges",value:function(e){var i,o,a=this;return Array.isArray(e)&&"[object Object]"===Object.prototype.toString.call(e[0])?(i=[],o=0,e.sort(function(e,t){return e.start-t.start}).forEach(function(e){var t=a.callNoMatchOnInvalidRanges(e,o),n=t.start,r=t.end;t.valid&&(e.start=n,e.length=r-n,i.push(e),o=r)}),i):(this.log("markRanges() will only accept an array of objects"),this.opt.noMatch(e),[])}},{key:"callNoMatchOnInvalidRanges",value:function(e,t){var n=void 0,r=void 0,i=!1;return e&&void 0!==e.start?(r=(n=parseInt(e.start,10))+parseInt(e.length,10),this.isNumeric(e.start)&&this.isNumeric(e.length)&&0<r-t&&0<r-n?i=!0:(this.log("Ignoring invalid or overlapping range: "+JSON.stringify(e)),this.opt.noMatch(e))):(this.log("Ignoring invalid range: "+JSON.stringify(e)),this.opt.noMatch(e)),{start:n,end:r,valid:i}}},{key:"checkWhitespaceRanges",value:function(e,t,n){var r=void 0,i=!0,o=n.length,t=t-o,t=parseInt(e.start,10)-t;return o<(r=(t=o<t?o:t)+parseInt(e.length,10))&&this.log("End range automatically set to the max value of "+(r=o)),t<0||r-t<0||o<t||o<r?(i=!1,this.log("Invalid range: "+JSON.stringify(e)),this.opt.noMatch(e)):""===n.substring(t,r).replace(/\s+/g,"")&&(i=!1,this.log("Skipping whitespace only range: "+JSON.stringify(e)),this.opt.noMatch(e)),{start:t,end:r,valid:i}}},{key:"getTextNodes",value:function(e){var t=this,n="",r=[];this.iterator.forEachNode(NodeFilter.SHOW_TEXT,function(e){r.push({start:n.length,end:(n+=e.textContent).length,node:e})},function(e){return t.matchesExclude(e.parentNode)?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT},function(){e({value:n,nodes:r})})}},{key:"matchesExclude",value:function(e){return o.matches(e,this.opt.exclude.concat(["script","style","title","head","html"]))}},{key:"wrapRangeInTextNode",value:function(e,t,n){var r=this.opt.element||"mark",e=e.splitText(t),n=e.splitText(n-t),t=document.createElement(r);return t.setAttribute("data-markjs","true"),this.opt.className&&t.setAttribute("class",this.opt.className),t.textContent=e.textContent,e.parentNode.replaceChild(t,e),n}},{key:"wrapRangeInMappedTextNode",value:function(a,s,c,u,l){var h=this;a.nodes.every(function(e,n){var t=a.nodes[n+1];if(void 0===t||t.start>s){if(!u(e.node))return!1;var t=s-e.start,r=(c>e.end?e.end:c)-e.start,i=a.value.substr(0,e.start),o=a.value.substr(r+e.start);if(e.node=h.wrapRangeInTextNode(e.node,t,r),a.value=i+o,a.nodes.forEach(function(e,t){n<=t&&(0<a.nodes[t].start&&t!==n&&(a.nodes[t].start-=r),a.nodes[t].end-=r)}),c-=r,l(e.node.previousSibling,e.start),!(c>e.end))return!1;s=e.end}return!0})}},{key:"wrapMatches",value:function(i,e,o,a,t){var s=this,c=0===e?0:e+1;this.getTextNodes(function(e){e.nodes.forEach(function(e){e=e.node;for(var t;null!==(t=i.exec(e.textContent))&&""!==t[c];)if(o(t[c],e)){var n=t.index;if(0!==c)for(var r=1;r<c;r++)n+=t[r].length;e=s.wrapRangeInTextNode(e,n,n+t[c].length),a(e.previousSibling),i.lastIndex=0}}),t()})}},{key:"wrapMatchesAcrossElements",value:function(o,e,a,s,c){var u=this,l=0===e?0:e+1;this.getTextNodes(function(e){for(var t;null!==(t=o.exec(e.value))&&""!==t[l];){var n=t.index;if(0!==l)for(var r=1;r<l;r++)n+=t[r].length;var i=n+t[l].length;u.wrapRangeInMappedTextNode(e,n,i,function(e){return a(t[l],e)},function(e,t){o.lastIndex=t,s(e)})}c()})}},{key:"wrapRangeFromIndex",value:function(e,s,c,t){var u=this;this.getTextNodes(function(o){var a=o.value.length;e.forEach(function(t,n){var e=u.checkWhitespaceRanges(t,a,o.value),r=e.start,i=e.end;e.valid&&u.wrapRangeInMappedTextNode(o,r,i,function(e){return s(e,t,o.value.substring(r,i),n)},function(e){c(e,t)})}),t()})}},{key:"unwrapMatches",value:function(e){for(var t=e.parentNode,n=document.createDocumentFragment();e.firstChild;)n.appendChild(e.removeChild(e.firstChild));t.replaceChild(n,e),this.ie?this.normalizeTextNode(t):t.normalize()}},{key:"normalizeTextNode",value:function(e){if(e){if(3===e.nodeType)for(;e.nextSibling&&3===e.nextSibling.nodeType;)e.nodeValue+=e.nextSibling.nodeValue,e.parentNode.removeChild(e.nextSibling);else this.normalizeTextNode(e.firstChild);this.normalizeTextNode(e.nextSibling)}}},{key:"markRegExp",value:function(e,t){var n=this,r=(this.opt=t,this.log('Searching with expression "'+e+'"'),0),t="wrapMatches";this[t=this.opt.acrossElements?"wrapMatchesAcrossElements":t](e,this.opt.ignoreGroups,function(e,t){return n.opt.filter(t,e,r)},function(e){r++,n.opt.each(e)},function(){0===r&&n.opt.noMatch(e),n.opt.done(r)})}},{key:"mark",value:function(e,t){var i=this,o=(this.opt=t,0),a="wrapMatches",t=this.getSeparatedKeywords("string"==typeof e?[e]:e),s=t.keywords,c=t.length,u=this.opt.caseSensitive?"":"i";this.opt.acrossElements&&(a="wrapMatchesAcrossElements"),0===c?this.opt.done(o):function e(n){var t=new RegExp(i.createRegExp(n),"gm"+u),r=0;i.log('Searching with expression "'+t+'"'),i[a](t,1,function(e,t){return i.opt.filter(t,n,o,r)},function(e){r++,o++,i.opt.each(e)},function(){0===r&&i.opt.noMatch(n),s[c-1]===n?i.opt.done(o):e(s[s.indexOf(n)+1])})}(s[0])}},{key:"markRanges",value:function(e,t){var i=this,n=(this.opt=t,0),t=this.checkRanges(e);t&&t.length?(this.log("Starting to mark with the following ranges: "+JSON.stringify(t)),this.wrapRangeFromIndex(t,function(e,t,n,r){return i.opt.filter(e,t,n,r)},function(e,t){n++,i.opt.each(e,t)},function(){i.opt.done(n)})):this.opt.done(n)}},{key:"unmark",value:function(e){var n=this,r=(this.opt=e,this.opt.element||"*");r+="[data-markjs]",this.opt.className&&(r+="."+this.opt.className),this.log('Removal selector "'+r+'"'),this.iterator.forEachNode(NodeFilter.SHOW_ELEMENT,function(e){n.unwrapMatches(e)},function(e){var t=o.matches(e,r),e=n.matchesExclude(e);return!t||e?NodeFilter.FILTER_REJECT:NodeFilter.FILTER_ACCEPT},this.opt.done)}},{key:"opt",set:function(e){this._opt=n({},{element:"",className:"",exclude:[],iframes:!1,iframesTimeout:5e3,separateWordSearch:!0,diacritics:!0,synonyms:{},accuracy:"partially",acrossElements:!1,caseSensitive:!1,ignoreJoiners:!1,ignoreGroups:0,ignorePunctuation:[],wildcards:"disabled",each:function(){},noMatch:function(){},filter:function(){return!0},done:function(){},debug:!1,log:window.console},e)},get:function(){return this._opt}},{key:"iterator",get:function(){return new o(this.ctx,this.opt.iframes,this.opt.exclude,this.opt.iframesTimeout)}}]),s);function s(e){i(this,s),this.ctx=e,this.ie=!1;e=window.navigator.userAgent;(-1<e.indexOf("MSIE")||-1<e.indexOf("Trident"))&&(this.ie=!0)}function c(e){var t=!(1<arguments.length&&void 0!==arguments[1])||arguments[1],n=2<arguments.length&&void 0!==arguments[2]?arguments[2]:[],r=3<arguments.length&&void 0!==arguments[3]?arguments[3]:5e3;i(this,c),this.ctx=e,this.iframes=t,this.exclude=n,this.iframesTimeout=r}function u(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return e.fn.mark=function(e,t){return new a(this.get()).mark(e,t),this},e.fn.markRegExp=function(e,t){return new a(this.get()).markRegExp(e,t),this},e.fn.markRanges=function(e,t){return new a(this.get()).markRanges(e,t),this},e.fn.unmark=function(e){return new a(this.get()).unmark(e),this},e});