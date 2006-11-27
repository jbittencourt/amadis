<?php

class AMCommunityThumb extends AMThumb {

  public function __construct($mini=false) {
    parent::__construct();
    if(!$mini) {
      $this->maxX = 80;
      $this->maxY = 60;
    }
    else {
      $this->maxX = 60;
      $this->maxY = 60;
    }
  }

}