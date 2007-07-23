<?php

class AMBViewAlbum extends AMListBox {

	public function __construct(CMContainer $items,$title,$type=AMTCadBox::CADBOX_IMAGE) {
		parent::__construct($items,$title, self::ALBUM, $type);
	}

	public function __toString() {
		global $_language,$_CMAPP, $page;
		$album = new AMAlbum;	
		parent::add("<table id='".$this->class_prefix."' bgcolor='#f6f8ff'>");
		if($this->itens->__hasItems()) {
			$flag=0; //this flag is used for break the album in two columns.
			parent::add("<tr>");
			foreach($this->itens as $item){
				parent::add("<td valign='top' align='center'><table border='1' class='".$this->class_prefix."_table'><tr>");
				parent::add("<td align='center'>");
				$image = new AMAlbumThumb;
				$image->codeFile = $item->codePhoto;
				$image->load();
				parent::add($image->getView());
				parent::add("</td></tr><tr><td align='right'>");
				parent::add("<br />".nl2br($item->comments)."&nbsp;&nbsp;&nbsp;&nbsp;");
				//Icons controller
				parent::add("<a href='$_CMAPP[services_url]/album/zoom.php?frm_codePhoto=".$item->codePhoto."&state=view'><img src='$_CMAPP[images_url]/ico_ampliar_foto.gif'></a><br />");
				parent::add("</td></tr></table></td>"); //closing the '$item[x]' block

				if($flag == 1){  //if 2 photos was listed yet, lets put the next in a new line!
					parent::add("</tr><tr>");
					$flag = 0;
				}
				else
					$flag++;   //else lets inc the flag, n wait for the next photo! :D~
			}
		}
		else
			parent::add("<tr><td class='".$this->class_prefix."_bg1'><br />".$_language['noPhotos']."</td><td></td></tr>");

		parent::add("</table>");
		return parent::__toString();
	}
}