<?
 /**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBProjectNewsList extends AMListBox {

  public function __construct(CMContainer $items,$title,$type=AMTCadBox::CADBOX_SEARCH) {

    parent::__construct($items,$title, self::PROJECT, $type);

  }

  public function __toString() {
    global $_language,$_CMAPP;

    parent::add("<br><br>");

    parent::add("<table id=\"".$this->class_prefix."_list\">");
    //    echo $this->itens;
    if($this->itens->__hasItems()) {
      $i = 0;
      foreach($this->itens as $item) {

	$id = $this->class_prefix."_list_1";
	if(($i%2)==1) $id = $this->class_prefix."_list_2";
	$i++;
	parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line\">");	  
	parent::add("<td width = '80'><a name='project_news_$item->codeNews'>");
	$f = $item->autor[0]->foto;
	if($f!=0) {
	  $thumb = new AMUserThumb;
	  $thumb->codeArquivo = $item->autor[0]->foto;
	  try {
	    $thumb->load();
	    parent::add($thumb->getView());
	  }
	  catch(CMDBException $e) {
	    echo $e; die();
	  }
	}
	else {
	  parent::add("&nbsp;");			
	}

	parent::add("<td>");
	parent::add(new AMTUserInfo($item->autor[0]));
	parent::add("</td>");
	parent::add("</tr>");
	parent::add("<tr id='$id'>");
	parent::add("<td colspan='2' class='".$this->class_prefix."_list_line_int'>");
	parent::add("<span class='project_list_subtitle'>$_language[project_news]: $item->title</span>");
	parent::add("<br>".nl2br($item->text)."</td>");
	parent::add("</tr>");
	parent::add("<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='8' border='0'></td></tr>");
      }
    }
    else {
      parent::add("<span class=\"texto\">".$_language['no_comments']."</span>");
    }
      
    parent::add("</table>");


    return parent::__toString();
  }
}
?>