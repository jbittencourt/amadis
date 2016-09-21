<?php

/**
 * Base to load an AMADIS page5~
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public
 * @package AMADIS
 * @subpackage Core
 * @version 1.0
 * @author Juliano Bittencourt <juliano@lec.ufrgs.br>
 */
class AMHTMLPage extends CMHTMLPage {

 	public function __construct()
 	{
    	parent::__construct("AMADISPage");
    	$this->requires("amadis.css",self::MEDIA_CSS);
	}

	/**
	 * Load a simple visualization, and inject variables
	 *
	 * @param array $vars - variables to injection in view
	 * @param string $view - view file. Ex: color_box
	 * @param string $location - view location. Ex: core_templates/boxes
	 * @return string
	 */
	public static function loadView($vars, $view, $location='core_templates')
	{
		global $_CMAPP, $_language;

		//start buffer
		ob_start();

		if(is_array($vars)) {
			foreach($vars as $var=>$value) {
				$$var = $value;
			}
		}

		$path = $_CMAPP['path'].'/templates/'.$location;
		$ext = pathinfo($view, PATHINFO_EXTENSION);
		$file = ($ext == '') ? $view.'.phtml' : $view;
		$path .= '/'.$file;

		if(file_exists($path)) {
			if ((bool) @ini_get('short_open_tag') === FALSE) {
				echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($path))).'<?php ');
			} else {
				include($path);
			}
		}

		$contents = ob_get_contents();

		//Clean and return
		ob_end_clean();
		return $contents;
	}
}
