<?php

class AMUploadThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 70;
    $this->maxY = 70;
  }

}