function
qs(selector,context){return(context||document).querySelector(selector);}function
qsl(selector,context){var
els=qsa(selector,context);return els[els.length-1];}function
qsa(selector,context){return(context||document).querySelectorAll(selector);}function
partial(fn){var
args=Array.apply(null,arguments).slice(1);return function(){return fn.apply(this,args);};}function
partialArg(fn){var
args=Array.apply(null,arguments);return function(arg){args[0]=arg;return fn.apply(this,args);};}function
mixin(target,source){for(var
key
in
source){target[key]=source[key];}}function
alterClass(el,className,enable){if(el){el.className=el.className.replace(RegExp('(^|\\s)'+className+'(\\s|$)'),'$2')+(enable?' '+className:'');}}function
toggle(id){var
el=qs('#'+id);el.className=(el.className=='hidden'?'':'hidden');return false;}function
cookie(assign,days){var
date=new
Date();date.setDate(date.getDate()+days);document.cookie=assign+'; expires='+date;}function
verifyVersion(current,url,token){cookie('adminer_version=0',1);var
iframe=document.createElement('iframe');iframe.src='https://www.adminer.org/version/?current='+current;iframe.frameBorder=0;iframe.marginHeight=0;iframe.scrolling='no';iframe.style.width='7ex';iframe.style.height='1.25em';if(window.postMessage&&window.addEventListener){iframe.style.display='none';addEventListener('message',function(event){if(event.origin=='https://www.adminer.org'){var
match=/version=(.+)/.exec(event.data);if(match){cookie('adminer_version='+match[1],1);ajax(url+'script=version',function(){},event.data+'&token='+token);}}},false);}qs('#version').appendChild(iframe);}function
selectValue(select){if(!select.selectedIndex){return select.value;}var
selected=select.options[select.selectedIndex];return((selected.attributes.value||{}).specified?selected.value:selected.text);}function
isTag(el,tag){var
re=new
RegExp('^('+tag+')$','i');return el&&re.test(el.tagName);}function
parentTag(el,tag){while(el&&!isTag(el,tag)){el=el.parentNode;}return el;}function
trCheck(el){var
tr=parentTag(el,'tr');alterClass(tr,'checked',el.checked);if(el.form&&el.form['all']&&el.form['all'].onclick){el.form['all'].onclick();}}function
selectCount(id,count){setHtml(id,(count===''?'':'('+(count+'').replace(/\B(?=(\d{3})+$)/g,thousandsSeparator)+')'));var
el=qs('#'+id);if(el){var
inputs=qsa('input',el.parentNode.parentNode);for(var
i=0;i<inputs.length;i++){var
input=inputs[i];if(input.type=='submit'){input.disabled=(count=='0');}}}}function
formCheck(name){var
elems=this.form.elements;for(var
i=0;i<elems.length;i++){if(name.test(elems[i].name)){elems[i].checked=this.checked;trCheck(elems[i]);}}}function
tableCheck(){var
inputs=qsa('table.checkable td:first-child input');for(var
i=0;i<inputs.length;i++){trCheck(inputs[i]);}}function
formUncheck(id){var
el=qs('#'+id);el.checked=false;trCheck(el);}function
formChecked(el,name){var
checked=0;var
elems=el.form.elements;for(var
i=0;i<elems.length;i++){if(name.test(elems[i].name)&&elems[i].checked){checked++;}}return checked;}function
tableClick(event,click){var
td=parentTag(getTarget(event),'td');var
text;if(td&&(text=td.getAttribute('data-text'))){if(selectClick.call(td,event,+text,td.getAttribute('data-warning'))){return;}}click=(click||!window.getSelection||getSelection().isCollapsed);var
el=getTarget(event);while(!isTag(el,'tr')){if(isTag(el,'table|a|input|textarea')){if(el.type!='checkbox'){return;}checkboxClick.call(el,event);click=false;}el=el.parentNode;if(!el){return;}}el=el.firstChild.firstChild;if(click){el.checked=!el.checked;el.onclick&&el.onclick();}if(el.name=='check[]'){el.form['all'].checked=false;formUncheck('all-page');}if(/^(tables|views)\[\]$/.test(el.name)){formUncheck('check-all');}trCheck(el);}var
lastChecked;function
checkboxClick(event){if(!this.name){return;}if(event.shiftKey&&(!lastChecked||lastChecked.name==this.name)){var
checked=(lastChecked?lastChecked.checked:true);var
inputs=qsa('input',parentTag(this,'table'));var
checking=!lastChecked;for(var
i=0;i<inputs.length;i++){var
input=inputs[i];if(input.name===this.name){if(checking){input.checked=checked;trCheck(input);}if(input===this||input===lastChecked){if(checking){break;}checking=true;}}}}else{lastChecked=this;}}function
setHtml(id,html){var
el=qs('[id="'+id.replace(/[\\"]/g,'\\$&')+'"]');if(el){if(html==null){el.parentNode.innerHTML='';}else{el.innerHTML=html;}}}function
nodePosition(el){var
pos=0;while(el=el.previousSibling){pos++;}return pos;}function
pageClick(href,page){if(!isNaN(page)&&page){location.href=href+(page!=1?'&page='+(page-1):'');}}function
menuOver(event){var
a=getTarget(event);if(isTag(a,'a|span')&&a.offsetLeft+a.offsetWidth>a.parentNode.offsetWidth-15){this.style.overflow='visible';}}function
menuOut(){this.style.overflow='auto';}function
selectAddRow(){var
field=this;var
row=cloneNode(field.parentNode);field.onchange=selectFieldChange;field.onchange();var
selects=qsa('select',row);for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/[a-z]\[\d+/,'$&1');selects[i].selectedIndex=0;}var
inputs=qsa('input',row);for(var
i=0;i<inputs.length;i++){inputs[i].name=inputs[i].name.replace(/[a-z]\[\d+/,'$&1');inputs[i].className='';if(inputs[i].type=='checkbox'){inputs[i].checked=false;}else{inputs[i].value='';}}field.parentNode.parentNode.appendChild(row);}function
selectSearchKeydown(event){if(event.keyCode==13||event.keyCode==10){this.onsearch=function(){};}}function
selectSearchSearch(){if(!this.value){this.parentNode.firstChild.selectedIndex=0;}}function
columnMouse(className){var
spans=qsa('span',this);for(var
i=0;i<spans.length;i++){if(/column/.test(spans[i].className)){spans[i].className='column'+(className||'');}}}function
selectSearch(name){var
el=qs('#fieldset-search');el.className='';var
divs=qsa('div',el);for(var
i=0;i<divs.length;i++){var
div=divs[i];var
el=qs('[name$="[col]"]',div);if(el&&selectValue(el)==name){break;}}if(i==divs.length){div.firstChild.value=name;div.firstChild.onchange();}qs('[name$="[val]"]',div).focus();return false;}function
isCtrl(event){return(event.ctrlKey||event.metaKey)&&!event.altKey;}function
getTarget(event){return event.target||event.srcElement;}function
bodyKeydown(event,button){eventStop(event);var
target=getTarget(event);if(target.jushTextarea){target=target.jushTextarea;}if(isCtrl(event)&&(event.keyCode==13||event.keyCode==10)&&isTag(target,'select|textarea|input')){target.blur();if(button){target.form[button].click();}else{if(target.form.onsubmit){target.form.onsubmit();}target.form.submit();}target.focus();return false;}return true;}function
bodyClick(event){var
target=getTarget(event);if((isCtrl(event)||event.shiftKey)&&target.type=='submit'&&isTag(target,'input')){target.form.target='_blank';setTimeout(function(){target.form.target='';},0);}}function
editingKeydown(event){if((event.keyCode==40||event.keyCode==38)&&isCtrl(event)){var
target=getTarget(event);var
sibling=(event.keyCode==40?'nextSibling':'previousSibling');var
el=target.parentNode.parentNode[sibling];if(el&&(isTag(el,'tr')||(el=el[sibling]))&&isTag(el,'tr')&&(el=el.childNodes[nodePosition(target.parentNode)])&&(el=el.childNodes[nodePosition(target)])){el.focus();}return false;}if(event.shiftKey&&!bodyKeydown(event,'insert')){return false;}return true;}function
functionChange(){var
input=this.form[this.name.replace(/^function/,'fields')];if(input){if(selectValue(this)){if(input.origType===undefined){input.origType=input.type;input.origMaxLength=input.getAttribute('data-maxlength');}input.removeAttribute('data-maxlength');input.type='text';}else
if(input.origType){input.type=input.origType;if(input.origMaxLength>=0){input.setAttribute('data-maxlength',input.origMaxLength);}}oninput({target:input});}helpClose();}function
skipOriginal(first){var
fnSelect=this.previousSibling.firstChild;if(fnSelect.selectedIndex<first){fnSelect.selectedIndex=first;}}function
fieldChange(){var
row=cloneNode(parentTag(this,'tr'));var
inputs=qsa('input',row);for(var
i=0;i<inputs.length;i++){inputs[i].value='';}parentTag(this,'table').appendChild(row);this.oninput=function(){};}function
ajax(url,callback,data,message){var
request=(window.XMLHttpRequest?new
XMLHttpRequest():(window.ActiveXObject?new
ActiveXObject('Microsoft.XMLHTTP'):false));if(request){var
ajaxStatus=qs('#ajaxstatus');if(message){ajaxStatus.innerHTML='<div class="message">'+message+'</div>';ajaxStatus.className=ajaxStatus.className.replace(/ hidden/g,'');}else{ajaxStatus.className+=' hidden';}request.open((data?'POST':'GET'),url);if(data){request.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}request.setRequestHeader('X-Requested-With','XMLHttpRequest');request.onreadystatechange=function(){if(request.readyState==4){if(/^2/.test(request.status)){callback(request);}else{ajaxStatus.innerHTML=(request.status?request.responseText:'<div class="error">'+offlineMessage+'</div>');ajaxStatus.className=ajaxStatus.className.replace(/ hidden/g,'');}}};request.send(data);}return request;}function
ajaxSetHtml(url){return!ajax(url,function(request){var
data=window.JSON?JSON.parse(request.responseText):eval('('+request.responseText+')');for(var
key
in
data){setHtml(key,data[key]);}});}function
ajaxForm(form,message,button){var
data=[];var
els=form.elements;for(var
i=0;i<els.length;i++){var
el=els[i];if(el.name&&!el.disabled){if(/^file$/i.test(el.type)&&el.value){return false;}if(!/^(checkbox|radio|submit|file)$/i.test(el.type)||el.checked||el==button){data.push(encodeURIComponent(el.name)+'='+encodeURIComponent(isTag(el,'select')?selectValue(el):el.value));}}}data=data.join('&');var
url=form.action;if(!/post/i.test(form.method)){url=url.replace(/\?.*/,'')+'?'+data;data='';}return ajax(url,function(request){setHtml('ajaxstatus',request.responseText);if(window.jush){jush.highlight_tag(qsa('code',qs('#ajaxstatus')),0);}messagesPrint(qs('#ajaxstatus'));},data,message);}function
selectClick(event,text,warning){var
td=this;var
target=getTarget(event);if(!isCtrl(event)||isTag(td.firstChild,'input|textarea')||isTag(target,'a')){return;}if(warning){alert(warning);return true;}var
original=td.innerHTML;text=text||/\n/.test(original);var
input=document.createElement(text?'textarea':'input');input.onkeydown=function(event){if(!event){event=window.event;}if(event.keyCode==27&&!event.shiftKey&&!event.altKey&&!isCtrl(event)){inputBlur.apply(input);td.innerHTML=original;}};var
pos=event.rangeOffset;var
value=(td.firstChild&&td.firstChild.alt)||td.textContent||td.innerText;input.style.width=Math.max(td.clientWidth-14,20)+'px';if(text){var
rows=1;value.replace(/\n/g,function(){rows++;});input.rows=rows;}if(qsa('i',td).length){value='';}if(document.selection){var
range=document.selection.createRange();range.moveToPoint(event.clientX,event.clientY);var
range2=range.duplicate();range2.moveToElementText(td);range2.setEndPoint('EndToEnd',range);pos=range2.text.length;}td.innerHTML='';td.appendChild(input);setupSubmitHighlight(td);input.focus();if(text==2){return ajax(location.href+'&'+encodeURIComponent(td.id)+'=',function(request){if(request.responseText){input.value=request.responseText;input.name=td.id;}});}input.value=value;input.name=td.id;input.selectionStart=pos;input.selectionEnd=pos;if(document.selection){var
range=document.selection.createRange();range.moveEnd('character',-input.value.length+pos);range.select();}return true;}function
selectLoadMore(limit,loading){var
a=this;var
title=a.innerHTML;var
href=a.href;a.innerHTML=loading;if(href){a.removeAttribute('href');return!ajax(href,function(request){var
tbody=document.createElement('tbody');tbody.innerHTML=request.responseText;qs('#table').appendChild(tbody);if(tbody.children.length<limit){a.parentNode.removeChild(a);}else{a.href=href.replace(/\d+$/,function(page){return+page+1;});a.innerHTML=title;}});}}function
eventStop(event){if(event.stopPropagation){event.stopPropagation();}else{event.cancelBubble=true;}}function
setupSubmitHighlight(parent){for(var
key
in{input:1,select:1,textarea:1}){var
inputs=qsa(key,parent);for(var
i=0;i<inputs.length;i++){setupSubmitHighlightInput(inputs[i])}}}function
setupSubmitHighlightInput(input){if(!/submit|image|file/.test(input.type)){addEvent(input,'focus',inputFocus);addEvent(input,'blur',inputBlur);}}function
inputFocus(){var
submit=findDefaultSubmit(this);if(submit){alterClass(submit,'default',true);}}function
inputBlur(){var
submit=findDefaultSubmit(this);if(submit){alterClass(submit,'default');}}function
findDefaultSubmit(el){if(el.jushTextarea){el=el.jushTextarea;}if(!el.form){return null;}var
inputs=qsa('input',el.form);for(var
i=0;i<inputs.length;i++){var
input=inputs[i];if(input.type=='submit'&&!input.style.zIndex){return input;}}}function
addEvent(el,action,handler){if(el.addEventListener){el.addEventListener(action,handler,false);}else{el.attachEvent('on'+action,handler);}}function
focus(el){setTimeout(function(){el.focus();},0);}function
cloneNode(el){var
el2=el.cloneNode(true);var
selector='input, select';var
origEls=qsa(selector,el);var
cloneEls=qsa(selector,el2);for(var
i=0;i<origEls.length;i++){var
origEl=origEls[i];for(var
key
in
origEl){if(/^on/.test(key)&&origEl[key]){cloneEls[i][key]=origEl[key];}}}setupSubmitHighlight(el2);return el2;}oninput=function(event){var
target=event.target;var
maxLength=target.getAttribute('data-maxlength');alterClass(target,'maxlength',target.value&&maxLength!=null&&target.value.length>maxLength);};function
bodyLoad(version,maria){if(window.jush){jush.create_links=' target="_blank" rel="noreferrer noopener"';if(version){for(var
key
in
jush.urls){var
obj=jush.urls;if(typeof
obj[key]!='string'){obj=obj[key];key=0;if(maria){for(var
i=1;i<obj.length;i++){obj[i]=obj[i].replace(/\.html/,'/').replace(/-type-syntax/,'-data-types').replace(/numeric-(data-types)/,'$1-$&').replace(/#statvar_.*/,'#$$1');}}}obj[key]=(maria?obj[key].replace(/dev\.mysql\.com\/doc\/mysql\/en\//,'mariadb.com/kb/en/library/'):obj[key]).replace(/\/doc\/mysql/,'/doc/refman/'+version).replace(/\/docs\/current/,'/docs/'+version);}}if(window.jushLinks){jush.custom_links=jushLinks;}jush.highlight_tag('code',0);var
tags=qsa('textarea');for(var
i=0;i<tags.length;i++){if(/(^|\s)jush-/.test(tags[i].className)){var
pre=jush.textarea(tags[i]);if(pre){setupSubmitHighlightInput(pre);}}}}}function
formField(form,name){for(var
i=0;i<form.length;i++){if(form[i].name==name){return form[i];}}}function
typePassword(el,disable){try{el.type=(disable?'text':'password');}catch(e){}}function
messagesPrint(el){var
els=qsa('.toggle',el);for(var
i=0;i<els.length;i++){els[i].onclick=partial(toggle,els[i].getAttribute('href').substr(1));}}function
loginDriver(driver){var
trs=parentTag(driver,'table').rows;var
disabled=/sqlite/.test(selectValue(driver));alterClass(trs[1],'hidden',disabled);trs[1].getElementsByTagName('input')[0].disabled=disabled;}var
dbCtrl;var
dbPrevious={};function
dbMouseDown(event){dbCtrl=isCtrl(event);if(dbPrevious[this.name]==undefined){dbPrevious[this.name]=this.value;}}function
dbChange(){if(dbCtrl){this.form.target='_blank';}this.form.submit();this.form.target='';if(dbCtrl&&dbPrevious[this.name]!=undefined){this.value=dbPrevious[this.name];dbPrevious[this.name]=undefined;}}function
selectFieldChange(){var
form=this.form;var
ok=(function(){var
inputs=qsa('input',form);for(var
i=0;i<inputs.length;i++){if(inputs[i].value&&/^fulltext/.test(inputs[i].name)){return true;}}var
ok=form.limit.value;var
selects=qsa('select',form);var
group=false;var
columns={};for(var
i=0;i<selects.length;i++){var
select=selects[i];var
col=selectValue(select);var
match=/^(where.+)col\]/.exec(select.name);if(match){var
op=selectValue(form[match[1]+'op]']);var
val=form[match[1]+'val]'].value;if(col
in
indexColumns&&(!/LIKE|REGEXP/.test(op)||(op=='LIKE'&&val.charAt(0)!='%'))){return true;}else
if(col||val){ok=false;}}if((match=/^(columns.+)fun\]/.exec(select.name))){if(/^(avg|count|count distinct|group_concat|max|min|sum)$/.test(col)){group=true;}var
val=selectValue(form[match[1]+'col]']);if(val){columns[col&&col!='count'?'':val]=1;}}if(col&&/^order/.test(select.name)){if(!(col
in
indexColumns)){ok=false;}break;}}if(group){for(var
col
in
columns){if(!(col
in
indexColumns)){ok=false;}}}return ok;})();setHtml('noindex',(ok?'':'!'));}var
added='.',rowCount;function
delimiterEqual(val,a,b){return(val==a+'_'+b||val==a+b||val==a+b.charAt(0).toUpperCase()+b.substr(1));}function
idfEscape(s){return s.replace(/`/,'``');}function
editFields(){var
els=qsa('[name$="[field]"]');for(var
i=0;i<els.length;i++){els[i].oninput=function(){editingNameChange.call(this);if(!this.defaultValue){editingAddRow.call(this);}}}els=qsa('[name$="[length]"]');for(var
i=0;i<els.length;i++){mixin(els[i],{onfocus:editingLengthFocus,oninput:editingLengthChange});}els=qsa('[name$="[type]"]');for(var
i=0;i<els.length;i++){mixin(els[i],{onfocus:function(){lastType=selectValue(this);},onchange:editingTypeChange,onmouseover:function(event){helpMouseover.call(this,event,getTarget(event).value,1)},onmouseout:helpMouseout});}}function
editingClick(event){var
el=getTarget(event);if(!isTag(el,'input')){el=parentTag(el,'label');el=el&&qs('input',el);}if(el){var
name=el.name;if(/^add\[/.test(name)){editingAddRow.call(el,1);}else
if(/^up\[/.test(name)){editingMoveRow.call(el,1);}else
if(/^down\[/.test(name)){editingMoveRow.call(el);}else
if(/^drop_col\[/.test(name)){editingRemoveRow.call(el,'fields\$1[field]');}else{if(name=='auto_increment_col'){var
field=el.form['fields['+el.value+'][field]'];if(!field.value){field.value='id';field.oninput();}}return;}return false;}}function
editingInput(event){var
el=getTarget(event);if(/\[default\]$/.test(el.name)){el.previousSibling.checked=true;}}function
editingNameChange(){var
name=this.name.substr(0,this.name.length-7);var
type=formField(this.form,name+'[type]');var
opts=type.options;var
candidate;var
val=this.value;for(var
i=opts.length;i--;){var
match=/(.+)`(.+)/.exec(opts[i].value);if(!match){if(candidate&&i==opts.length-2&&val==opts[candidate].value.replace(/.+`/,'')&&name=='fields[1]'){return;}break;}var
table=match[1];var
column=match[2];var
tables=[table,table.replace(/s$/,''),table.replace(/es$/,'')];for(var
j=0;j<tables.length;j++){table=tables[j];if(val==column||val==table||delimiterEqual(val,table,column)||delimiterEqual(val,column,table)){if(candidate){return;}candidate=i;break;}}}if(candidate){type.selectedIndex=candidate;type.onchange();}}function
editingAddRow(focus){var
match=/(\d+)(\.\d+)?/.exec(this.name);var
x=match[0]+(match[2]?added.substr(match[2].length):added)+'1';var
row=parentTag(this,'tr');var
row2=cloneNode(row);var
tags=qsa('select',row);var
tags2=qsa('select',row2);for(var
i=0;i<tags.length;i++){tags2[i].name=tags[i].name.replace(/[0-9.]+/,x);tags2[i].selectedIndex=tags[i].selectedIndex;}tags=qsa('input',row);tags2=qsa('input',row2);var
input=tags2[0];for(var
i=0;i<tags.length;i++){if(tags[i].name=='auto_increment_col'){tags2[i].value=x;tags2[i].checked=false;}tags2[i].name=tags[i].name.replace(/([0-9.]+)/,x);if(/\[(orig|field|comment|default)/.test(tags[i].name)){tags2[i].value='';}if(/\[(has_default)/.test(tags[i].name)){tags2[i].checked=false;}}tags[0].oninput=editingNameChange;row.parentNode.insertBefore(row2,row.nextSibling);if(focus){input.oninput=editingNameChange;input.focus();}added+='0';rowCount++;return false;}function
editingRemoveRow(name){var
field=formField(this.form,this.name.replace(/[^\[]+(.+)/,name));field.parentNode.removeChild(field);parentTag(this,'tr').style.display='none';return false;}function
editingMoveRow(up){var
row=parentTag(this,'tr');if(!('nextElementSibling'in
row)){return true;}row.parentNode.insertBefore(row,up?row.previousElementSibling:row.nextElementSibling?row.nextElementSibling.nextElementSibling:row.parentNode.firstChild);return false;}var
lastType='';function
editingTypeChange(){var
type=this;var
name=type.name.substr(0,type.name.length-6);var
text=selectValue(type);for(var
i=0;i<type.form.elements.length;i++){var
el=type.form.elements[i];if(el.name==name+'[length]'){if(!((/(char|binary)$/.test(lastType)&&/(char|binary)$/.test(text))||(/(enum|set)$/.test(lastType)&&/(enum|set)$/.test(text)))){el.value='';}el.oninput.apply(el);}if(lastType=='timestamp'&&el.name==name+'[has_default]'&&/timestamp/i.test(formField(type.form,name+'[default]').value)){el.checked=false;}if(el.name==name+'[collation]'){alterClass(el,'hidden',!/(char|text|enum|set)$/.test(text));}if(el.name==name+'[unsigned]'){alterClass(el,'hidden',!/(^|[^o])int(?!er)|numeric|real|float|double|decimal|money/.test(text));}if(el.name==name+'[on_update]'){alterClass(el,'hidden',!/timestamp|datetime/.test(text));}if(el.name==name+'[on_delete]'){alterClass(el,'hidden',!/`/.test(text));}}helpClose();}function
editingLengthChange(){alterClass(this,'required',!this.value.length&&/var(char|binary)$/.test(selectValue(this.parentNode.previousSibling.firstChild)));}function
editingLengthFocus(){var
td=this.parentNode;if(/(enum|set)$/.test(selectValue(td.previousSibling.firstChild))){var
edit=qs('#enum-edit');edit.value=enumValues(this.value);td.appendChild(edit);this.style.display='none';edit.style.display='inline';edit.focus();}}function
enumValues(s){var
re=/(^|,)\s*'(([^\\']|\\.|'')*)'\s*/g;var
result=[];var
offset=0;var
match;while(match=re.exec(s)){if(offset!=match.index){break;}result.push(match[2].replace(/'(')|\\(.)/g,'$1$2'));offset+=match[0].length;}return(offset==s.length?result.join('\n'):s);}function
editingLengthBlur(){var
field=this.parentNode.firstChild;var
val=this.value;field.value=(/^'[^\n]+'$/.test(val)?val:val&&"'"+val.replace(/\n+$/,'').replace(/'/g,"''").replace(/\\/g,'\\\\').replace(/\n/g,"','")+"'");field.style.display='inline';this.style.display='none';}function
columnShow(checked,column){var
trs=qsa('tr',qs('#edit-fields'));for(var
i=0;i<trs.length;i++){alterClass(qsa('td',trs[i])[column],'hidden',!checked);}}function
partitionByChange(){var
partitionTable=/RANGE|LIST/.test(selectValue(this));alterClass(this.form['partitions'],'hidden',partitionTable||!this.selectedIndex);alterClass(qs('#partition-table'),'hidden',!partitionTable);helpClose();}function
partitionNameChange(){var
row=cloneNode(parentTag(this,'tr'));row.firstChild.firstChild.value='';parentTag(this,'table').appendChild(row);this.oninput=function(){};}function
editingCommentsClick(el,focus){var
comment=el.form['Comment'];columnShow(el.checked,6);alterClass(comment,'hidden',!el.checked);if(focus&&el.checked){comment.focus();}}function
dumpClick(event){var
el=parentTag(getTarget(event),'label');if(el){el=qs('input',el);var
match=/(.+)\[\]$/.exec(el.name);if(match){checkboxClick.call(el,event);formUncheck('check-'+match[1]);}}}function
foreignAddRow(){var
row=cloneNode(parentTag(this,'tr'));this.onchange=function(){};var
selects=qsa('select',row);for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/\]/,'1$&');selects[i].selectedIndex=0;}parentTag(this,'table').appendChild(row);}function
indexesAddRow(){var
row=cloneNode(parentTag(this,'tr'));this.onchange=function(){};var
selects=qsa('select',row);for(var
i=0;i<selects.length;i++){selects[i].name=selects[i].name.replace(/indexes\[\d+/,'$&1');selects[i].selectedIndex=0;}var
inputs=qsa('input',row);for(var
i=0;i<inputs.length;i++){inputs[i].name=inputs[i].name.replace(/indexes\[\d+/,'$&1');inputs[i].value='';}parentTag(this,'table').appendChild(row);}function
indexesChangeColumn(prefix){var
names=[];for(var
tag
in{'select':1,'input':1}){var
columns=qsa(tag,parentTag(this,'td'));for(var
i=0;i<columns.length;i++){if(/\[columns\]/.test(columns[i].name)){var
value=selectValue(columns[i]);if(value){names.push(value);}}}}this.form[this.name.replace(/\].*/,'][name]')].value=prefix+names.join('_');}function
indexesAddColumn(prefix){var
field=this;var
select=field.form[field.name.replace(/\].*/,'][type]')];if(!select.selectedIndex){while(selectValue(select)!="INDEX"&&select.selectedIndex<select.options.length){select.selectedIndex++;}select.onchange();}var
column=cloneNode(field.parentNode);var
selects=qsa('select',column);for(var
i=0;i<selects.length;i++){select=selects[i];select.name=select.name.replace(/\]\[\d+/,'$&1');select.selectedIndex=0;}field.onchange=partial(indexesChangeColumn,prefix);var
inputs=qsa('input',column);for(var
i=0;i<inputs.length;i++){var
input=inputs[i];input.name=input.name.replace(/\]\[\d+/,'$&1');if(input.type!='checkbox'){input.value='';}}parentTag(field,'td').appendChild(column);field.onchange();}function
sqlSubmit(form,root){if(encodeURIComponent(form['query'].value).length<2e3){form.action=root+'&sql='+encodeURIComponent(form['query'].value)+(form['limit'].value?'&limit='+
+form['limit'].value:'')+(form['error_stops'].checked?'&error_stops=1':'')+(form['only_errors'].checked?'&only_errors=1':'');}}function
triggerChange(tableRe,table,form){var
formEvent=selectValue(form['Event']);if(tableRe.test(form['Trigger'].value)){form['Trigger'].value=table+'_'+(selectValue(form['Timing']).charAt(0)+formEvent.charAt(0)).toLowerCase();}alterClass(form['Of'],'hidden',!/ OF/.test(formEvent));}var
that,x,y;function
schemaMousedown(event){if((event.which?event.which:event.button)==1){that=this;x=event.clientX-this.offsetLeft;y=event.clientY-this.offsetTop;}}function
schemaMousemove(event){if(that!==undefined){var
left=(event.clientX-x)/em;var
top=(event.clientY-y)/em;var
divs=qsa('div',that);var
lineSet={};for(var
i=0;i<divs.length;i++){if(divs[i].className=='references'){var
div2=qs('[id="'+(/^refs/.test(divs[i].id)?'refd':'refs')+divs[i].id.substr(4)+'"]');var
ref=(tablePos[divs[i].title]?tablePos[divs[i].title]:[div2.parentNode.offsetTop/em,0]);var
left1=-1;var
id=divs[i].id.replace(/^ref.(.+)-.+/,'$1');if(divs[i].parentNode!=div2.parentNode){left1=Math.min(0,ref[1]-left)-1;divs[i].style.left=left1+'em';divs[i].querySelector('div').style.width=-left1+'em';var
left2=Math.min(0,left-ref[1])-1;div2.style.left=left2+'em';div2.querySelector('div').style.width=-left2+'em';}if(!lineSet[id]){var
line=qs('[id="'+divs[i].id.replace(/^....(.+)-.+$/,'refl$1')+'"]');var
top1=top+divs[i].offsetTop/em;var
top2=top+div2.offsetTop/em;if(divs[i].parentNode!=div2.parentNode){top2+=ref[0]-top;line.querySelector('div').style.height=Math.abs(top1-top2)+'em';}line.style.left=(left+left1)+'em';line.style.top=Math.min(top1,top2)+'em';lineSet[id]=true;}}}that.style.left=left+'em';that.style.top=top+'em';}}function
schemaMouseup(event,db){if(that!==undefined){tablePos[that.firstChild.firstChild.firstChild.data]=[(event.clientY-y)/em,(event.clientX-x)/em];that=undefined;var
s='';for(var
key
in
tablePos){s+='_'+key+':'+Math.round(tablePos[key][0]*10000)/10000+'x'+Math.round(tablePos[key][1]*10000)/10000;}s=encodeURIComponent(s.substr(1));var
link=qs('#schema-link');link.href=link.href.replace(/[^=]+$/,'')+s;cookie('adminer_schema-'+db+'='+s,30);}}var
helpOpen,helpIgnore;function
helpMouseover(event,text,side){var
target=getTarget(event);if(!text){helpClose();}else
if(window.jush&&(!helpIgnore||this!=target)){helpOpen=1;var
help=qs('#help');help.innerHTML=text;jush.highlight_tag([help]);alterClass(help,'hidden');var
rect=target.getBoundingClientRect();var
body=document.documentElement;help.style.top=(body.scrollTop+rect.top-(side?(help.offsetHeight-target.offsetHeight)/2:help.offsetHeight))+'px';help.style.left=(body.scrollLeft+rect.left-(side?help.offsetWidth:(help.offsetWidth-target.offsetWidth)/2))+'px';}}function
helpMouseout(event){helpOpen=0;helpIgnore=(this!=getTarget(event));setTimeout(function(){if(!helpOpen){helpClose();}},200);}function
helpClose(){alterClass(qs('#help'),'hidden',true);}