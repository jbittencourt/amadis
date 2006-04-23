<?
class AMBAdminUsersList extends AMTCadBox {

  protected $itens;
  protected $class_prefix;
  const PEOPLE = 0;

  public function __construct(CMContainer $items,$title, $type=AMTCadBox::CADBOX_SEARCH) {
    global $_language;
    $box_theme=AMTCadBox::PEOPLE_THEME;
    $this->class_prefix = 'admin';

    parent::__construct($title, $type, $box_theme);
    $this->itens = $items;
  }


  public function getChangePwButton($codeUser) {
    global $_CMAPP,$_language;

    $link = $_CMAPP['services_url']."/admin/changepw.php?frm_codUser=$codeUser";
    return '<button class="button-as-link" type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_ver_pagina.gif"> '.$_language['changepw_button'].'</button>';
  }


  public function getChangeStatusButton($codeUser) {
      global $_CMAPP,$_language;
      
      $link = $_CMAPP['services_url']."/admin/changestatus.php?frm_codUser=$codeUser";
      return '<button class="button-as-link"type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_ver_pagina.gif"> '.$_language['changestatus_button'].'</button>';
  }

  public function getSendNotificationButton($codeUser){
    global $_CMAPP,$_language;
      
      $link = $_CMAPP['services_url']."/admin/sendnotif.php?frm_codUser=$codeUser";
      return '<button class="button-as-link"type="button" onClick="AM_openURL(\''.$link.'\')"><img src="'.$_CMAPP['images_url'].'/ico_ver_pagina.gif"> '.$_language['sendn_button'].'</button>';    
  }

  public function __toString() {
    global $_language,$_CMAPP;

    parent::add("<br><br>");

    parent::add("<table id=\"".$this->class_prefix."_list\">");
      
    if(!empty($this->itens->items)) {
      $i = 0;
      foreach($this->itens as $item) {
	$id = $this->class_prefix."_list_1";
	if(($i%2)==1) $id = $this->class_prefix."_list_2";
	$i++;
	parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line\">");	  
	parent::add("<td width = '80'>");
	$f = $item->foto;
	if($f!=0) {
	  $thumb = new AMUserThumb;
	  $thumb->codeArquivo = $item->foto;
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
	parent::add(new AMTUserInfo($item));
	parent::add("</td>");
	parent::add("<td width='90'>".$this->getChangePwButton($item->codeUser));
	parent::add("<td width='90'>".$this->getChangeStatusButton($item->codeUser));
	parent::add("<td width='90'>".$this->getSendNotificationButton($item->codeUser));
	parent::add("</tr>");	

	if(isset($_REQUEST['frm_codProjeto']) && !empty($_REQUEST['frm_codProjeto'])) {
	  parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line_int\">");
	  parent::add("<td align='left' valign='top' colspan = '5'><br><font class='project_list_subtitle'>$_language[join_reason]</font>");
	  parent::add("<br><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='12' border='0'>");
	  parent::add($reason[0]->textRequest."</td>");
	  parent::add("</tr>");
	  parent::add("<tr id=\"$id\" class=\"".$this->class_prefix."_list_line\">");
	  parent::add("<td align='left' valign='top' colspan = '5'><br><font class='project_list_subtitle'>$_language[approval]</font>");
	  parent::add("<br>".$response[0]->textResponse."</td>");
	  parent::add("</tr>");
	}

	parent::add("<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='8' border='0'></td></tr>");
      }
    }
    else {
      parent::add("<span class=\"texto\">$_language[no_user_found]</span>");
    }
      
    parent::add("</table>");


    return parent::__toString();
  }
}

?>