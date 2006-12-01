<?

$_CMAPP['notrestricted'] = True;
include("../../config.inc.php");

//header('Content-type: text/xml');
$impl = new DOMImplementation;
$dtd = $impl->createDocumentType("projects","",$_CMAPP['media_url']."/dtds/projects_export.dtd");
$dom = $impl->createDocument("", "",$dtd);

$dom->encoding = "UTF-8";
$dom->standalone = "no";

$root = $dom->createElement('projects');
$dom->appendChild($root);

$users = $_SESSION[environment]->listSummaryUsersInteraction();
note($users); die();

if(!empty($_SESSION[user])) {
//   $user = new AMUser;
//   $user->username = "juliano";
//   $user->load();
  $userProjs = $_SESSION[user]->listProjects();
//$userProjs = $user->listProjects();
}

Foreach($projects as $proj) {
  $xproj = $dom->createElement('project');
  if(!empty($userProjs)) {
    $is = false;
    foreach($userProjs as $p2) {
      if($p2->codeProject==$proj->codeProject) {
	$is = true;
	break;
      }
    }
    if($is) $xproj->setAttribute("userproject","true");
  }
  $xproj->appendChild($dom->createElement('code',utf8_encode($proj->codeProject)));
  $xproj->appendChild($dom->createElement('title',utf8_encode($proj->title)));
  $xproj->appendChild($dom->createElement('hits',utf8_encode($proj->hits)));
  $xproj->appendChild($dom->createElement('members',utf8_encode($proj->numMembers)));
  $xproj->appendChild($dom->createElement('recentness',utf8_encode($proj->time)));
  $xproj->appendChild($dom->createElement('forumMessages',utf8_encode($proj->numForumMessages)));
  $xproj->appendChild($dom->createElement('mostRecentForumMessage',utf8_encode($proj->lastTimeForumMessage)));
  $root->appendChild($xproj);
}

echo $dom->saveXML();

?>