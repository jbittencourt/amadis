<?php
class AMProjectBlogs extends CMObj {

	const ENUM_STATUS_ENABLE = "ENABLE";
	const ENUM_STATUS_DISABLE = "DISABLE";

	//privileges to access this community
	const ENUM_TYPE_INTERNAL = "INTERNAL";
	const ENUM_TYPE_EXTERNAL = "EXTERNAL";

	public function configure() {
		$this->setTable("ProjectBlogs");

		$this->addField("codeSource",CMObj::TYPE_INTEGER,11,1,0,1);
		$this->addField("codeProject",CMObj::TYPE_INTEGER,11,1,0,0);
		$this->addField("codeUser",CMObj::TYPE_INTEGER,11,1,0,0);
		$this->addField("address",CMObj::TYPE_VARCHAR,256,1,0,0);
		$this->addField("title",CMObj::TYPE_VARCHAR,200,1,0,0);
        $this->addField("status",CMObj::TYPE_ENUM,"10",1,"ENABLE",0);
        $this->addField("type",CMObj::TYPE_ENUM,"10",1,"INTERNAL",0);        

		$this->addPrimaryKey("codeSource");
		
		$this->setEnumValidValues("status",array(self::ENUM_STATUS_ENABLE ,
        self::ENUM_STATUS_DISABLE ));
		
        $this->setEnumValidValues("type",array(self::ENUM_TYPE_INTERNAL ,
        self::ENUM_TYPE_EXTERNAL ));
				
	}
}

?>