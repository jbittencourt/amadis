<?php

class AMCity extends CMObj
{

    public function configure()
    {
        $this->setTable("Cities");

        $this->addField("codeCity",CMObj::TYPE_INTEGER,11,1,0,1);
        $this->addField("name",CMObj::TYPE_VARCHAR,100,1,0,0);
        $this->addField("codeState",CMObj::TYPE_INTEGER,11,1,0,0);
        $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);

        $this->addPrimaryKey("codeCity");
    }

}