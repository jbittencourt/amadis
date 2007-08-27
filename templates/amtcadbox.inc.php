<?php

/**
 * Register box template
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 */
class AMTCadBox extends CMHTMLObj {

  const DEFAULT_THEME = 1;
  const PROJECT_THEME = 2;
  const COMMUNITY_THEME = 3;
  const PEOPLE_THEME = 4;
  const DIARY_THEME = 5;
  const WEBFOLIO_THEME = 6;
  const ALBUM_THEME = 7;

  const CADBOX_SEARCH = "_busca";
  const CADBOX_LIST = "_listar";
  const CADBOX_CREATE = "_criar";
  const CADBOX_IMAGE = "_image";
  const CADBOX_ZOOM = "_zoom";
  const CADBOX_DEFAULT = "";

  protected $image, $titulo, $theme, $class, $titlecss;
  protected $buffer = array();
				 
  public function __construct($titulo="", $image=self::CADBOX_DEFAULT, $theme=AMTCadBox::DEFAULT_THEME) {
    parent::__construct();
  
    $this->setTitle($titulo);
    $this->requires("cadbox.css",CMHTMLObj::MEDIA_CSS);

    switch($theme) {
    case AMTCadBox::DEFAULT_THEME:
      $this->theme = "box_cadastro";
      $this->image = "box_cadproj_01$image.gif";
      $this->class = 'cad-box-default';
      break;
    case AMTCadBox::WEBFOLIO_THEME:
      $this->titlecss = 'webfolio-title';
      $this->theme = "box_cadwebfolio";
      $this->image = 'box_cadwebfolio'.$image.'.gif';
      $this->class = 'cad-box-webfolio';
      break;
    case AMTCadBox::PROJECT_THEME:
      $this->titlecss = "project-title";
      $this->theme = "box_cadproj";
      $this->image = "box_cadproj_01$image.gif";
      $this->class = 'cad-box-project';
      break;
    case AMTCadBox::COMMUNITY_THEME:
      $this->titlecss = "txttitcomunidade";
      $this->theme = "box_cad_comunidade";
      $this->image = "box_cad_comunidade_01$image.gif";
      $this->class = 'cad-box-community';
      break;
    case AMTCadBox::PEOPLE_THEME:
      $this->titlecss = "people-title";
      $this->theme = "box_cad_pessoas";
      $this->image = "box_cad_pessoas_01$image.gif";
      $this->class = 'cad-box-people';
      break;
    case AMTCadBox::DIARY_THEME:
      $this->titlecss = "diary_title";
      $this->theme = "box_cad_diario";
      $this->image = "box_cad_diario_01$image.gif";
      $this->class = 'cad-box-diary';
      break;
    case AMTCadBox::ALBUM_THEME:
      $this->titlecss = "album-title";
      $this->theme = "box_cad_album";
      $this->image = "box_cad_album_01$image.gif";
      $this->class = 'cad-box-album';
      break;
    }
  }

  public function add($value) {
    $this->buffer[] = $value;
  }


  public function setTitle($value) {
    global $_CMAPP;
    $this->titulo = $value;

  }

  public function __toString() {
    global $_CMAPP;
	
	$injection = array(
		'box_class'=>$this->class,
		'box_id'=>'cad-box',
		'cad_box_title'=>'<span class="'.$this->titlecss.'">'. $this->titulo .'</span>',
		'box_content'=>implode("\n", $this->buffer),
		'box_left_corner_style'=>'background: transparent url(../../media/images/'.$this->image.') no-repeat;'
	);
    
    
	parent::add(AMHTMLPage::loadView($injection, 'box'));
	
	return parent::__toString();
	
  }
}