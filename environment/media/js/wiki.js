
function linkMangled(text, addr)
{
	addr = addr.replace(/^\s+|\s+$/g, '')
   	addr = addr.split(' ')
   	addr = this.wikiManagerAddress+addr.join('_')
   	return "<a href='"+addr+"'>"+text+"</a>";
}

function Wiki_preview()
{
	crossmark=new Crossmark();
	crossmark.semanticActions.wikiManagerAddress="index.php?frm_namespace=" + CURRENT_NAMESPACE + "&frm_title=";
	crossmark.semanticActions.linkMangled=linkMangled;

   	var markup;

   	markup = AM_getElement('txtarea').value;

   	var res=AM_getElement('preview_result');

   	res.innerHTML = "Esta e somente uma visualizacao, voce precisa clicar em salvar para guardas as modificacoes do texto";
   	try {
    	res.innerHTML += crossmark.parse(markup);
   	} catch(e) {
    	res.innerHTML = "Couldn't generate the page because the<br/>"
      	+"crossmark source triggered the following error:<br/>"
      	+"Name: <b>"+e.name+"</b><br/>"
      	+"Message: <b>"+e.message+"</b><br/>";
   	}
   	res.style.display='block';

}

function toggleEdit(cancel)
{
   	if(cancel == 'cancel') {
		AM_getElement('jsCrossMark_editArea').style.display = 'none';
		AM_getElement('preview_result').style.display = 'none';
		AM_getElement('result').style.display = 'block';
		AM_getElement('txtarea').value = AM_getElement(CURRENT_PAGE).innerHTML;
		return;
   	} else {
   		var txtarea = AM_getElement('txtarea');
   		AM_getElement('result').style.display='none';
		AM_getElement('jsCrossMark_editArea').style.display = 'block';
	}
}

function Wiki_saveText()
{
	Wiki_savePage(CURRENT_NAMESPACE, CURRENT_PAGE, AM_getElement('txtarea').value);
	AM_getElement('jsCrossMark_editArea').style.display = 'none';	
}

var AMWikiCallBack = {
	onSavePage : function(result) {
		if(result != 0) {
			wikiLoad();
		}else alert('Nao deu pra salvar!');
	}	
};

function Wiki_savePage(namespace, title, text)
{
	AMWiki.onSavePageError = AM_callBack.onError;
	AMWiki.savePage(namespace, title, text, AMWikiCallBack.onSavePage);	
}

function wikiLoad(src)
{
   	crossmark=new Crossmark();
  	crossmark.semanticActions.wikiManagerAddress="index.php?frm_namespace=" + CURRENT_NAMESPACE + "&frm_title=";
	crossmark.semanticActions.linkMangled=linkMangled;

   	var markup;
   
   	var txtarea=AM_getElement('txtarea');
   	if (src) {
    	markup=AM_getElement(src).value;
      	txtarea.value=markup
   	} else
      	markup=txtarea.value
      
   	AM_getElement('preview_result').style.display = 'none';

   	var res=AM_getElement('result')   
   	try {
      	res.innerHTML=crossmark.parse(markup)
   	} catch(e) {
      	res.innerHTML="Couldn't generate the page because the<br/>"
      	+"crossmark source triggered the following error:<br/>"
      	+"Name: <b>"+e.name+"</b><br/>"
      	+"Message: <b>"+e.message+"</b><br/>"
   	}
   	res.style.display='block'
}


// apply tagOpen/tagClose to selection in textarea,
// use sampleText instead of selection if there is none
// Copied and adapted from MediaWiki that copied and adapted from phpBB
function Wiki_insertTags(tagOpen, tagClose, sampleText) {
	var txtarea = AM_getElement('txtarea');

	// IE
	if (document.selection  && is_ie) {
		var theSelection = document.selection.createRange().text;
		if (!theSelection)
			theSelection=sampleText;
		txtarea.focus();
		if (theSelection.charAt(theSelection.length - 1) == " ") { // exclude ending space char, if any
			theSelection = theSelection.substring(0, theSelection.length - 1);
			document.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
		} else {
			document.selection.createRange().text = tagOpen + theSelection + tagClose;
		}

	// Mozilla
	} else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
		var replaced = false;
		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		if (endPos-startPos)
			replaced = true;
		var scrollTop = txtarea.scrollTop;
		var myText = (txtarea.value).substring(startPos, endPos);
		if (!myText)
			myText=sampleText;
		if (myText.charAt(myText.length - 1) == " ") { // exclude ending space char, if any
			subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
		} else {
			subst = tagOpen + myText + tagClose;
		}
		txtarea.value = txtarea.value.substring(0, startPos) + subst +
			txtarea.value.substring(endPos, txtarea.value.length);
		txtarea.focus();
		//set new selection
		if (replaced) {
			var cPos = startPos+(tagOpen.length+myText.length+tagClose.length);
			txtarea.selectionStart = cPos;
			txtarea.selectionEnd = cPos;
		} else {
			txtarea.selectionStart = startPos+tagOpen.length;
			txtarea.selectionEnd = startPos+tagOpen.length+myText.length;
		}
		txtarea.scrollTop = scrollTop;

	// All other browsers get no toolbar.
	// There was previously support for a crippled "help"
	// bar, but that caused more problems than it solved.
	}
	// reposition cursor if possible
	if (txtarea.createTextRange)
		txtarea.caretPos = document.selection.createRange().duplicate();
}

