!function(i){var o={};function a(t){if(o[t])return o[t].exports;var e=o[t]={i:t,l:!1,exports:{}};return i[t].call(e.exports,e,e.exports,a),e.l=!0,e.exports}a.m=i,a.c=o,a.d=function(t,e,i){a.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:i})},a.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(a.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)a.d(i,o,function(t){return e[t]}.bind(null,o));return i},a.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return a.d(e,"a",e),e},a.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},a.p="/asset//",a(a.s=322)}({322:function(module,exports,__webpack_require__){!function($){var GridManager=function(opt){var option=$.extend({id:"",canBatchSelect:!1,canSingleSelectItem:!1,canMultiSelectItem:!1,title:null,titleAdd:null,titleEdit:null,titleShow:null,ttileImport:null,canAdd:!1,canEdit:!1,canDelete:!1,canShow:!1,canBatchDelete:!1,canSort:!1,canExport:!1,canImport:!1,urlAdd:!1,urlEdit:!1,urlDelete:!1,urlShow:!1,urlExport:!1,urlImport:!1,urlSort:!1,batchOperatePrepend:"",gridToolbar:"",defaultPageSize:10,gridBeforeRequestScript:null,pageSizes:[],addDialogSize:["90%","90%"],editDialogSize:["90%","90%"],showDialogSize:["90%","90%"],importDialogSize:["90%","90%"],pageJumpEnable:!1,lang:{loading:"Loading",noRecords:"No Records",add:"Add",edit:"Edit",show:"Show",import:"Import",confirmDelete:"Confirm Delete ?",pleaseSelectRecords:"Please Select Records",confirmDeleteRecords:"Confirm Delete %d records ?"}},opt),$grid=$("#"+option.id),listerData={page:1,pageSize:1,records:[],total:1,head:[]},processArea=function(t){return/^(\d+)px$/.test(t[0])&&(t[0]=Math.min($(window).width(),parseInt(t[0]))+"px"),/^(\d+)px$/.test(t[1])&&(t[1]=Math.min($(window).height(),parseInt(t[1]))+"px"),t},getId=function(t){t=parseInt($(t).closest("[data-index]").attr("data-index"));return listerData.records[t]._id},getCheckedIds=function(){for(var t=layui.table.checkStatus(option.id+"Table").data,e=[],i=0;i<t.length;i++)e.push(t[i]._id);return e},getCheckedItems=function(){for(var t=layui.table.checkStatus(option.id+"Table").data,e=[],i=0;i<t.length;i++)e.push(t[i]);return e};layui.use(["table","laypage"],function(){var tableOption={id:option.id+"Table",elem:"#"+option.id+"Table",defaultToolbar:option.gridToolbar,page:!1,skin:"line",text:{none:'<div class="ub-text-muted tw-py-4"><i class="iconfont icon-refresh tw-animate-spin tw-inline-block" style="font-size:2rem;"></i><br />'+option.lang.loading+"</div>"},loading:!0,cellMinWidth:100,cols:[[]],data:[],autoColumnWidth:!0,autoScrollTop:!1,autoSort:!1,done:function(){}};option.canMultiSelectItem&&(option.batchOperatePrepend||option.canDelete&&option.canBatchDelete)&&(tableOption.toolbar="#"+option.id+"TableHeadToolbar");var table=layui.table.render(tableOption);layui.table.on("sort("+option.id+"Table)",function(t){null==t.type?lister.setParam("order",[]):lister.setParam("order",[[t.field,t.type]]),lister.setPage(1),lister.load()});var isFirst=!0,$lister=$("#"+option.id),first=!0,lister=new window.api.lister({search:$lister.find("[data-search]"),table:$lister.find("[data-table]")},{hashUrl:!1,server:window.location.href,showLoading:!1,param:{pageSize:option.defaultPageSize},customLoading:function(loading){option.gridBeforeRequestScript&&eval(option.gridBeforeRequestScript),first?first=!1:table.loading(loading)},render:function(data){listerData=data,option.canSingleSelectItem?data.head.splice(0,0,{type:"radio"}):option.canMultiSelectItem&&data.head.splice(0,0,{type:"checkbox"}),$grid.find("[data-addition]").html(data.addition||""),layui.table.reload(option.id+"Table",{text:{none:'<div class="ub-text-muted"><i class="iconfont icon-empty-box" style="font-size:2rem;"></i><br />'+option.lang.noRecords+"</div>"},cols:[data.head],data:data.records,limit:data.pageSize});var pageLayout=["limit","prev","page","next","count"];option.pageJumpEnable&&pageLayout.push("skip"),layui.laypage.render({elem:option.id+"Pager",curr:data.page,count:data.total,limit:data.pageSize,limits:option.pageSizes,layout:pageLayout,jump:function(t,e){e||(lister.setPage(t.curr),lister.setPageSize(t.limit),lister.load())}}),data.script&&eval(data.script)}});function doEdit(t){t=getId(t);lister.realtime.dialog.edit=layer.open({type:2,title:option.titleEdit||(option.title?option.lang.edit+option.title:option.lang.edit),shadeClose:!1,shade:.5,maxmin:!1,scrollbar:!1,area:processArea(option.editDialogSize),content:lister.realtime.url.edit+(lister.realtime.url.edit&&0<=lister.realtime.url.edit.indexOf("?")?"&":"?")+"_id="+t,success:function(t,e){lister.realtime.dialog.editWindow=$(t).find("iframe").get(0).contentWindow,lister.realtime.dialog.editWindow.__dialogClose=function(){layer.close(lister.realtime.dialog.edit)},lister.realtime.dialog.editWindow.addEventListener("modstart:form.submitted",function(t){0===t.detail.res.code&&layer.close(lister.realtime.dialog.edit)})},end:function(){lister.refresh()}})}function doDelete(t){var e=getId(t);window.api.dialog.confirm(option.lang.confirmDelete,function(){window.api.dialog.loadingOn(),window.api.base.post(lister.realtime.url.delete,{_id:e},function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){lister.refresh()}})})})}function doShow(t){t=getId(t);lister.realtime.dialog.show=layer.open({type:2,title:option.titleShow||(option.title?option.lang.show+option.title:option.lang.show),shadeClose:!1,shade:.5,maxmin:!1,scrollbar:!1,area:processArea(option.showDialogSize),content:lister.realtime.url.show+(lister.realtime.url.show&&0<=lister.realtime.url.show.indexOf("?")?"&":"?")+"_id="+t,success:function(t,e){},end:function(){}})}lister.realtime={url:{add:option.urlAdd,edit:option.urlEdit,delete:option.urlDelete,show:option.urlShow,export:option.urlExport,import:option.urlImport,sort:option.urlSort},dialog:{add:null,addWindow:null,edit:null,editWindow:null,import:null}},option.canAdd&&$grid.on("click","[data-add-button]",function(){var t,e=lister.realtime.url.add;$(this).is("[data-add-copy-button]")&&(t=getId(this),e+=(0<e.indexOf("?")?"&":"?")+"_copyId="+t),lister.realtime.dialog.add=layer.open({type:2,title:option.titleAdd||(option.title?option.lang.add+option.title:option.lang.add),shadeClose:!1,shade:.5,maxmin:!1,scrollbar:!1,area:processArea(option.addDialogSize),content:e,success:function(t,e){lister.realtime.dialog.addWindow=$(t).find("iframe").get(0).contentWindow,lister.realtime.dialog.addWindow.__dialogClose=function(){layer.close(lister.realtime.dialog.add)},lister.realtime.dialog.addWindow.addEventListener("modstart:form.submitted",function(t){0===t.detail.res.code&&layer.close(lister.realtime.dialog.add)})},end:function(){lister.refresh()}})}),option.canEdit&&($lister.find("[data-table]").on("click","[data-edit]",function(){doEdit(this)}),$lister.find("[data-table]").on("click","[data-edit-quick]",function(){var t=$(this).attr("data-edit-quick").split(":"),e=t.shift(),t=t.join(":"),t={_id:getId(this),_action:"itemCellEdit",column:e,value:t};window.api.dialog.loadingOn(),window.api.base.post(lister.realtime.url.edit,t,function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){}}),lister.refresh()})}),$grid.on("grid-item-cell-change",function(t,e){e={_id:getId(e.ele),_action:"itemCellEdit",column:e.column,value:e.value};window.api.dialog.loadingOn(),window.api.base.post(lister.realtime.url.edit,e,function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){}}),lister.refresh()})})),option.canDelete&&$lister.find("[data-table]").on("click","[data-delete]",function(){doDelete(this)}),option.canShow&&$lister.find("[data-table]").on("click","[data-show]",function(){doShow(this)}),$(document).on("click",".layui-table-tips .layui-layer-content [data-delete], .layui-table-tips .layui-layer-content [data-edit], .layui-table-tips .layui-layer-content [data-show]",function(){var o=$(this),t=$(this).closest(".layui-layer-content"),a=t.offset();a.width=t.width(),a.height=t.height(),a.centerY=a.top+a.height/2;t=$grid.offset();t.width=$grid.width(),t.height=$grid.height(),a.left<t.left||a.left>t.left+t.width||a.top<t.top||a.top>t.top+t.height||$grid.find(".layui-table-main [data-index]").each(function(t,e){var i=$(e),e=i.offset();e.top<a.centerY&&e.top+i.height()>a.centerY&&(o.is("[data-delete]")?doDelete(i):o.is("[data-show]")?doShow(i):o.is("[data-edit]")&&doEdit(i))})}),option.canDelete&&option.canBatchDelete&&$lister.find("[data-table]").on("click","[data-batch-delete]",function(){var t=getCheckedIds();t.length?window.api.dialog.confirm(option.lang.confirmDeleteRecords.replace("%d",t.length),function(){window.api.dialog.loadingOn(),window.api.base.post(lister.realtime.url.delete,{_id:t.join(",")},function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){lister.refresh()}})})}):window.api.dialog.tipError(option.lang.pleaseSelectRecords)}),option.canSort&&$lister.find("[data-table]").on("click","[data-sort]",function(){var t=getId(this),e=$(this).attr("data-sort");window.api.dialog.loadingOn(),window.api.base.post(lister.realtime.url.sort,{_id:t,direction:e},function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){lister.refresh()}})})}),option.canExport&&$lister.find("[data-export-button]").on("click",function(){lister.prepareSearch();var t=JSON.stringify(lister.getParam()),t=lister.realtime.url.export+"?_param="+MS.util.urlencode(t);window.open(t,"_blank")}),option.canImport&&$lister.find("[data-import-button]").on("click",function(){lister.realtime.dialog.import=layer.open({type:2,title:option.titleImport||(option.title?option.lang.import+option.title:option.lang.import),shadeClose:!1,shade:.5,maxmin:!1,scrollbar:!1,area:processArea(option.importDialogSize),content:lister.realtime.url.import,success:function(t,e){},end:function(){}})}),$lister.find("[data-table]").on("click","[data-batch-operate]",function(){var t,e=getCheckedIds(),i=$(this).attr("data-batch-operate");e.length?(t=function(){window.api.dialog.loadingOn(),window.api.base.post(i,{_id:e.join(",")},function(t){window.api.dialog.loadingOff(),window.api.base.defaultFormCallback(t,{success:function(t){lister.refresh()}})})},$(this).attr("data-batch-confirm")?window.api.dialog.confirm($(this).attr("data-batch-confirm").replace("%d",e.length),function(){t()}):t()):window.api.dialog.tipError(option.lang.pleaseSelectRecords)}),$lister.find("[data-table]").on("click","[data-batch-dialog-operate]",function(){var t=getCheckedIds(),e=$(this).attr("data-batch-dialog-operate");t.length?MS.dialog.dialog(e+"?_id="+t.join(",")):window.api.dialog.tipError(option.lang.pleaseSelectRecords)}),$lister.data("lister",lister),window.__grids=window.__grids||{instances:{},get:function(t){if("number"==typeof t){var e,i=0;for(e in window.__grids.instances){if(i===t)return window.__grids.instances[e];i++}}return window.__grids.instances[t]}},window.__grids.instances[option.id]={$lister:$lister,lister:lister,getCheckedIds:getCheckedIds,getCheckedItems:getCheckedItems,getId:getId}}),(option.canBatchSelect||option.canSingleSelectItem||option.canMultiSelectItem)&&$(function(){setTimeout(function(){window.__dialogFootSubmiting&&window.__dialogFootSubmiting(function(){var t=window.__grids.instances[option.id].getCheckedIds(),e=window.__grids.instances[option.id].getCheckedItems();window.parent.__dialogSelectIds=t,window.parent.__selectorDialogItems=e,parent.layer.closeAll()})},0)})};MS.GridManager=GridManager}.call(this,__webpack_require__(6))},6:function(t,e){t.exports=window.$}});