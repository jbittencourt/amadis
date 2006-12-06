<?php
class AMTAlbum extends AMMain {
  

  function __construct() {
    global $_CMAPP;

    parent::__construct();
    $this->requires("album.css",CMHTMLObj::MEDIA_CSS);

    $this->setImgId($_CMAPP['imlang_url']."/top_album_amadis.gif");
      
    
  }
}