(function($){var $w=$(window);$.fn.visible=function(partial,hidden,direction,container){if(this.length<1)
return;direction=direction||'both';var $t=this.length>1?this.eq(0):this,isContained=typeof container!=='undefined'&&container!==null,$c=isContained?$(container):$w,wPosition=isContained?$c.position():0,t=$t.get(0),vpWidth=$c.outerWidth(),vpHeight=$c.outerHeight(),clientSize=hidden===!0?t.offsetWidth*t.offsetHeight:!0;if(typeof t.getBoundingClientRect==='function'){var rec=t.getBoundingClientRect(),tViz=isContained?rec.top-wPosition.top>=0&&rec.top<vpHeight+wPosition.top:rec.top>=0&&rec.top<vpHeight,bViz=isContained?rec.bottom-wPosition.top>0&&rec.bottom<=vpHeight+wPosition.top:rec.bottom>0&&rec.bottom<=vpHeight,lViz=isContained?rec.left-wPosition.left>=0&&rec.left<vpWidth+wPosition.left:rec.left>=0&&rec.left<vpWidth,rViz=isContained?rec.right-wPosition.left>0&&rec.right<vpWidth+wPosition.left:rec.right>0&&rec.right<=vpWidth,vVisible=partial?tViz||bViz:tViz&&bViz,hVisible=partial?lViz||rViz:lViz&&rViz,vVisible=(rec.top<0&&rec.bottom>vpHeight)?!0:vVisible,hVisible=(rec.left<0&&rec.right>vpWidth)?!0:hVisible;if(direction==='both')
return clientSize&&vVisible&&hVisible;else if(direction==='vertical')
return clientSize&&vVisible;else if(direction==='horizontal')
return clientSize&&hVisible}else{var viewTop=isContained?0:wPosition,viewBottom=viewTop+vpHeight,viewLeft=$c.scrollLeft(),viewRight=viewLeft+vpWidth,position=$t.position(),_top=position.top,_bottom=_top+$t.height(),_left=position.left,_right=_left+$t.width(),compareTop=partial===!0?_bottom:_top,compareBottom=partial===!0?_top:_bottom,compareLeft=partial===!0?_right:_left,compareRight=partial===!0?_left:_right;if(direction==='both')
return!!clientSize&&((compareBottom<=viewBottom)&&(compareTop>=viewTop))&&((compareRight<=viewRight)&&(compareLeft>=viewLeft));else if(direction==='vertical')
return!!clientSize&&((compareBottom<=viewBottom)&&(compareTop>=viewTop));else if(direction==='horizontal')
return!!clientSize&&((compareRight<=viewRight)&&(compareLeft>=viewLeft))}}})(jQuery);if('undefined'==typeof _brayworth_)
var _brayworth_=function(){return(_brayworth_)}
$.extend(_brayworth_,{_brayworth_:!0,templates:{},urlwrite:function(_url){if('undefined'==typeof _url)
_url='';return('/'+_url)}});if(typeof _brayworth_=='undefined')
var _brayworth_={};_brayworth_.bootstrapModalPop=function(params){if(/string/.test(typeof params)){var modal=$(this).data('modal');if(/close/i.test(params)){modal.close();return}}
var options={title:'',width:!1,autoOpen:!0,buttons:{},headButtons:{},}
$.extend(options,params);var header=$('<div class="modal-header"><i class="fa fa-times close"></i><h1></h1></div>');var body=$('<div class="modal-body"></div>');body.append(this);var footer=$('<div class="modal-footer text-right"></div>');var modal=$('<div class="modal"></div>');var wrapper=$('<div class="modal-content"></div>');if(options.width)
wrapper.css({'width':'300px'});else wrapper.addClass('modal-content-600');wrapper.append(header).append(body).appendTo(modal);var _el=$(this)
var s=_el.attr('title');$('h1',header).html('').append(s);if(Object.keys(options.buttons).length>0){$.each(options.buttons,function(i,el){var b=$('<button class="button button-raised"></button>')
b.html(i);b.on('click',function(e){el.click.call(modal,e)})
footer.append(b)})
wrapper.append(footer)}
if(Object.keys(options.headButtons).length>0){$.each(options.headButtons,function(i,el){if(!!el.icon)
var b=$('<i class="fa fa-fw pull-right" style="margin-right: 3px; padding-right: 12px; cursor: pointer;"></i>').addClass(el.icon);else var b=$('<button class="button button-raised pull-right"></button>').html(i);if(!!el.title)
b.attr('title',el.title)
b.on('click',function(e){el.click.call(modal,e)})
header.prepend(b)})
header.prepend($('.close',header))}
modal.appendTo('body');$(this).data('modal',modal.modalDialog({afterClose:function(){modal.remove();if(!!options.afterClose&&/function/.test(typeof options.afterClose))
options.afterClose.call(modal)},}))};if(typeof _brayworth_=='undefined')
var _brayworth_={};_brayworth_.browser={}
_brayworth_.browser.isIPhone=navigator.userAgent.toLowerCase().indexOf('iphone')>-1;_brayworth_.browser.isIPad=navigator.userAgent.toLowerCase().indexOf('ipad')>-1;_brayworth_.browser.isChromeOniOS=_brayworth_.browser.isIPhone&&navigator.userAgent.toLowerCase().indexOf('CriOS')>-1;_brayworth_.browser.isMobileDevice=_brayworth_.browser.isIPhone||_brayworth_.browser.isIPad;_brayworth_.hideContext=function(el){var _el=$(el);if(!!_el.data('hide')){if(_el.data('hide')=='hide')
$(el).addClass('hidden');else $(el).remove()}
else{$(el).remove()}}
_brayworth_.hideContexts=function(){$('[data-role="contextmenu"]').each(function(i,el){_brayworth_.hideContext(el)})}
_brayworth_.context=function(){return({root:$('<ul class="menu menu-contextmenu" data-role="contextmenu"></ul>'),items:[],length:0,detachOnHide:!0,create:function(item){var el=$('<li></li>').append(item).appendTo(this.root);this.items.push(el);this.length=this.items.length;return(el)},append:function(item){this.create(item);return(this)},open:function(evt){var css={position:'absolute',top:10,left:$(document).width()-140,}
if(!!evt.pageY)
css.top=Math.max(evt.pageY-10,0);if(!!evt.pageX)
css.left=Math.max(evt.pageX-40,0);if(this.detachOnHide){this.root.css(css).appendTo('body').data('hide','detach')}
else{if(this.root.parent().length<1)
this.root.appendTo('body').data('hide','hide');this.root.css(css).removeClass('hidden')}
var offset=this.root.offset();if(offset.left+this.root.width()>$(window).width()){var l=$(window).width()-this.root.width()-5;this.root.css('left',Math.max(l,2));offset=this.root.offset()}
if(offset.top+this.root.height()>($(window).height()+$(window).scrollTop())){var t=($(window).height()+$(window).scrollTop())-this.root.height()-5;this.root.css('top',Math.max(t,$(window).scrollTop()+2));offset=this.root.offset()}
if(offset.left>($(window).width()-(this.root.width()*2)))
this.root.addClass('menu-contextmenu-right');else this.root.removeClass('menu-contextmenu-right');if(offset.top+(this.root.height()*1.2)>($(window).height()+$(window).scrollTop()))
this.root.addClass('menu-contextmenu-low');else this.root.removeClass('menu-contextmenu-low');return(this)},close:function(){if(this.detachOnHide){this.root.remove()}
else{this.root.addClass('hidden')}
return(this)},remove:function(){return(this.close())},attachTo:function(parent){var _me=this;$(parent).off('click.removeContexts').on('click.removeContexts',function(evt){if($(evt.target).closest('[data-role="contextmenu"]').length>0){if(/^(a)$/i.test(evt.target.nodeName))
return}
_brayworth_.hideContexts()}).on('contextmenu',function(evt){if($(evt.target).closest('[data-role="contextmenu"]').length)
return;_brayworth_.hideContexts();if(evt.shiftKey)
return;if(/^(input|textarea|img|a|select)$/i.test(evt.target.nodeName)||$(evt.target).closest('a').length>0)
return;if($(evt.target).closest('table').data('nocontextmenu')=='yes')
return;if($(evt.target).hasClass('modal')||$(evt.target).closest('.modal').length>0)
return;if($(evt.target).hasClass('ui-widget-overlay')||$(evt.target).closest('.ui-dialog').length>0)
return;if(typeof window.getSelection!="undefined"){var sel=window.getSelection();if(sel.rangeCount){if(sel.anchorNode.parentNode==evt.target){var frag=sel.getRangeAt(0).cloneContents();var text=frag.textContent;if(text.length>0)
return}}}
evt.preventDefault();_me.open(evt)});return(_me)}})};(function(){_brayworth_.growlSuccess=function(params){var options={growlClass:'success'}
if(/object/.test(typeof params))
$.extend(options,params);else if(/string/i.test(typeof params))
options.text=params;_brayworth_.growl.call(this,options)}
_brayworth_.growlError=function(params){var options={growlClass:'error'}
if(/object/.test(typeof params))
$.extend(options,params);else if(/string/i.test(typeof params))
options.text=params;_brayworth_.growl.call(this,options)}
_brayworth_.growlAjax=function(j){var options={growlClass:'error',text:'no description'}
if(!!j.response){if(j.response=='ack')
options.growlClass='success'}
if(!!j.description)
options.text=j.description;if(!!j.timeout)
options.timeout=j.timeout;_brayworth_.growl.call(this,options)}
var growlers=[];_brayworth_.growl=function(params){var host=(this==_brayworth_?$('body'):this)
if('string'==typeof this)
host=$(host);else if(this instanceof String)
host=$(host.valueOf());else if('object'==typeof this&&!!this.xhr)
host=$('body');else if(!(this instanceof jQuery))
host=$(host);var options={top:60,right:20,text:'',title:'',timeout:2000,growlClass:'information',}
if(/object/.test(typeof params))
$.extend(options,params);else if(/string/i.test(typeof params))
options.text=params;if(options.title==''&&options.text=='')
return;var growler=$('<div class="growler"></div>');var growlerIndex=-1
$.each(growlers,function(i,e){if(!e){growlerIndex=i;growlers[growlerIndex]=growler;return(!1)}});if(growlerIndex<0){growlerIndex=growlers.length;growlers[growlerIndex]=growler}
if(host[0].tagName=='BODY'||host.css('position')!='static'){options.top*=growlerIndex}
else{try{var offset=host.offset();options.top=offset.top-20;options.right=Math.min($(window).width(),offset.left+host.width()+20)}
catch(e){console.warn(host,e)}}
options.top=Math.max(options.top,$(window).scrollTop());var title=$('<h3></h3>');var content=$('<div></div>');if(options.title!='')
title.html(options.title).appendTo(growler);else content.css('padding-top','5px');if(options.text!='')
content.html(options.text).appendTo(growler);growler.css({'position':'absolute','top':options.top,'right':options.right}).addClass(options.growlClass).appendTo(host);setTimeout(function(){growlers[growlerIndex]=!1;growler.remove()},options.timeout)}})();_brayworth_.fileDragDropContainer=function(){var c=$('<div>&nbsp;</div>');var _c=$('<div class="box__uploading"></div>').appendTo(c);var __c=$('<div class="box__fill text-center">uploading</div>').appendTo(_c);$('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>').appendTo(__c);return(c)}
_brayworth_.fileDragDropHandler=function(params){var _el=$(this);var options={url:!1,postData:{},onUpload:function(response){},}
$.extend(options,params);if(!options.url)
throw 'Invalid upload url';var isAdvancedUpload=(function(){var div=document.createElement('div');return(('draggable' in div)||('ondragstart' in div&&'ondrop' in div))&&'FormData' in window&&'FileReader' in window})();if(isAdvancedUpload&&!_el.hasClass('has-advanced-upload')){_el.addClass('has-advanced-upload').on('drag dragstart dragend dragover dragenter dragleave drop',function(e){e.preventDefault();e.stopPropagation()}).on('dragover dragenter',function(){$(this).addClass('is-dragover')}).on('dragleave dragend drop',function(){$(this).removeClass('is-dragover')}).on('drop',function(e){e.preventDefault();var droppedFiles=e.originalEvent.dataTransfer.files;if(droppedFiles){var data=new FormData();for(var o in options.postData)
data.append(o,options.postData[o]);$.each(droppedFiles,function(i,file){data.append('files-'+i,file)});$('.box__fill',_el).css('width','0');_el.addClass('is-uploading');$.ajax({url:options.handler,type:'POST',data:data,dataType:'json',cache:!1,contentType:!1,processData:!1,xhr:function(){var xhr=new window.XMLHttpRequest();xhr.upload.addEventListener("progress",function(e){if(e.lengthComputable)
$('.box__fill',_el).css('width',(e.loaded/e.total*100)+'%')})
return xhr}}).done(function(response){if(response.response=='ack'){$.each(response.data,function(i,j){$('body').growlAjax(j)})}
else{$('body').growlAjax(response)}
options.onUpload(response)}).always(function(r){_el.removeClass('is-uploading')}).fail(function(r){console.warn(r);_brayworth_.modal({title:'Upload Error',text:'there was an error uploading the attachments<br />we recommend you reload your browser'})})}})}}
_brayworth_.ScrollTo=function(el){var _el=(el instanceof jQuery?el:$(el));var t=_el.offset().top;var nav=$('body>nav');if(nav.length){t-=(nav.height())}
else{var hdr=$('body>header');if(hdr.length)
t-=(hdr.height())}
t=Math.max(20,t);$('html, body').animate({scrollTop:t},1000)}
_brayworth_.hashScroll=function(){$('a[href*="#"]:not([href="#"] , .carousel-control, .ui-tabs-anchor)').on('click',function(){if(location.pathname.replace(/^\//,'')==this.pathname.replace(/^\//,'')&&location.hostname==this.hostname){var target=$(this.hash);target=target.length?target:$('[name='+this.hash.slice(1)+']');if(target.length){if(/nav/i.test(target.prop('tagName')))
return;_brayworth_.ScrollTo(target);return!1}}})};_brayworth_.InitHRefs=function(){$('[data-href]').each(function(i,el){$(el).css({'cursor':'pointer'}).off('click').on('click',function(e){if(/^(a)$/i.test(e.target.nodeName))
return;e.stopPropagation();e.preventDefault();if($(e.target).closest('[data-role="contextmenu"]').length>0)
_brayworth_.hideContext($(e.target).closest('[data-role="contextmenu"]')[0]);var target=$(this).data('target');if(target==''||target==undefined)
window.location.href=$(this).data('href');else window.open($(this).data('href'),target)})})};if(typeof _brayworth_=='undefined')
var _brayworth_={};_brayworth_.initDatePickers=function(parent){if($.fn.datepicker){if(!parent)
parent='body';$('.datepicker',parent).each(function(i,el){var bootstrap=(typeof $().scrollspy=='function');var df=$(el).data('dateformat');if(df==undefined){if(bootstrap)
df='yyyy-mm-dd';else if(jQuery.ui)
df='yy-mm-dd'}
if(bootstrap)
$(el).datepicker({format:df});else if(jQuery.ui)
$(el).datepicker({dateFormat:df})})}};_brayworth_.lazyImageLoader=function(){var imgStack=[];$('div[data-delayedimg="true"]').each(function(i,el){var _=$(el);if(_.visible(!0))
_.css({'background-image':'url("'+_.data('src')+'")'}).data('delayedimg',!1);else imgStack.push(_)})
if(imgStack.length>0){$(document).on('scroll',function(e){var unProcessed=0;$.each(imgStack,function(i,el){var _=$(el);if(_.data('delayedimg')){if(_.visible(!0)){_.css({'background-image':'url("'+_.data('src')+'")'}).data('delayedimg',!1)}
else{unProcessed ++}}})
if(unProcessed<1){$(document).off('scroll')}})}};_brayworth_.logonModal=function(){var flds={user:$('<input type="text" class="form-control" placeholder="username" />'),pass:$('<input type="password" class="form-control" placeholder="password" />'),}
var dlg=$('<div class="container" />');var form=$('<form class="form" />').appendTo(dlg);$('<div class="row py-1" />').append($('<div class="col" />').append(flds.user)).appendTo(form);$('<div class="row py-1" />').append($('<div class="col" />').append(flds.pass)).appendTo(form);function submitter(){var u=flds.user.val();var p=flds.pass.val();if(u.trim()==''){$('body').growlError('empty user');flds.user.focus();return}
if(p.trim()==''){$('body').growlError('empty pass');flds.pass.focus();return}
modal.modal('close');$.ajax({type:'post',url:_brayworth_.urlwrite(),data:{action:'-system-logon-',u:u,p:p,}}).done(function(d){$('body').growlAjax(d);if(!!d.response&&d.response=='ack')
window.location.reload();else setTimeout(_brayworth_.logonModal,2000)})}
form.on('submit',function(){submitter();return!1}).append('<input type="submit" style="display: none;" />');var modal=_brayworth_.modal({width:300,title:'logon',text:dlg,buttons:{logon:submitter}})};_brayworth_.modal=function(params){jQuery.fn.modal=_brayworth_.modal;if('string'==typeof params){var _m=$(this).data('modal');if('close'==params)
_m.close();return}
var options={title:'',width:!1,mobile:_brayworth_.browser.isMobileDevice,fullScreen:_brayworth_.browser.isMobileDevice,className:'',autoOpen:!0,buttons:{},headButtons:{},closeIcon:'fa-times',onOpen:function(){},}
$.extend(options,params);var t=_brayworth_.templates.modal();if(options.className!='')
t.get().addClass(options.className);t.get('.close').addClass(options.closeIcon);if(!!options.width)
t.get('.modal-content').width(options.width);else t.get('.modal-content').addClass(_brayworth_.templates.modalDefaultClass);var content=(!!options.text?options.text:'');if('undefined'!=typeof this){if(!this._brayworth_){var content=(this instanceof jQuery?this:$(this));if(options.title==''&&('string'==typeof content.attr('title')))
options.title=content.attr('title')}}
t.html('H1','').append(options.title);t.append(content);if(Object.keys(options.buttons).length>0){$.each(options.buttons,function(i,el){var j={text:i,click:function(e){}}
if('function'==typeof el)
j.click=el;else $.extend(j,el);$('<button></button>').addClass(_brayworth_.templates.buttonCSS).html(j.text).on('click',function(e){j.click.call(t.get(),e)}).appendTo(t.footer())})}
if(Object.keys(options.headButtons).length>0){$.each(options.headButtons,function(i,el){var j={text:i,title:!1,icon:!1,click:function(e){},}
if('function'==typeof el)
j.click=el;else $.extend(j,el);if(!!j.icon)
var b=$('<i class="fa fa-fw pull-right" style="margin-right: 3px; padding-right: 12px; cursor: pointer;"></i>').addClass(j.icon);else var b=$('<button class="pull-right"></button>').html(j.text).addClass(_brayworth_.templates.buttonCSS);if(!!j.title)
b.attr('title',j.title)
b.on('click',function(e){j.click.call(t.get(),e)})
t.header.prepend(b)})
t.header.prepend($('.close',t.header))}
var previousElement=document.activeElement;var bodyElements=[];if(options.fullScreen){$('body > *').each(function(i,el){var _el=$(el);if(!_el.hasClass('hidden')){_el.addClass('hidden');bodyElements.push(_el)}})
t.get('.modal-content').css({'width':'auto','margin':0})}
t.appendTo('body');var _modal=_brayworth_.modalDialog.call(t.get(),{mobile:options.mobile,onOpen:options.onOpen,afterClose:function(){t.get().remove();if(!!options.afterClose&&'function'==typeof options.afterClose)
options.afterClose.call(t.modal);$.each(bodyElements,function(i,el){$(el).removeClass('hidden')})
previousElement.focus()},})
t.data('modal',_modal);if('undefined'!=typeof this&&!this._brayworth_){if(this instanceof jQuery)
this.data('modal',_modal);else $(this).data('modal',_modal)}
return(t.data('modal'))}
_brayworth_.templates.buttonCSS='btn btn-default';_brayworth_.templates.modalDefaultClass='';_brayworth_.templates.modal=function(){var _=templation.template('modal');_.header=_.get('.modal-header');_.body=_.get('.modal-body');_.append=function(p){this.body.append(p);return(this)}
_.footer=function(){if(!this._footer){this._footer=$('<div class="modal-footer text-right"></div>');this.get('.modal-content').append(this._footer)}
return(this._footer)};return(_)}
$.fn.modalDialog=_brayworth_.modalDialog=function(_options){if(/string/.test(typeof(_options))){if(_options=='close'){var modal=this.data('modal');modal.close();return(modal)}}
var modal=this;var options={mobile:_brayworth_.browser.isMobileDevice,beforeClose:function(){},afterClose:function(){},onEnter:function(){},onOpen:function(){},};$.extend(options,_options);var close=$('.close',this);modal.close=function(){options.beforeClose.call(modal);modal.css('display','none');options.afterClose.call(modal);modal=!1;$(document).unbind('keyup.modal');$(document).unbind('keypress.modal')}
if(options.mobile)
modal.addClass('modal-mobile');modal.css('display','block').data('modal',modal);var _AF=$('[autofocus]',modal);if(_AF.length>0){_AF.first().focus()}
else{_AF=$('textarea, input, button',modal);if(_AF.length>0)
_AF.first().focus()}
$(document).on('keyup.modal',function(e){if(e.keyCode==27){if(modal)
modal.close()}}).on('keypress.modal',function(e){if(e.keyCode==13)
options.onEnter.call(modal,e)})
close.off('click').css({cursor:'pointer'}).on('click',function(e){modal.close()});options.onOpen.call(modal);return(modal)}
_brayworth_.swipeOff=function(){$(this).off('mousedown touchstart').off('mouseup touchend')};_brayworth_.swipeOn=function(params){var options={left:function(){},right:function(){},up:function(){},down:function(){},}
$.extend(options,params);var down=!1;var touchEvent=function(e){var _touchEvent=function(x,y){return({'x':x,'y':y})}
var evt=e.originalEvent;try{if('undefined'!==typeof evt.pageX){return(_touchEvent(evt.pageX,evt.pageY))}
else if('undefined'!==typeof evt.touches){if(evt.touches.length>0)
return(_touchEvent(evt.touches[0].pageX,evt.touches[0].pageY));else return(_touchEvent(evt.changedTouches[0].pageX,evt.changedTouches[0].pageY))}}
catch(e){console.warn(e)}
return(_touchEvent(0,0))}
var swipeEvent=function(down,up){var j={'direction':'',x:up.x-down.x,y:up.y-down.y}
if(j.x>70)
j.direction='right'
else if(j.x<-70)
j.direction='left'
return(j)}
$(this).on('mousedown touchstart',function(e){if(/^(input|textarea|img|a|select)$/i.test(e.target.nodeName))
return;down=touchEvent(e)}).on('mouseup touchend',function(e){if(down){var sEvt=swipeEvent(down,touchEvent(e));down=!1;if(sEvt.direction=='left')
options.left();else if(sEvt.direction=='right')
options.right()}})};(function(){String.prototype.trim=function(){return this.replace(/^\s+|\s+$/g,"")}
String.prototype.ltrim=function(){return this.replace(/^\s+/,"")}
String.prototype.rtrim=function(){return this.replace(/\s+$/,"")}
String.prototype.pad=function(len,padChar){if(padChar==undefined){padChar=" "}
if(isNaN(len)){len=this.length}
var res=this;while(res.length<len){res=res.concat(padChar)}
return(res)};String.prototype.padLeft=function(len,padChar){if(padChar==undefined){padChar=" "}
if(isNaN(len)){len=this.length}
var res=this;if(res.length>len){var iStart=(res.length-len);res=res.substring(iStart)}else{while(res.length<len){res=padChar.concat(res)}}
return(res)};String.prototype.format=function(){var args=arguments;return this.replace(/\{\{|\}\}|\{(\d+)\}/g,function(m,n){if(m=="{{"){return "{"}
if(m=="}}"){return "}"}
return args[n]})};String.prototype.toCapitalCase=function(){var re=/\s/;var words=this.split(re);re=/(\S)(\S+)/;var reI=/^[a-zA-Z]'[a-zA-Z]+$/;for(var i=words.length-1;i>=0;i--){if(words[i]!="&"){if(words[i].length>3&&reI.test(words[i])){parts=words[i].split(/'/);words[i]=parts[0].toUpperCase()+"'"+parts[1].substring(0,1).toUpperCase()+parts[1].substring(1).toLowerCase()}else if(re.test(words[i])){words[i]=RegExp.$1.toUpperCase()+RegExp.$2.toLowerCase()}}}
return words.join(' ')}
String.prototype.AsLocalPhone=function(){var p=this;var ns=this.replace(/\s+|\(|\)|\-/g,"");if(ns.length==8){if(!ns.substring(0,1)=='0')
ns=fallback_area_code+ns}
if(ns.length==10){re=/(\S\S)(\S\S\S\S)(\S+)/;if(re.test(ns)){if(!!useCompactPhoneFormat)
ns=RegExp.$1+" "+RegExp.$2+""+RegExp.$3;else ns=RegExp.$1+" "+RegExp.$2+" "+RegExp.$3;return(ns)}}else if(/^0011/.test(ns)){re=/(\S\S\S\S)(\S\S)(\S+)/;if(re.test(ns)){ns=RegExp.$1+" "+RegExp.$2+" "+RegExp.$3;return(ns)}}
return(p)};String.prototype.IsMobilePhone=function(){var p=this;var ns=this.replace(/\s+|\(|\)|\-/g,"");if(ns.length==10)
return(!0);else if(/^(\+|0011)/.test(ns)){re=/(\S\S\S\S)(\S+)/;if(re.test(ns))
return(!0)}
return(!1)};String.prototype.AsMobilePhone=function(){var p=this;var ns=this.replace(/\s+|\(|\)|\-/g,"");if(ns.length==10){re=/(\S\S\S\S)(\S\S\S)(\S+)/;if(re.test(ns)){if(!!useCompactPhoneFormat)
ns=RegExp.$1+" "+RegExp.$2+''+RegExp.$3;else ns=RegExp.$1+" "+RegExp.$2+' '+RegExp.$3;return(ns)}}
else if(/^(\+|0011)/.test(ns)){re=/(\S\S\S\S)(\S+)/;if(re.test(ns)){ns=RegExp.$1+" "+RegExp.$2;return(ns)}}
return(p)};String.prototype.isEmail=function(){if(this.length<3)
return(!1);var emailReg=/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;return emailReg.test(this)}
Number.prototype.formatComma=function(x){return this.toString().replace(/\B(?=(\d{3})+(?!\d))/g,",")}
Number.prototype.formatCurrency=function(){var parts=this.toString().split(".");if(parts.length<2)
parts.push('00');else if(parts[1].length<1)
parts[1]+='00';else if(parts[1].length<2)
parts[1]+='0';parts[0]=parts[0].replace(/\B(?=(\d{3})+(?!\d))/g,",");return parts.join(".")}})();(function(){if(!1){if('serviceWorker' in navigator){if(/^https:/.test(window.location.href)){navigator.serviceWorker.register('/js/service-worker.js').then(function(){})}}}})();$(document).ready(function(){_brayworth_.InitHRefs();_brayworth_.initDatePickers();$('[data-role="back-button"]').each(function(i,el){$(el).css('cursor','pointer').on('click',function(evt){evt.stopPropagation();evt.preventDefault();window.history.back()})})
$('[data-role="visibility-toggle"]').each(function(i,el){var _el=$(el);var target=_el.data('target');var oT=$('#'+target);if(oT){_el.css('cursor','pointer').on('click',function(evt){evt.stopPropagation();evt.preventDefault();oT.toggle()})}})
$('[role="print-page"]').each(function(i,el){$(el).on('click',function(e){e.preventDefault();window.print()})})});(function($){$.fn.serializeFormJSON=function(){var o={};var a=this.serializeArray();$.each(a,function(){if(o[this.name]){if(!o[this.name].push)
o[this.name]=[o[this.name]];o[this.name].push(this.value||'')}
else o[this.name]=this.value||''});return o};$.fn.growlSuccess=_brayworth_.growlSuccess;$.fn.growlError=_brayworth_.growlError;$.fn.growlAjax=_brayworth_.growlAjax;$.fn.growl=_brayworth_.growl;$.fn.swipeOn=_brayworth_.swipeOn;$.fn.swipeOff=_brayworth_.swipeOff})(jQuery);var templation={urlwrite:function(_url){if('undefined'==typeof _url)
_url='';return('/'+_url)}};(function(){var cache={container:'<div class="container"></div>',row:'<div class="row"></div>',form:'<form></form>',table:'<table><thead></thead><tbody></tbody><tfoot></tfoot></table>',tr:'<tr></tr>',modal:'<div class="modal"><div class="modal-content" role="dialog" aria-labelledby="modal-header-title"><div class="modal-header"><i class="fa close"></i><h1 id="modal-header-title"></h1></div><div class="modal-body"></div></div></div>',};function _t(src){var _={src:src,_element:!1,get:function(k){if('undefined'==typeof k)
return(this._element);else return $(k,this._element)},data:function(k,v){if('undefined'==typeof v)
return(this._element.data(k));else return(this._element.data(k,v))},html:function(k,v){var e=this.get(k);if('undefined'!=typeof(v))
return(e.html(v));else return(e.html())},val:function(k,v){var e=this.get(k);if('undefined'!=typeof(v))
return(e.val(v));else return(e.val())},append:function(p){this._element.append(p);return(this)},appendTo:function(p){this._element.appendTo(p);return(this)},prependTo:function(p){this._element.prependTo(p);return(this)},remove:function(p){this._element.remove();return(this)},reset:function(){this._element=$(this.src);return(this)}}
return(_.reset())}
templation.template=function(name){if(name in cache)
return(_t(cache[name]));else throw 'template not in cache'}
templation.loadHTML=function(key,fragment){cache[key]=fragment;return(_t(fragment))}
templation.load=function(params){return(new Promise(function(resolve,reject){var options={type:'post',template:'',url:templation.urlwrite(),}
$.extend(options,params);if(!options.template){reject('no template')}
else if('string'!=typeof options.template){reject('template must be a string')}
else{if(options.template in cache){resolve(_t(cache[options.template]))}
else{$.ajax({type:options.type,url:options.url,data:{action:'get-template',template:options.template,}}).done(function(d){cache[options.template]=d;resolve(_t(d))})}}}))}})();(function(factory){var registeredInModuleLoader=!1;if(typeof define==='function'&&define.amd){define(factory);registeredInModuleLoader=!0}
if(typeof exports==='object'){module.exports=factory();registeredInModuleLoader=!0}
if(!registeredInModuleLoader){var OldCookies=window.Cookies;var api=window.Cookies=factory();api.noConflict=function(){window.Cookies=OldCookies;return api}}}(function(){function extend(){var i=0;var result={};for(;i<arguments.length;i++){var attributes=arguments[i];for(var key in attributes){result[key]=attributes[key]}}
return result}
function init(converter){function api(key,value,attributes){var result;if(typeof document==='undefined'){return}
if(arguments.length>1){attributes=extend({path:'/'},api.defaults,attributes);if(typeof attributes.expires==='number'){var expires=new Date();expires.setMilliseconds(expires.getMilliseconds()+attributes.expires*864e+5);attributes.expires=expires}
attributes.expires=attributes.expires?attributes.expires.toUTCString():'';try{result=JSON.stringify(value);if(/^[\{\[]/.test(result)){value=result}}catch(e){}
if(!converter.write){value=encodeURIComponent(String(value)).replace(/%(23|24|26|2B|3A|3C|3E|3D|2F|3F|40|5B|5D|5E|60|7B|7D|7C)/g,decodeURIComponent)}else{value=converter.write(value,key)}
key=encodeURIComponent(String(key));key=key.replace(/%(23|24|26|2B|5E|60|7C)/g,decodeURIComponent);key=key.replace(/[\(\)]/g,escape);var stringifiedAttributes='';for(var attributeName in attributes){if(!attributes[attributeName]){continue}
stringifiedAttributes+='; '+attributeName;if(attributes[attributeName]===!0){continue}
stringifiedAttributes+='='+attributes[attributeName]}
return(document.cookie=key+'='+value+stringifiedAttributes)}
if(!key){result={}}
var cookies=document.cookie?document.cookie.split('; '):[];var rdecode=/(%[0-9A-Z]{2})+/g;var i=0;for(;i<cookies.length;i++){var parts=cookies[i].split('=');var cookie=parts.slice(1).join('=');if(cookie.charAt(0)==='"'){cookie=cookie.slice(1,-1)}
try{var name=parts[0].replace(rdecode,decodeURIComponent);cookie=converter.read?converter.read(cookie,name):converter(cookie,name)||cookie.replace(rdecode,decodeURIComponent);if(this.json){try{cookie=JSON.parse(cookie)}catch(e){}}
if(key===name){result=cookie;break}
if(!key){result[name]=cookie}}catch(e){}}
return result}
api.set=api;api.get=function(key){return api.call(api,key)};api.getJSON=function(){return api.apply({json:!0},[].slice.call(arguments))};api.defaults={};api.remove=function(key,attributes){api(key,'',extend(attributes,{expires:-1}))};api.withConverter=init;return api}
return init(function(){})}))