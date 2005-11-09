<?
define('CHAT_ROOM', "chat_room");
define('FINDER_ROOM', "finder_room");

interface AMCChat {

  public function setSleepTime();
  public function addChat($type, $code);
  public function addUserChat($codeUser, $codeUser, $typeRoom);
  public function getNewMessages($sender, $recipient);
  public function sendMessage($recipient, $text);
  static public function checkTimeOut($idSession);
  static public function updateTimeOut($idSession, $time);
  static public function drawMessages($list);

}
?>