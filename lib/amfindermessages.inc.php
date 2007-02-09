<?

class AMFinderMessages extends CMObj {

   const ENUM_STATUS_READ = "READ";
   const ENUM_STATUS_NOT_READ = "NOT_READ";

   public function configure() {
     $this->setTable("FinderMessages");

     $this->addField("code",CMObj::TYPE_INTEGER,11,1,0,1);
     $this->addField("codeRoom",CMObj::TYPE_VARCHAR,11,1,0,0);
     $this->addField("codeSender",CMObj::TYPE_INTEGER,11,0,0,0);
     $this->addField("codeRecipient",CMObj::TYPE_INTEGER,11,1,0,0);
     $this->addField("message",CMObj::TYPE_TEXT,65535,1,0,0);
     $this->addField("status",CMObj::TYPE_ENUM,"10",1,"NOT_READ",0);
     $this->addField("time",CMObj::TYPE_INTEGER,11,1,0,0);

     $this->addPrimaryKey("code");

     $this->setEnumValidValues("status",array(self::ENUM_STATUS_READ,
                                            self::ENUM_STATUS_NOT_READ));
  }

  public function markAsRead() {
    $this->state = self::STATE_PERSISTENT;
    $this->status=self::ENUM_STATUS_READ;
    try {
      $this->save();
    }catch(CMDBQueryError $e) {

    }
  }

  /**
   * Envia um mensagem para um usuario conectado
   *
   * @param string $mensagem Mensagem a ser enviada
   * @param integer $para Codigo do usuario para quem se deseja enviar um mensagem
   */
  static public function sendMessage($recipient,$text) {
    $message = new AMFinderMessages;
    $message->codeSender = $_SESSION['user']->codeUser;
    $message->codeRecipient = $recipient;
    $message->message = $text;
    $message->status = AMFinderMessages::ENUM_STATUS_NOT_READ;
    $message->time = time();
    try {
      $message->save();
    }catch(CMException $e ) {
      return "not send message";
    }
    return "send message";
  }

}

?>
