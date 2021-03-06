<?php
/**
 * @package AMADIS
 * @subpackage AMTemplates
 */

class AMTIconList extends CMHTMLObj {

  const PROJECT_LIST = 0;
  const COMMUNITY_LIST = 1;
  const USER_LIST = 2;

  protected $list;
  protected $items_per_line = 5;
  protected $type;

  public function __construct(CMContainer $list,$type = self::PROJECT_LIST,$items=4) {
    parent::__construct();
    $this->requires("iconlist.css",CMHTMLObj::MEDIA_CSS);

    $this->items_per_line = $items;
    $this->list = $list;
    $this->type = $type;
  }


  public function __toString() {
    	global $_CMAPP;

    	if($this->list->__hasItems()) {
      		$cont = 0;
      		parent::add('<div id="icon-table">');
      		foreach($this->list as $item) {
				if($cont % $this->items_per_line == 0) {
	  				if($cont!=0) parent::add('</span>');
	  				parent::add('<span id="icon-line">');
				}
				$cont++;

				$icon = "";
				$name = "";
				$url = "#";

				switch($this->type) {
	  				/* PROJECTS */
					case self::PROJECT_LIST:
	  					$f = AMProjectImage::getImage($item);;
	  					$name = $item->title;
	  					$type = 'PROJECT';
		  				$url = $_CMAPP['services_url'].'/projects/project.php?frm_codProjeto='.$item->codeProject;
		  				break;
	  				/* COMMUNITIES */
					case self::COMMUNITY_LIST:
	  					$f = AMCommunityImage::getImage($item);
	  					$name = $item->name;
	  					$type = 'COMMUNITY';
	  					$url = $_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$item->code;
	  					break;
					case self::USER_LIST:
	  					$f = AMUserPicture::getImage($item);
	  					$name = $item->name;
	  					$type = 'USER';
	  					$url = $_CMAPP['services_url'].'/webfolio/userinfo_details.php?frm_codeUser='.$item->codeUser;
	  					break;
				}

				$thumb = new AMIconThumb;
				try {
					$thumb->codeFile = $f;
					try {
					  	$thumb->load();
	  					$icon = $thumb->getView();
					}catch(CMDBException $e) {
				  		new AMErrorReport($e, 'AMTIconList::__toString', AMLog::LOG_WEBFOLIO);
				  		parent::add(new AMAlert($_language['error_loading_image']));
					}
				}catch(CMObjEPropertieValueNotValid $e) {
					switch($type) {
						case 'USER':
							$thumb = new AMIconThumb(AMUserPicture::DEFAULT_IMAGE);
							$thumb->load();
							$icon = $thumb->getView();
							break;
						case 'PROJECT':
							$thumb = new AMIconThumb(AMProjectImage::DEFAULT_IMAGE);
							$thumb->load();
							$icon = $thumb->getView();
							break;
						case 'COMMUNITY':
							$thumb = new AMIconThumb(AMCommunityImage::DEFAULT_IMAGE);
							$thumb->load();
							$icon = $thumb->getView();
							break;
					}
				}

				parent::add('<a href="'.$url.'" id="icon-el">');
				parent::add('<span id="icon-image">');
				parent::add($icon);
				parent::add('</span>');
				parent::add('<br /><span id="icon-legend">'.$name.'</span>');
				parent::add('</a>');
				parent::add('<span id="icon-space"></span>');
      		}
    	}
    	parent::add('</span>');
    	parent::add('</div>');
    	return parent::__toString();
  	}
}