<?

include("../../config.inc.php");

if(empty($_REQUEST[frm_codeGroupMemberJoin])) {
  echo "Bailing: no group join code";
}

$j = new CMGroupMemberJoin;
$j->codeGroupMemberJoin = $_REQUEST[frm_codeGroupMemberJoin];

try {
  $j->load();
} catch (CMDBNoRecord $e) {
  echo "Bailing: invititation not found.";
}

$j->ackResponse = CMGroupMemberJoin::ENUM_ACKRESPONSE_ACK;
$j->save();

echo "Ack ".$j->codeGroupMemberJoin;

?>