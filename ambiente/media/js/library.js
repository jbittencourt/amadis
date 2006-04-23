
var AMSharedCallBack = {

  share: function(result) {
    var image = AM_getElement(result.oldId);
    image.src = result.url;
    image.id  = result.id;
  }
}



function Library_abrir(URL) {
  var width = 30;
  var height = 30;
  var left = 99;
  var top = 99;
  window.open(URL,'download', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=no, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
}


function Library_toogleHighlightLine(code,color) {
  var css_on  = "blt_col_select";
  var css_off = "blt_col_"+color;

  obj = AM_getElement("library_item_"+code);
  if(obj.className == css_on) {
    obj.className = css_off;
  }
  else {
    obj.className = css_on;
  }
}

function Library_delFile(id, link){
  if(confirm(lang_wish_delete)){
    location.href = link;
  }  
}

function Library_checkform(form){
  if(form.elements['upload'].value == "" || form.elements['upload'].value == null)
    return false;
  else 
    return true;
}