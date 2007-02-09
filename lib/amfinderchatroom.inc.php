<?

class AMFinderChatRoom extends CMObj {

   public function configure() {
     $this->setTable("FinderChatRoom");

     $this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("dateStart",CMObj::TYPE_VARCHAR,11,1,0,0);
     $this->addField("dateEnd",CMObj::TYPE_INTEGER,11,0,0,0);
     $this->addField("codeStarter",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("codeRequest",CMObj::TYPE_INTEGER,11,1,0,0);
     
     $this->addPrimaryKey("code");
  }


  function __init($sender,$recipient) {
    $this->codeStarter = $sender;
    $this->codeRequest = $recipient;
    $this->dateStart = time();
    try { 
      $this->save();
    }catch (CMDBQueryError $e) {
      
    }

    return $this->code;
  }


  function inThisRoom($codUser) {

    return (($this->codeStarter==$codeUser) || ($this->codeRequest==$codeUser));

  }

}

?>
