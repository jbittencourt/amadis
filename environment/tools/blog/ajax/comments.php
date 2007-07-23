<?php
include('../../../config.inc.php');

global $_CMAPP;

$_language = $_CMAPP[i18n]->getTranslationArray("blog");
$_CMAPP['smartform'] = array();
$_CMAPP['smartform']['language'] = $_language;

$post = new AMBlogPost;
$post->codePost = $_REQUEST['frm_codePost'];
try {
	$post->load();
}catch(CMException $e){
	new AMErrorReport($e, "AMBlog::getCommentsToPost", AMLog::LOG_BLOG);
}

if(isset($_REQUEST['frm_action']) && $_REQUEST['frm_action'] == 'reply_comment') {
	
	$campos = array("body");
	$form = new AMWSmartForm('AMBlogComment', 'reply-comment-'.$_REQUEST['frm_codeComment'], "$_CMAPP[services_url]/blog/blog.php",$campos);
	$form->submit_label = $_language['send'];
	$form->setCancelOff();
	$form->components['body']->setLabel($_language['my_reply']);
	$form->addComponent("frm_codePost", new CMWHidden("frm_codePost", $post->codePost));
	$form->addComponent("frm_parentComment", new CMWHidden("frm_parentComment", $_REQUEST['frm_codeComment']));
	$form->addComponent("frm_codeUser", new CMWHidden("frm_codeUser", $post->codeUser));
	$form->addComponent("action", new CMWHidden("frm_action","A_reply_comentario"));
	echo $form->__toString();
	die();
	
}

$comments = $post->listComments();

$box = new AMBoxBlogComment;

		//$ico = "<img id='diary_comment_ico' src='".$_CMAPP['images_url']."/ico_comentario.gif'>";

if($comments->__hasItems()) {
	foreach($comments as $item) {
		$smile = new AMSmileRender($item->body);
		$box->add('<div><span class="comment-date">(');
		$box->add(new AMTUserInfo($item->user->items[0], AMTUserInfo::LIST_USERNAME));
		$box->add(','.date($_language['date_format'], $item->time).')</span> - ');
		$box->add('<a href="javascript:Blog.replyComment('.$post->codePost.', '.$item->codeComment.');">'.$_language['reply'].'</a></div>');
		$box->add('<div class="comment-entry">'.nl2br(strip_tags($smile->__toString())).'</div>');
		
		if($item->answered == AMBlogComment::ENUM_ANSWERED_TRUE) {
			$ans = $post->listComments($item->codeComment);
			if($ans->__hasItems()) {
				$box->add('<div class="comment-answer"');
				foreach($ans as $answer) {
					$smile = new AMSmileRender($answer->body);
					$box->add('<div><span class="comment-date">(');
					$box->add(new AMTUserInfo($answer->user->items[0], AMTUserInfo::LIST_USERNAME));
					$box->add(','.date($_language['date_format'], $answer->time).')</span></div>');
					$box->add('<div class="comment-entry">'.nl2br(strip_tags($smile->__toString())).'</div><hr />');
				}
				$box->add('</div>');
			}
		}
		$box->add('<div style="display:none;" id="reply-'.$item->codeComment.'"></div><hr />');
	}
} else {
	$box->add("<div class=\"no-comments\">$ico ".$_language['comments_dont_exists']."</div>");
}

$campos = array("body");
$form = new AMWSmartForm('AMBlogComment', "cad_comentario", "$_CMAPP[services_url]/blog/blog.php",$campos);
$form->submit_label = $_language['send'];
$form->setCancelOff();
		//$form->addComponent('cancel', new CMWButton('cancel',$_language['cancel'], 'reset'));
		//$form->components['cancel']->setOnClick("Blog_toogleComments('$post->codePost');");
$form->components['body']->setLabel($_language['frm_body']);
$form->addComponent("frm_codePost", new CMWHidden("frm_codePost", $post->codePost));
$form->addComponent("frm_codeUser", new CMWHidden("frm_codeUser", $post->codeUser));
$form->addComponent("action", new CMWHidden("frm_action","A_comentario"));
$box->add($form);

echo $box;