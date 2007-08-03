<?php

class AMWarning extends CMObj 
{

	public function configure() {
    	$this->setTable("Warnings");

     	$this->addField("codeWarning",CMObj::TYPE_INTEGER,20,1,0,1);
     	$this->addField("title",CMObj::TYPE_VARCHAR,100,1,0,0);
     	$this->addField("description",CMObj::TYPE_TEXT,65535,1,0,0);
     	$this->addField("timeStart",CMObj::TYPE_INTEGER,20,1,0,0);
     	$this->addField("timeEnd",CMObj::TYPE_INTEGER,20,1,0,0);

     	$this->addPrimaryKey("codeWarning");
  	}


  	public function listaAvisos(){
    	$q = new CMQuery('AMWarning');    
    	return $q->execute();
  	}
}