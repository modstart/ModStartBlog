!function(n){var i={};function l(e){if(i[e])return i[e].exports;var t=i[e]={i:e,l:!1,exports:{}};return n[e].call(t.exports,t,t.exports,l),t.l=!0,t.exports}l.m=n,l.c=i,l.d=function(e,t,n){l.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},l.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},l.t=function(t,e){if(1&e&&(t=l(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(l.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var i in t)l.d(n,i,function(e){return t[e]}.bind(null,i));return n},l.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return l.d(t,"a",t),t},l.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},l.p="/asset//",l(l.s=323)}({323:function(e,t,n){n=n(324);"api"in window||(window.api={}),window.api.multiSelector=n,"MS"in window||(window.MS={}),window.MS.multiSelector=n},324:function(n,i,l){!function(e,d){var t;!function(){function e(e){var r,o,s,u,a,c,p,f;void 0!==d?(this.opt=d.extend({container:null,seperator:",",dynamic:!1,server:"/path/to/data",data:[],maxLevel:0,fixedLevel:0,lang:{loading:"正在加载...",pleaseSelect:"请选择"},onChange:function(e,t){},selectorValue:"[data-value]",selectorTitle:"[data-title]",selectorSelect:"[data-select]",valueKey:"id",parentValueKey:"pid",titleKey:"title",sortKey:"sort",rootParentValue:0,serverMethod:"get",serverDataType:"json",serverResponseHandle:function(e){return"object"!=typeof e?(alert("ErrorResponse:"+e),[]):"code"in e&&"data"in e?0!=e.code?(alert("ErrorResponseCode:"+e.code),[]):e.data:(alert("ErrorResponseObject:"+e.toString()),[])}},e),o=d((r=this).opt.container),s=o.find(this.opt.selectorSelect),this.value=null,this.title=null,this.server=null,this.seperator=",",this.maxLevel=0,this.data=[],this.initValues=[],this.initTitles=[],u=function(e,t,n){n=n||null;var i={},l=r.opt.sortKey;i[r.opt.parentValueKey]=e,i[r.opt.titleKey]=n,d.ajax({type:r.opt.serverMethod,url:r.opt.server,dataType:r.opt.serverDataType,timeout:3e4,data:i,success:function(e){r.opt.serverResponseHandle(e).sort(function(e,t){return e[l]-t[l]});e=a(e.data);t(e)},error:function(){alert("请求出现错误 T_T")}})},a=function(e){for(var t=[],n=0;n<e.length;n++)t.push({parentValue:e[n][r.opt.parentValueKey],value:e[n][r.opt.valueKey],title:e[n][r.opt.titleKey]});return t},c=function(n,e){for(var t=[],i=r.data,l=0;l<i.length;l++)i[l].parentValue==e&&t.push({value:i[l].value,title:i[l].title});if(s.find("select").each(function(e,t){parseInt(d(t).attr("data-level"))>=n&&d(t).remove()}),t.length){var a=[];for(a.push('<select data-level="'+n+'" class="select">'),a.push('<option value="">'+r.opt.lang.pleaseSelect+"</option>"),l=0;l<t.length;l++)a.push('<option value="'+t[l].value+'">'+t[l].title+"</option>");a.push("</select>"),s.append(a.join("")),o.trigger("widget.category.rendered",[r])}},p=function(n){s.find("select").each(function(e,t){parseInt(d(t).attr("data-level"))>=n&&d(t).remove()})},f=function(){o.find(r.opt.selectorValue).val(""),o.find(r.opt.selectorTitle).val("");var i=[],l=[];s.find("select").each(function(e,t){var n=d(t).val();n&&(i.push(n),l.push(d(t).find("option:selected").text()))}),o.find(r.opt.selectorValue).each(function(e,t){var n=d(t).attr("data-level");n?(n=parseInt(n))<=i.length&&d(t).val(i[n-1]):d(t).val(i.join(r.seperator))}),o.find(r.opt.selectorTitle).each(function(e,t){var n=d(t).attr("data-level");n?(n=parseInt(n))<=l.length&&d(t).val(l[n-1]):d(t).val(l.join(r.seperator))}),r.value=i,r.title=l,o.trigger("widget.category.change",[r]),r.opt.onChange(i,l)},this.val=function(e){if(null==e)return r.value;if(r.initValues=e,r.initValues.length)if(r.opt.dynamic)u(r.initValues.join(","),function(e){r.data=e,s.html(""),c(1,0);for(var t=0;t<r.initValues.length-1;t++){var n=t+2,i=r.initValues[t];(0==r.maxLevel||n<=r.maxLevel)&&c(n,i)}s.find("select").each(function(e,t){var n=d(t).attr("data-level");n&&(n=parseInt(n))<=r.initValues.length&&d(t).val(r.initValues[n-1])}),r.initValues=[],f()});else{c(1,r.opt.rootParentValue);for(var t=0;t<r.initValues.length;t++){var n=t+1,i=r.initValues[t],l=s.find("select");l.length>=n&&d(l.get(n-1)).val(i).trigger("change")}r.initValues=[]}else p(1)},this.titleVal=function(e){if(null==e)return r.title;if(r.initTitles=e,r.initTitles.length)if(r.opt.dynamic)u(null,function(e){r.data=e,s.html(""),c(1,0);for(var t=0;t<r.initTitles.length-1;t++){var n=t+2,i=r.initTitles[t],l=s.find("select[data-level="+(n-1)+"]"),a=null;l.find("option").each(function(e,t){d(t).text()==i&&(a=d(t).attr("value"))}),null!==a&&(0==r.maxLevel||n<=r.maxLevel)&&c(n,a)}s.find("select").each(function(e,t){var n,i=d(t).attr("data-level");i&&(i=parseInt(i))<=r.initTitles.length&&(n=r.initTitles[i-1],d(t).find("option").each(function(e,t){d(t).text()==n&&d(t).prop("selected",!0)}))}),f()},r.initTitles.join(","));else{c(1,r.opt.rootParentValue);for(var t=0;t<r.initTitles.length;t++){var n,i=t+1,l=r.initTitles[t],a=s.find("select");a.length>=i&&(n=d(a.get(i-1))).find("option").each(function(e,t){d(t).text()==l&&n.val(d(t).attr("value")).trigger("change")})}}else p(1)},s.on("change","select",function(){var l=parseInt(d(this).attr("data-level")),a=d(this).val();return r.opt.dynamic?(0==r.maxLevel||l<r.maxLevel)&&(0<a?u(a,function(e){if(r.data=e,c(l+1,a),r.initValues&&r.initValues.length){if(!(t=s.find("select")).length)return;(n=d(t.get(t.length-1))).val(r.initValues.splice(0,1)[0]).trigger("change")}else if(r.initTitles&&r.initTitles.length){var t;if(!(t=s.find("select")).length)return;var n=d(t.get(t.length-1)),i=r.initTitles.splice(0,1)[0];n.find("option").each(function(e,t){d(t).text()==i&&n.val(d(t).attr("value")).trigger("change")})}f()}):(p(l+1),f())):(0==r.maxLevel||l<r.maxLevel)&&(0<a?c(l+1,a):p(l+1),f()),!1}),function(){var e=o.find(r.opt.selectorValue).val(),t=o.find(r.opt.selectorTitle).val();if(e)for(var n=e.split(r.seperator),i=0;i<n.length;i++)n[i]&&r.initValues.push(n[i]);else if(t)for(var l=t.split(r.seperator),i=0;i<l.length;i++)l[i]&&r.initTitles.push(l[i])}(),s.html(r.opt.lang.loading),r.opt.dynamic&&r.initValues.length?r.val(r.initValues):r.opt.dynamic&&r.initTitles.length?r.titleVal(r.initTitles):r.opt.dynamic?u(r.opt.rootParentValue,function(e){r.data=e,s.html(""),c(1,r.opt.rootParentValue),r.initValues.length?r.val(r.initValues):r.initTitles.length&&r.titleVal(r.initTitles)}):(r.data=a(r.opt.data),s.html(""),r.initValues.length?r.val(r.initValues):r.initTitles.length?r.titleVal(r.initTitles):c(1,r.opt.rootParentValue))):alert("MultiSelector require jQuery")}l(325).cmd?n.exports=e:void 0===(t=function(){return e}.call(i,l,i,n))||(n.exports=t)}.call(function(){return this||("undefined"!=typeof window?window:e)}())}.call(this,l(5),l(6))},325:function(e,t){e.exports=function(){throw new Error("define cannot be used indirect")}},5:function(e,t){var n=function(){return this}();try{n=n||new Function("return this")()}catch(e){"object"==typeof window&&(n=window)}e.exports=n},6:function(e,t){e.exports=window.$}});