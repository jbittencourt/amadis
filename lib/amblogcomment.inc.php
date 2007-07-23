<?php

class AMBlogComment extends CMObj {
	
    const ENUM_ANSWERED_TRUE = "TRUE";
    const ENUM_ANSWERED_FALSE = "FALSE";
	
    public function configure() 
    {
        $this->setTable("BlogComments");

        $this->addField("codeComment",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("body",CMObj::TYPE_TEXT,65535,1,0,0);
        $this->addField("codePost",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("codeUser",CMObj::TYPE_VARCHAR,50,1,0,0);
		$this->addField("parentComment",CMObj::TYPE_INTEGER,20,1,0,0);
		$this->addField("answered",CMObj::TYPE_ENUM,"10",1,"FALSE",0);		

		$this->addPrimaryKey("codeComment");
		
		$this->setEnumValidValues(
			"answered",array(self::ENUM_ANSWERED_TRUE,
			self::ENUM_ANSWERED_FALSE
		));
        
    }

}