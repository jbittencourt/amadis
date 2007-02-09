<?php

class AMIconThumb extends AMThumb {
	
  	public function __construct($type='') {
    	parent::__construct();
    	$this->maxX = 60;
    	$this->maxY = 60;
    	$this->type = $type;
  	}

}