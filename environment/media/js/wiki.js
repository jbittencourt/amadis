function Wiki_preview()
{
   crossmark=new Crossmark();
   var markup;

   markup = AM_getElement('txtarea').value;

   var res=AM_getElement('preview_result');

   try {
      res.innerHTML = crossmark.parse(markup);
   } catch(e) {
      res.innerHTML = "Couldn't generate the page because the<br/>"
      +"crossmark source triggered the following error:<br/>"
      +"Name: <b>"+e.name+"</b><br/>"
      +"Message: <b>"+e.message+"</b><br/>";
   }
   res.style.display='block';
	
}

function toggleEdit()
{
   var b=AM_getElement('edit');
   if (b.value=='Edit') {
      b.value='Salvar';

	  AM_getElement('preview').style.display = 'inline';

      var txtarea = AM_getElement('txtarea');
      txtarea.style.display='block';
      AM_getElement('result').style.display='none';
   } else {
   	  Wiki_savePage(CURRENT_NAMESPACE, CURRENT_PAGE, AM_getElement('txtarea').value);
   }
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
   var markup;
   
   var txtarea=AM_getElement('txtarea');
   if (src) {
      markup=AM_getElement(src).value;
      txtarea.value=markup
   } else
      markup=txtarea.value
      
   txtarea.style.display='none'
   AM_getElement('preview_result').style.display = 'none';
   AM_getElement('preview').style.display = 'none';

   AM_getElement('edit').value='Edit'
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

