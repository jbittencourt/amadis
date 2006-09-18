<?php

class AMProjectBlogs extends CMObj {

    public function configure() {
        $this->setTable("ProjectBlogs");

        $this->addField("codeBlog",CMObj::TYPE_INTEGER,11,1,0,1);
        $this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);
        $this->addField("address",CMObj::TYPE_VARCHAR,256,1,0,0);
        $this->addField("title",CMObj::TYPE_VARCHAR,200,1,0,0);
        $this->addField("filter",CMObj::TYPE_VARCHAR,256,1,0,0);
        $this->addField("postCount",CMObj::TYPE_INTEGER,11,1,0,0);

        $this->addPrimaryKey("codeBlog");
    }
}
