<?php

class AMBlogComment extends CMObj
{

    public function configure() {
        $this->setTable("BlogComments");

        $this->addField("codComment",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("body",CMObj::TYPE_TEXT,65535,1,0,0);
        $this->addField("codePost",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("codeUser",CMObj::TYPE_VARCHAR,50,1,0,0);

        $this->addPrimaryKey("codComment");
    }

}


