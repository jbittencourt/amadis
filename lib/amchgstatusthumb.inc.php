<?php

class AMChgStatusThumb extends AMThumb {

  public function __construct() {
    parent::__construct();
    $this->maxX = 80;
    $this->maxY = 60;
  }

}