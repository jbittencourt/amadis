<?php

class AMArea extends CMObj {

    public function configure() {
        $this->setTable("Areas");

        $this->addField("codeArea",CMObj::TYPE_INTEGER,4,1,0,1);
        $this->addField("name",CMObj::TYPE_VARCHAR,50,1,0,0);
        $this->addPrimaryKey("codeArea");
    }
    
    function listProjects() {
         
        $q = new CMQuery(AMProject);
        $q->setNaturalJoin(AMProjectArea,"temp");
        $q->setFilter("codeArea = '$this->codeArea'");
        return $q->execute();
    }

    static public function listAreas(){
        $q = new CMQuery('AMArea');
        return $q->execute();
    }
    
}

