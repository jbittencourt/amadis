<?
$_CMAPP[notrestricted] = True;
include("../../config.inc.php");


$_language = $_CMAPP[i18n]->getTranslationArray("diary");
$_CMAPP[smartform] = array();
$_CMAPP[smartform][language] = $_language;



//load the data from the post into a variable and test for errors
$post = new AMDiarioPost;
$post->codePost = $_REQUEST[frm_codePost];

try {
  $post->load();
} catch(AMException $e) {
  die();
}

//the post exists, so we can go on;
$comments = $post->listComments();

$campos = array("body");
$form = new AMWSmartForm(AMDiarioComentario, "cad_comentario", "$_CMAPP[services_url]/diario/diario.php",$campos);
$form->submit_label = $_language[post_comment];
$form->setCancelUrl("");
$form->cancel_button->setOnClick("window.Blog_toogleComments('$post->codePost')");
$form->components[body]->setCols(50);
$form->components[body]->setRows(6);
$form->addComponent("frm_codePost", new CMWHidden("frm_codePost", $_REQUEST[frm_codePost]));
$form->addComponent("frm_codeUser", new CMWHidden("frm_codeUser", $post->codeUser));
$form->addComponent("action", new CMWHidden("frm_action","A_comentario"));



$box = new AMBoxDiaryComment;

$ico = "<img id=\"diary_comment_ico\" src=\"$_CMAPP[images_url]/ico_comentario.gif\">";
 
if($comments->__hasItems()) {
  foreach($comments as $item) {
    //    $smile = new AMSmileRender($item->body); 
    $box->add("<div> $ico  $item->body(");
    $box->add(new AMTUserInfo($item->user->items[0],AMTUserInfo::LIST_USERNAME));
    $box->add(",".date($_language[date_format],$item->time).")</div>");
  }
} else {
  $box->add("<div>$ico ".$_language[comments_dont_exists]."</div>");
}

if($_SESSION[user]) {
  $box->add("<div>");
  $box->add($form);
  $box->add("</div>");
}

$js = "parent.parent.Blog_loadComments($_REQUEST[frm_codePost])";
echo "<html><body onLoad=\"$js\">";
echo $box;
echo "</body></html>";


?>
