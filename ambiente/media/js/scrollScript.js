var scrollLock = false;

function doScroll(numLinhas, obj) {
  window.scrollBy(0,1);
  if (browser=='opera') {
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
  var box = AM_getElement('chatBox');

  if (browser=='opera') {
    doScroll(numLinhas); 
  }
  
  if(browser != 'opera') {
    box.scrollTop += numLinhas;
  }

}
posicaoY=0;
detect = navigator.userAgent.toLowerCase();
if (detect.indexOf('opera')!=-1) { browser='opera'; }    
else { browser = 'outro'; }
    