// this function generates the actual toolbar buttons with localized text
// we use it to avoid creating the toolbar where javascript is not enabled
function Wiki_addButton(imageFile, speedTip, tagOpen, tagClose, sampleText, callFunction) {
	// Don't generate buttons for browsers which don't fully
	// support it.
	if (!document.selection && is_ie) {
		return false;
	}
	imageFile = Wiki_escapeQuotesHTML(imageFile);
	speedTip = Wiki_escapeQuotesHTML(speedTip);
	tagOpen = Wiki_escapeQuotes(tagOpen);
	tagClose = Wiki_escapeQuotes(tagClose);
	sampleText = Wiki_escapeQuotes(sampleText);
	var mouseOver = "";

	if(callFunction == undefined) {
		document.write("<a href=\"javascript:Wiki_insertTags");
		document.write("('"+tagOpen+"','"+tagClose+"','"+sampleText+"');\">");
		document.write("<img width=\"23\" height=\"22\" src=\""+imageFile+"\" border=\"0\" alt=\""+speedTip+"\" title=\""+speedTip+"\""+mouseOver+">");
		document.write("</a><br>");
		return;
	} else {
		document.write("<a href=\"javascript:" + callFunction + "\">");
		document.write("<img width=\"23\" height=\"22\" src=\""+imageFile+"\" border=\"0\" alt=\""+speedTip+"\" title=\""+speedTip+"\""+mouseOver+">");
		document.write("</a><br>");
	}
}

function Wiki_escapeQuotes(text) {
	var re = new RegExp("'","g");
	text = text.replace(re,"\\'");
	re = new RegExp("\\n","g");
	text = text.replace(re,"\\n");
	return Wiki_escapeQuotesHTML(text);
}

function Wiki_escapeQuotesHTML(text) {
	var re = new RegExp('&',"g");
	text = text.replace(re,"&amp;");
	var re = new RegExp('"',"g");
	text = text.replace(re,"&quot;");
	var re = new RegExp('<',"g");
	text = text.replace(re,"&lt;");
	var re = new RegExp('>',"g");
	text = text.replace(re,"&gt;");
	return text;
}

function Wiki_loadToolBar()
{
	Wiki_addButton('/skins/common/images/button_bold.png','Texto em negrito','*','*','Texto em negrito');
	Wiki_addButton('/skins/common/images/button_italic.png','Texto em italico','\/','\/','Texto em italico');
	Wiki_addButton('/skins/common/images/button_underline.png','Texto sublinhado','_','_','Texto sublinhado');
	Wiki_addButton('/skins/common/images/button_link.png','Ligacao interna','[[',']]','Titulo da ligacao');
	Wiki_addButton('/skins/common/images/button_extlink.png','Ligacao externa (lembre-se dos prefixos http://, ftp://, ...)','[[',']]','ligacao externa | http://www.wikimedia.org');
	Wiki_addButton('/skins/common/images/button_headline.png','Seccao de nivel 2','\n== ',' ==\n','Texto de cabecalho');
	Wiki_addButton('/skins/common/images/button_image.png','Imagem anexa','<image ','>','Exemplo.jpg', 'Wiki_insertImage();');
	//Wiki_addButton('/skins/common/images/button_media.png','Ligacao a ficheiro interno de multimedia','[[Media:',']]','Exemplo.ogg');
	//Wiki_addButton('/skins/common/images/button_math.png','Formula matematica (LaTeX)','\<math\>','\</math\>','Inserir formula aqui');
	//Wiki_addButton('/skins/common/images/button_nowiki.png','Ignorar formato wiki','\<nowiki\>','\</nowiki\>','Inserir texto nao-formatado aqui');
	//Wiki_addButton('/skins/common/images/button_sig.png','Sua assinatura com hora e data','--~~~~','','');
	//Wiki_addButton('/skins/common/images/button_hr.png','Linha horizontal (utilize moderadamente)','\n----\n','','');
}

function Wiki_insertImage()
{
	var win = AM_getElement('Wiki_insertImageWindow');
	win.style.display = 'block';
	win.style.left = (MouseX-200) + 'px;';
	win.style.top = (MouseY-150) + 'px;';

}

var MouseX;
var MouseY;

function getMouseXY(e) {
  if (is_ie) { // grab the x-y pos.s if browser is IE
    tempX = event.clientX + document.body.scrollLeft;
    tempY = event.clientY + document.body.scrollTop;
  } else {  // grab the x-y pos.s if browser is NS
    tempX = e.pageX;
    tempY = e.pageY;
  }  
  // catch possible negative values in NS4
  if (tempX < 0){ tempX = 0; }
  if (tempY < 0){ tempY = 0; }  
  // show the position values in the form named Show
  // in the text fields named MouseX and MouseY
  MouseX = tempX; 
  MouseY = tempY;
  return true;
}

function Wiki_close(id)
{
	AM_getElement(id).style.display = 'none';
}