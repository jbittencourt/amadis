<?php

class AMUserThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 60;
    $this->maxY = 60;
  }

  public function load() {
    $tmp = $this->codeFile;
    if(empty($tmp)) {
      $this->codeFile = AMUserPicture::DEFAULT_IMAGE;
    }
    return parent::load();
  }

}