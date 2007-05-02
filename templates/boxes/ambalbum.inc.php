<?php
class AMBAlbum extends AMListBox implements CMActionListener {


	public function __construct(CMContainer $items,$title,$type=AMTCadBox::CADBOX_IMAGE) {
		parent::__construct($items,$title, self::ALBUM, $type);
	}

	public function doAction() {
		if(isset($_REQUEST['action'])) {
			$album = new AMAlbum;
			switch($_REQUEST['action']){
				case "save":						
					try{
						$album->saveEntry();
						header("Location:$_SERVER[PHP_SELF]?frm_ammsg=file_successful_sent");
					}catch(CMException $e){
						new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
						header("Location:$_SERVER[PHP_SELF]?frm_amerror=send_file");
					}						
					break;

				case "delete":
					try{
						$album->deleta($_REQUEST["id"]);
						header("Location:$_SERVER[PHP_SELF]?frm_ammsg=file_successful_delete");
					}catch(CMException $e){
						new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
						header("Location:$_SERVER[PHP_SELF]?frm_amerror=del_file");
					}
					break;

				case "edita_comment":
					try{
						$album->editComment($_REQUEST['photo'], $_REQUEST['comment_edited']);
						header("Location:$_SERVER[PHP_SELF]?frm_ammsg=comment_successfull_edited");
					}catch(CMException $e){
						new AMErrorReport($e, 'AMBAlbum::doAction', AMLog::LOG_ALBUM);
						header("Location:$_SERVER[PHP_SELF]?frm_amerror=edit_comment");
					}
					break;
			}
		}
	}

	public function __toString() {
		global $_language,$_CMAPP;

		parent::add("<table id='".$this->class_prefix."' bgcolor='#f6f8ff'>");
		if($this->itens->__hasItems()) {
			$flag=0; //this flag is used for break the album in two columns.
			parent::add("<tr>");
			foreach($this->itens as $item){
				parent::add("<td valign='top' align='center'><br><table border='1' class='".$this->class_prefix."_table'><tr>");
				parent::add("<td align='center'>");
				
				$pict = $item->getPhoto();
				
				$thumb = AMAlbumPicture::getThumb($pict);

				parent::add($thumb->getView());				
				parent::add("</tr><tr><td align='right'>");
				parent::add("<br>".nl2br($item->comments)."&nbsp;&nbsp;&nbsp;&nbsp;");
				//Icons controller
				parent::add("<a href='$_CMAPP[services_url]/album/zoom.php?frm_codePhoto=".$item->codePhoto."&state=personal'><img src='$_CMAPP[images_url]/ico_ampliar_foto.gif'></a> <a href='$_SERVER[PHP_SELF]?action=delete&id=".$item->codePhoto."'><img src='$_CMAPP[images_url]/ico_excluir_foto.gif'></a> <a onClick='AM_togleDivDisplay(\"hideShow_".$item->codePhoto."\")'><img src='$_CMAPP[images_url]/icon_editar_legenda.gif'></a><br>");
				parent::add("<span id='hideShow_".$item->codePhoto."' style='display:none'><form method='post' action='$_SERVER[PHP_SELF]?action=edita_comment&photo=$item->codePhoto'><input type='text' name='comment_edited' value='$item->comments' size='12'><input type='submit' value='".$_language['ok']."'></form></span>");
				parent::add("</td></tr></table></td>"); //closing the '$item[x]' block

				if($flag == 1){  //if 2 photos was listed yet, lets put the next in a new row!
					parent::add("</tr><tr>");
					$flag = 0;
				}
				else
				$flag++;   //else lets inc the flag, n wait for the next photo! :D~
			}
		}
		else
		parent::add("<tr><td class='".$this->class_prefix."_bg1'><br>".$_language['noPhotos']."</td><td></td></tr>");

		parent::add("<tr><td><br><br><br><img src='$_CMAPP[images_url]/pt-br/tit_enviar_imagens_album.gif'></td><td></td></tr>");
		parent::add("<tr><td><img src='$_CMAPP[media_url]/images/dot.gif' width='1' height='7' border='0'><table cellpadding='1' cellspacing='8' border='0'>");
		parent::add("<tr><td>");
		parent::add("<form enctype='multipart/form-data' action='$_SERVER[PHP_SELF]' method='post' name='upload'>");
		parent::add("".$_language['photo']."<br><input name='frm_picture' type='file'><input type='hidden' name='fieldName' value='frm_picture'><input type='hidden' name='MAX_FILE_SIZE' value='2048'><input type='hidden' name ='action' value ='save'></td></tr>");
		parent::add("<tr><td><br>".$_language['comment']."<br><input type='text' size='20' name='comment'>");
		parent::add(" &nbsp;&nbsp;<input type='submit' value='$_language[send]'></form></td></tr></table></td><td></td></tr>");
		parent::add("</table>");
		return parent::__toString();
	}
}