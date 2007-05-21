<?php

/**
 * Class that represents each wiki page edit
 *
 */
class AMWikiRevision extends CMObj
{

    public function configure() {
        $this->setTable("WikiRevision");

        $this->addField("codeRevision",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("user",CMObj::TYPE_INTEGER,11,1,0,0);
        $this->addField("page",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("text",CMObj::TYPE_INTEGER,20,1,0,0);
        $this->addField("time",CMObj::TYPE_INTEGER,20,1,0,0);
   
        $this->addPrimaryKey("codeRevision");
        $this->addPrimaryKey("page");
        $this->addRelation('user', 'AMUser', 'codeUser');
        $this->addRelation('page', 'AMWikiPage', 'codePage');
        $this->addRelation('text', 'AMWikiText', 'codeText');
    }

}


