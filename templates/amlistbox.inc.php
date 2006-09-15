<?
/** 
 * Listagem generica com thumbnails de usuarios ou projetos
 *
 * @package AMADIS
 * @subpackage Core
 * @param items CMContainer
 * @param title String
 * @param theme AMTCadBox themes constants
 * @param type AMTCadBox type constants
 */

abstract class AMListBox extends AMTCadBox {

  const PEOPLE = 0;
  const PROJECT = 1;
  const COMMUNITY = 2;
  const ALBUM = 3;

  protected $itens;
  protected $class_prefix;

  public function __construct(CMContainer $items,$title,$theme, $type=AMTCadBox::CADBOX_SEARCH) {
    global $_language;
  
    switch($theme) {
    case self::PEOPLE:
      $box_theme=AMTCadBox::PEOPLE_THEME;
      $this->class_prefix = 'people';
      break;
    case self::PROJECT:
      $box_theme=AMTCadBox::PROJECT_THEME;
      $this->class_prefix = 'project';
      break;
    case self::COMMUNITY:
      $box_theme = AMTCadBox::COMMUNITY_THEME;
      $this->class_prefix = 'community';
      break;
    case self::ALBUM:
      $box_theme = AMTCadBox::ALBUM_THEME;
      $this->class_prefix = 'album';
    }

    parent::__construct($title, $type, $box_theme);
    $this->itens = $items;
  }

}
?>