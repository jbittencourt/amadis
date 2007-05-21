<?php

/**
 * Class that contatins the wiki text of the wiki revisions
 *
 */
class AMWikiText extends CMObj
{

    public function configure() {
        $this->setTable("WikiText");

        $this->addField("codeText",CMObj::TYPE_INTEGER,20,1,0,1);
        $this->addField("text",CMObj::TYPE_BLOB ,0,1,0,0);
   
        $this->addPrimaryKey("codeText");
    }

}

