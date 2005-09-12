function doScroll(numLinhas) {
  window.scrollBy(0,1);
  if (browser=="opera") {
    window.scrollBy(0,40);
    for(i=1;i<=numLinhas;i++) {
      window.scrollBy(0,10); }   
  }    
  else if (isNaN(window.pageYOffset))
    { window.scrollTo(0,document.body.scrollHeight);}
  else 
    { if (document.layers) {
      window.scrollBy(0,47);
      for(i=1;i<=numLinhas;i++) {
	window.scrollBy(0,14); }   
    }
    else {
      window.scrollTo(0,document.body.offsetHeight);}   //By0,42
    }
}

function scrollTela(numLinhas) {
  var frm_scroll = parent.AM_getElement("autoScroll");

  if (browser=="opera") {
    if (frm_scroll.checked==true)    
      { doScroll(numLinhas); }
  }
  if (browser!="opera") {
    if (!isNaN(window.pageYOffset)) {
      if (window.pageYOffset<posicaoY) {
        frm_scroll.checked = false; }    
      posicaoY = window.pageYOffset; }                    
    else if (!isNaN(document.body.scrollTop)) {
      if (document.body.scrollTop<posicaoY) {
        frm_scroll.checked = false; }    
      
    }
  }
  if (browser!="opera") {
    if (frm_scroll.checked==true) {
      doScroll(numLinhas); }    
    else if (!isNaN(window.pageYOffset)) {
      if ((document.body.offsetHeight-window.pageYOffset)<(window.innerHeight+70))    //70
	{  doScroll(numLinhas);}     
    }  
    else if (!isNaN(document.body.scrollTop)) {
      if ((document.body.scrollTop+document.body.clientHeight)>=(document.body.scrollHeight-90)) //90
	{  doScroll(numLinhas);}
    }      
    if (isNaN(window.pageYOffset)) {
      posicaoY = document.body.scrollTop; }                        
    else { 
      posicaoY = window.pageYOffset; }    
  }
}

posicaoY=0;
detect = navigator.userAgent.toLowerCase();
if (detect.indexOf('opera')!=-1){
  browser="opera";
} else {
  browser = "outro";
}