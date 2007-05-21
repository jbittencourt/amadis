<?php
include('../../config.inc.php');
$_language = $_CMAPP['i18n']->getTranslationArray("wiki");
$page = new AMTProjeto();

//escrever o wiki
$page->requires('jsCrossmark.css', CMHTMLObj::MEDIA_CSS);
$page->requires('jsCrossmark.js', CMHTMLObj::MEDIA_JS);

$page->add('<input type="button" value="Edit" id="edit" onclick="javascript:toggleEdit();"/>
<input type="button" value="Crossmark Draft-4" onclick="javascript:wikiLoad(\'draft4\');"/>
<input type="button" value="jsCrossmark" onclick="javascript:wikiLoad(\'jsCrossmark\');"/>
');

$page->add('<textarea style="display: none;" id="draft4">

Crossmark: a flexible, lightweight textual markup language
==========================================================

<note I corrected some errors in the spec (such as the RFC link) --Daniel>

:Author
    Ivan Krstić
    ivan@laptop.org
    One Laptop Per Child / Harvard University

:Metadata
    Revision: Draft-4
    Timestamp: Wed Oct 26 15:15:22 EDT 2006
    Note:
        This is NOT the final Crossmark specification. It is the fourth draft,
        and is still being reviewed actively as we work towards an authoritative
        specification for the format. To offer feedback, please mail the author.

:Summary
    This document defines the Crossmark markup language. Crossmark has a dual
    purpose; in the strict sense, it\'s a markup for (collaborative) authoring
    environments such as wikis and blogs. At the same time, it serves as an
    actual document format, suitable for use in e-book readers, as well as for
    conversion to other output formats. Crossmark is highly extensible through
    \'macros\', which allow it to be easily customized for domain-specific
    documents.

    Crossmark is designed to be read and written by humans, and only
    incidentally by computers, although it is parsable unambiguously. A formal
    grammar is being developed and will be made available along with a
    reference parser implementation.

    This specification will only define an unambiguous translation of Crossmark
    input to a parse tree. The translation of a Crossmark parse tree to an
    actual output format, such as HTML or Postscript, is not officially
    specified, and is left entirely up to the implementers of individual output
    filters. This is by design. Crossmark requires parsers to provide
    functionality called \'layout hinting\', which allows authors to provide cues
    about how their content should be laid out, but the interpretation of those
    cues is performed by output filters as they see fit.

    This specification does not, but will in a later revision, define the
    structure and implementation of Crossmark \'bundles\' -- archives that include
    Crossmark documents and accompanying content such as multimedia and
    Crossmark macros.

</textarea>');

$page->add('<textarea style="display: none;" id="jsCrossmark">

jsCrossmark - JavaScript Crossmark Markup Interpreter
-----------------------------------------------------

:code javascript
   cmMacros.localization={ //embedding localization, how cool is that? :)
      \'imagem\':   \'image\',
      \'codigo\':   \'code\',
      \'autor\':    \'author\',
      \'sumario\':  \'summary\',
      \'nota\':     \'note\',
      \'tabela\':   \'table\',
      \'cru\':      \'raw\',
   }
   false

:autor
   [[ Daniel Monteiro Basso | mailto:daniel@basso.inf.br ]]
   [[ Laboratório de Estudos Cognitivos | http://www.lec.ufrgs.br ]]
   [[ Universidade Federal do Rio Grande do Sul | http://www.ufrgs.br ]]

:nota
   <image gato.jpg, right>
   *features*:
      - parses most of the specs accurately
      - two types of styling: accurate or fast (the default)
      - extensible through JavaScript macro registering
      - interpretations errors are elegantly treated
      
   *important notes* (differences from spec):
      - /table/: has a simpler syntax than the spec\'ed
      - /date/: just copies the source, doesn\'t process it yet
      - /image/: only takes an alignment parameter
      - /localization/: only for macro names (actually there is no named parameter processing yet), and it doesn\'t support accentuation (javascript\'s fault)
      - /blockquotes/: I\'m not sure what to process inside of it, should lists be processed?

   *what I intend to do*:
      - the `quote` macro
      - a real `raw` macro (current is hardcoded for inline call)
      - `note` colapsing/espansion
      - citations -- very useful
      - overall debugging
      - RSS and TeX output

=== JavaScript macros ===

==== a complex one ====

:codigo javascript
      var cm=new Crossmark()
      function fib(l,a,b){return l?fib(l-1,b,a+b):b}
      var fibretv=\'\'
      for (var i=4; i<8; i++)
         fibretv+=\'* The \'+i+\'th element in the Fibonacci set is \'+fib(i-2,1,1)+\'\n\'
      cm.parse(
      \'\njsCrossmark\n----\n\n\'+
      \'This was intepreted recursively in a block of code.\n\'+
      \'\n=== Speaking of recursion... ===\n\n\'+fibretv
      )
</textarea>');
$page->add('<textarea id="txtarea" cols=85 rows=30>
</textarea>');
$page->add('<div id="result"></div>');

$page->add(CMHTMLObj::getScript('function toggleEdit()
{
   var b=document.getElementById(\'edit\')
   if (b.value==\'Edit\')
   {
      b.value=\'Visualize\'
      var txtarea=document.getElementById(\'txtarea\')
      txtarea.style.display=\'block\'
      document.getElementById(\'result\').style.display=\'none\'
   } else {
      wikiLoad()
   }
}

function wikiLoad(src)
{
   crossmark=new Crossmark()
   var markup
   
   var txtarea=document.getElementById(\'txtarea\')
   if (src)   
   {
      markup=document.getElementById(src).value
      txtarea.value=markup
   } else
      markup=txtarea.value
      
   txtarea.style.display=\'none\'

   document.getElementById(\'edit\').value=\'Edit\'
   var res=document.getElementById(\'result\')   
   try {
      res.innerHTML=crossmark.parse(markup)
   } catch(e) {
      res.innerHTML="Couldn\'t generate the page because the<br/>"
      +"crossmark source triggered the following error:<br/>"
      +"Name: <b>"+e.name+"</b><br/>"
      +"Message: <b>"+e.message+"</b><br/>"
   }
   res.style.display=\'block\'
}

wikiLoad(\'jsCrossmark\');'));


echo $page;

?>