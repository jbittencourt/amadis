<?

class AMProjectList extends AMListBox {

  public function __construct(CMContainer $items,$title,$type=AMTCadBox::CADBOX_SEARCH) {

    parent::__construct($items,$title, self::PROJECT, $type);

  }
  

  public function __toString() {
    global $_language,$_CMAPP;

    parent::add("<br><br>");
    //listagem
    parent::add("<table id=\"project_list\">");
      
    if($this->itens->__hasItems()) {
      $i = 0;
      foreach($this->itens as $item) {
	$id = "project_list_1";
	if(($i%2)==1) $id = "project_list_2";
	$i++;
	parent::add("<tr id=\"$id\" class=\"project_list_line\">");
	  
	parent::add("<td>");
	$f = $item->image;
	if($f!=0) {
	  $thumb = new AMProjectThumb;
	  $thumb->codeArquivo = $item->image;
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
	  

	parent::add("<td width=40%>");
	parent::add("<a class=\"blue\" href=\"$_CMAPP[services_url]/projetos/projeto.php?frm_codProjeto=$item->codeProject\">$item->title</a>");
	parent::add("</td>");
	parent::add("<td><span class=\"texto\">$item->description</span></td>");
	parent::add("</tr>");
      }
    }
    else {
      parent::add("$_language[no_project_found]");
    }

    parent::add("</table>");

    return parent::__toString();
  }
}


?>