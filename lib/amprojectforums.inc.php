<?php
/**
 * Short descrition
 *
 * Long description (can contatin many lines)
 *
 * @author You Name <your@email.org>
 * @todo You have something do finish in the future
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 **/

class AMProjectForums extends CMObj
{
    public function configure()
    {
        $this->setTable("ProjectForums");

        $this->addField("codeProject",CMObj::TYPE_INTEGER,"0",1,0,0);
        $this->addField("codeForum",CMObj::TYPE_INTEGER,"0",1,0,0);

        $this->addPrimaryKey("codeProject");
        $this->addPrimaryKey("codeForum");

    }
}

//put your functions here

?>
