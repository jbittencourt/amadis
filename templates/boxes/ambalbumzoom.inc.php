<?
class AMBAlbumZoom extends AMListBox {

  public function __construct(CMContainer $items,$title,AMAlbum $album,$type=AMTCadBox::CADBOX_ZOOM) {
    parent::__construct($items,$title, self::ALBUM, $type);
    $this->album = $album;
  }

  public function __toString() {
    global $_language,$_CMAPP;   
    $state = $_REQUEST['state'];
    parent::add("<table id='".$this->class_prefix."' bgcolor='#f6f8ff'>");   
    if($this->itens->__hasItems()) {
      parent::add("<tr>");
      parent::add("<td valign='top' align='center'><br><table border='1' class='".$this->class_prefix."_table'><tr>");
      parent::add("<td class='".$this->class_prefix."_bg1'>");
      parent::add("<img style=`border:1px;width:700px;` src='$_CMAPP[media_url]/imagewrapper.php?method=db&frm_codeArquivo=".$this->album->codePhoto."'>");
      parent::add("</td></tr><tr><td align='right'>");
      parent::add("<br>".nl2br($this->album->comments)."&nbsp;&nbsp;&nbsp;&nbsp;<br><a class='".$this->class_prefix."_link' cursor:hand href='");
      if($state == "personal"){ 
	parent::add($_CMAPP['services_url']."/album/album.php"); 
      }else{
	parent::add($_CMAPP['services_url']."/album/viewalbum.php?frm_codeUser=".$this->album->codeUser); 
      }
      parent::add("'><< ".$_language['back']."</a>"); 
      parent::add("</td></tr></table></td>");
    }
    else 
      parent::add("<tr><td class='".$this->class_prefix."_bg1'><br>".$_language['noPhotos']."</td><td></td></tr>");
    
    parent::add("</table>");
    return parent::__toString();
  }
  
}
?>