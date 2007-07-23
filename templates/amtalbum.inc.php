<?php
class AMTAlbum extends AMMain {
  function __construct() {
    global $_CMAPP, $_language;

    parent::__construct('webfolio');
    $this->requires("album.css",CMHTMLObj::MEDIA_CSS);

    $this->setImgId($_CMAPP['images_url']."/ico_album.gif");
    $this->setSectionTitle($_language['album']);
    
  }
}