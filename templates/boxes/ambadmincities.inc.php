<?php
/**
 * @package AMADIS
 * @subpackage AMBoxes
 */

class AMBAdminCities extends AMSimpleBox implements CMActionListener {

	public function __construct() {
		global $_language;

		parent::__construct($_language['edit_tables']);
	}

	public function doAction() {
		global $_CMAPP,$_language;

		$city = new AMCity;

		if(!isset($_REQUEST['action'])){
			$_REQUEST['action'] = "";
		}

		switch($_REQUEST['action']){
			case "deleteCity":
				$city->codeCity = $_REQUEST['idCidade'];
				try{
					$city->load();
					$city->delete();
				}catch(AMException $e){
				}
				break;

			case "editCity":
				$city->codeCity = $_REQUEST['idCidade'];
				$city->load();
				$city->name = $_REQUEST['nomCidade1'];
				$city->state =  $_REQUEST['codEstado1'];
				try{
					$city->save();
				}catch(AMException $e){
				}
				break;

			case "addCity":
				$city->name = $_REQUEST['nomCidade'];
				$city->state =  $_REQUEST['codEstado'];
				$city->time = time();
				try{
					$city->save();
				}catch(AMException $e){
				}
				break;

		}
	}

	public function __toString() {
		global $_language, $_CMAPP;

		/*$city = new AMCity;*/

		/*	if(!isset($_REQUEST['action'])){
			$_REQUEST['action'] = "";
			}

			switch($_REQUEST['action']){
			case "deleteCity":
			$city->codeCity = $_REQUEST['idCidade'];
			try{
			$city->load();
			$city->delete();
			}catch(AMException $e){
			}
			break;

			case "editCity":
			$city->codeCity = $_REQUEST['idCidade'];
			$city->load();
			$city->name = $_REQUEST['nomCidade1'];
			$city->state =  $_REQUEST['codEstado1'];
			try{
			$city->save();
			}catch(AMException $e){
			}
			break;

			case "addCity":

			$city->name = $_REQUEST['nomCidade'];
			$city->state =  $_REQUEST['codEstado'];
			$city->time = time();
			try{
			$city->save();
			}catch(AMException $e){
			}
			break;

			}*/

		parent::add("<table><tr><td><h3>". $_language['edit_cities']. "</h3></td></tr>");

		$est = new AMState;
		//------ Conta qntos estados existem cadastrados
		$q = new CMQuery('AMState');
		$extados = $q->execute();
		//---------------------------------------------
		$conteudo = "";
		$i=0;
		foreach($extados as $it){
			$est->codeState = $it->codeState;
			$res = $est->listCities();

			foreach($res as $item){
				$conteudo .= "<tr><td>";
				$conteudo .= $item->name;
				$conteudo .= " &nbsp;&nbsp;( <a href='#' onClick='AM_togleDivDisplay(\"hideShow".$i."\")'>".$_language['edit']."</a> | <a href='?action=deleteCity&idCidade=$item->codeCity'>".$_language['delete']."</a> )</td></tr>";
				//----------formulario
				$conteudo .= "<tr><td><span id='hideShow".$i++."' style='display:none'><form action = '?action=editCity' method=post>";
				$conteudo .= "<input type='text' value='$item->name' name='nomCidade1'><br />";
				$conteudo .= "<select name='codEstado1'>";
				//----
				$ee = new AMState;
				$he = $ee->listStates();
				foreach($he as $items){
					if($items->codeState == $item->state){
						$conteudo .= "<option value='$items->codeState' selected>$items->name</option>";
					}
					else{
						$conteudo .= "<option value='$items->codeState'>$items->name</option>";
					}
				}
				//----
				$conteudo .= "</select>";
				$conteudo .= "<input type='hidden' name='action' value='editCity'>";
				$conteudo .= "<input type='hidden' name='idCidade' value='$item->codeCity'>";
				$conteudo .= "<input type='submit' value='".$_language['update']."'";
				$conteudo .= "</form></span></td></tr>";

			}
		}
		$conteudo .= "<tr><td><a href='#' onClick='AM_togleDivDisplay(\"hideShowAddCity\")'>".$_language['add_new_city']."</a></td></tr>";
		$conteudo .= "<tr><td><span id='hideShowAddCity' style='display:none'><form action = '?action=addCity' method=post>";
		$conteudo .= "<input type='text' value='".$_language['city_name']."' name='nomCidade'>";
		$conteudo .= "<select name='codEstado'>";
		//----
		$ee = new AMState; $he = $ee->listStates();
		$conteudo .= "<option value='' selected>".$_language['select_state']."</option>";
		foreach($he as $items){
			$conteudo .= "<option value='$items->codeState'>$items->name</option>";
		}
		//----
		$conteudo .= "</select>";
		$conteudo .= "<input type='submit' value='".$_language['add']."'";
		$conteudo .= "<input type='hidden' name='action' value='addCity'>";
		$conteudo .= "<input type='hidden' name='idCidade' value='$item->codeCity'>";
		$conteudo .= "</form></span></td></tr>";
		$conteudo .= "</table>";

		parent::add($conteudo);

		return parent::__toString();
	}

}