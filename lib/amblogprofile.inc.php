<?php

class AMBlogProfile extends CMObj 
{

   public function configure() {
     $this->setTable("BlogProfiles");

     $this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("titleBlog",CMObj::TYPE_VARCHAR,65535,1,0,0);
     $this->addField("text",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("image",CMObj::TYPE_INTEGER,20,1,0,0);

     $this->addPrimaryKey("codeUser");
  }
}

