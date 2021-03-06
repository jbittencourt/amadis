<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminStates extends AMSimpleBox {

	public function __construct() 
	{
		global $_language;

		parent::__construct($_language['edit_tables']);
	}

	public function __toString() 
	{
		global $_language, $_CMAPP;


		$est = new AMState;

		if(!isset($_REQUEST['action1'])){
			$_REQUEST['action1'] = "";
		}
		
		switch($_REQUEST['action1']){
			case "deleteState":
				$est->codeState = $_REQUEST['idState'];
				try{
					$est->load();
					$est->delete();
				}catch(CMException $e){	}
				header('Location: editStates.php');
				break;

			case "editState":
				$est->codeState = $_REQUEST['idState'];
				$est->load();
				$est->name = $_REQUEST['nomEstado1'];
				$est->code =  $_REQUEST['desSigla1'];
				$est->country = $_REQUEST['desPais1'];
				try{
					$est->save();
				}catch(CMException $e){	}
				header('Location: editStates.php');
				break;

			case "addState":
				$est->name = $_REQUEST['nomEstado'];
				$est->code =  $_REQUEST['desSigla'];
				$est->country = $_REQUEST['desPais'];
				
				try{
					$est->save();
				}catch(CMException $e){
					
				}
				header('Location: editStates.php');
				break;

			default:
				break;
		}

		parent::add('<h3>'.$_language['edit_state'].'</h3>');
		parent::add('<table class="admin">');
		
		parent::add('<tr><th>'.$_language['state'].'</th>');
		parent::add('<th>'.$_language['sig'].'</th>');
		parent::add('<th>'.$_language['country'].'</th>');
		parent::add('<th>'.$_language['actions'].'</th></tr>');

		$res = $est->listStates();
		$i=0;
		$conteudo = "";
		foreach($res as $item){
			if($i % 2 == 0) $row = 'row0';
			else $row  = 'row1';
			
			$conteudo .= '<tr class="'.$row.'">';
			
			$conteudo .= '<td>'.$item->name.'</td>';
			$conteudo .= '<td>'.$item->code.'</td>';
			$conteudo .= '<td>'.$item->country.'</td>';
			$conteudo .= '<td>(<a href="#" onclick="$(\'hideShowState'.$i.'\').toggle();">'.$_language['edit'].'</a> | ';
			$conteudo .= '<a href="?action1=deleteState&idState='.$item->codeState.'">'.$_language['delete'].'</a>)</td></tr>';

			$conteudo .= '<tr><td class="edit-area" colspan="4">';
			$conteudo .= '<span id="hideShowState'.$i++.'" style="display:none"><form action="'.$_SERVER['PHP_SELF'].'" method="post">';
			$conteudo .= "<input type='text' value='$item->name' name='nomEstado1'> &nbsp;&nbsp;&nbsp;&nbsp;";
			$conteudo .= "<input type='text' value='$item->code' name='desSigla1' size='2'><br />";
			$conteudo .= "<input type='text' value='$item->country' name='desPais1'> &nbsp;&nbsp;&nbsp;&nbsp;";
			$conteudo .= "<input type='hidden' name='action1' value='editState'>";
			$conteudo .= "<input type='hidden' name='idState' value='$item->codeState'>";
			$conteudo .= "<input type='submit' value='".$_language['update']."'";

			$conteudo .= "</form></span></td></tr>";

		}
		$conteudo .= '<tr><td colspan="4"><a href="#" onclick="$(\'addStateForm\').toggle();">'.$_language['add_new_state'].'</a>';
		$conteudo .= "<span id='addStateForm' style='display:none'><form action = $_SERVER[PHP_SELF] method=post>";
		$conteudo .= "<input type='text' name='nomEstado' value= '".$_language['state_name']."'> &nbsp;&nbsp;&nbsp;&nbsp;";
		$conteudo .= "<input type='text' name='desSigla'  value= '".$_language['acronym']."' size='2'><br />";
		$conteudo .= "<input type='text' name='desPais' value = '".$_language['country']."'> &nbsp;&nbsp;&nbsp;&nbsp;";
		$conteudo .= "<input type='hidden' name='action1' value='addState'>";
		$conteudo .= "<input type='submit' value='".$_language['add']."'>";
		$conteudo .= "</form></span></td></tr>";
		$conteudo .= "</table>";

		parent::add($conteudo);

		return parent::__toString();
	}
}