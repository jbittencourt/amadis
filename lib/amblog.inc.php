<?php
class AMBlog implements AMAjax {
	
 	public function getCommentsToPost($codePost) 
 	{
 		global $_CMAPP;
 		
 		$_language = $_CMAPP[i18n]->getTranslationArray("blog");
		$_CMAPP['smartform'] = array();
		$_CMAPP['smartform']['language'] = $_language;

		$post = new AMBlogPost;
 		$post->codePost = $codePost;
 		try {
 			$post->load();
 		}catch(CMException $e){
 			new AMErrorReport($e, "AMBlog::getCommentsToPost", AMLog::LOG_BLOG);
 		}
 		
		$comments = $post->listComments();
		
		$box = new AMBoxBlogComment;

		$ico = "<img id='diary_comment_ico' src='".$_CMAPP['images_url']."/ico_comentario.gif'>";
		
		if($comments->__hasItems()) {
  			foreach($comments as $item) {
    		    $smile = new AMSmileRender($item->body); 
    			$box->add("<div> $ico  ".$smile->__toString()."(");
    			$box->add(new AMTUserInfo($item->user->items[0],AMTUserInfo::LIST_USERNAME));
    			$box->add(",".date($_language['date_format'],$item->time).")</div>");
  			}
		} else {
  			$box->add("<div>$ico ".$_language['comments_dont_exists']."</div>");
		}

		$campos = array("body");
		$form = new AMWSmartForm('AMBlogComment', "cad_comentario", "$_CMAPP[services_url]/blog/blog.php",$campos);
		$form->submit_label = $_language['send'];
		$form->setCancelOff();
		//$form->addComponent('cancel', new CMWButton('cancel',$_language['cancel'], 'reset'));
		//$form->components['cancel']->setOnClick("Blog_toogleComments('$post->codePost');");
		$form->components['body']->setCols(50);
		$form->components['body']->setRows(6);
		$form->components['body']->setLabel($_language['frm_body']);
		$form->addComponent("frm_codePost", new CMWHidden("frm_codePost", $post->codePost));
		$form->addComponent("frm_codeUser", new CMWHidden("frm_codeUser", $post->codeUser));
		$form->addComponent("action", new CMWHidden("frm_action","A_comentario"));
		$box->add($form);
		
		$result = array();
		$result['box'] = $box->__toString();

		return $result;
 			
 	}
	public function xoadGetMeta() {
    	$methods = array('getCommentsToPost');
    	XOAD_Client::mapMethods($this, $methods);
    
	    XOAD_Client::publicMethods($this, $methods);
  	}
}
?>