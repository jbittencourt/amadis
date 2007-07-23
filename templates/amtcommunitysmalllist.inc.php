<?php

/**
 * Smaall list of the communities showed in the initial page AMADIS
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage AMCommunity
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMTCommunitySmallList extends CMHTMLObj {

	protected $items;

	public function __construct(CMContainer $items) {
		parent::__construct();
		$this->items = $items;
	}


	public function __toString() {
		global $_CMAPP;

		parent::add('<div class="small-list">');
		if($this->items->__hasItems()) {
			foreach($this->items as $comm) {
    			parent::add('<div class="item">');
	    		parent::add('  <div class="thumb">');
	    		try {
					$img = AMCommunityImage::getThumb($comm,true);
					parent::add($img->getView());
				} catch(CMException $e) {
					parent::add(new AMTProjectImage(AMProjectImage::DEFAULT_IMAGE, AMImageTemplate::METHOD_DEFAULT, true));
				}
    			parent::add('  </div>');
    		
	    		$text = substr($comm->description,0,150);
				if($text!=$comm->description) {
					$text.="...";
				}
				    			
				parent::add('  <div class="description">');
				parent::add('    <a href="'.$_CMAPP['services_url'].'/communities/community.php?frm_codeCommunity='.$comm->code.'">'.$comm->name.'</a> - '.$text);
    			parent::add('  </div>');
	
    			parent::add('</div>');
    			parent::add('<br class="clear" />');
			}
		}
		parent::add('</div>');

		return parent::__toString();
	}
}
?>