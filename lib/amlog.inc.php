<?php
/**
 * Log messages error, this is a better way to control error events of the system.
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @access public or private
 * @package AMADIS
 * @subpackage AMErrorReport
 * @version 1.0
 * @author Robson Mendonca <robson@lec.ufrgs.br>
 * @see AMErrorReport,AMError
 */
class AMLog {

  
	/**
	 * @staticvar array $errors - list of errors that will logged
   	 */
	static public $errors = array();
	
	const LOG_CORE = 'error.log';
	const LOG_WEBFOLIO = 'webfolio-error.log';
	const LOG_BLOG = 'blog-error.log';
	const LOG_PROJECTS = 'projects-error.log';
	const LOG_COMMUNITIES = 'communities-error.log';
	const LOG_AGGREGATOR = 'aggregator-error.log';
	const LOG_ALBUM = 'album-error.log';
	const LOG_CHAT = 'chat-error.log';
	const LOG_FINDER = 'finder-error.log';
	const LOG_LIBRARY = 'library-error.log';
	const LOG_FORUM = 'forum-error.log';
	const LOG_FRIENDS = 'friends-error.log';
	const LOG_WIKI = 'wiki-error.log';
	
  	/**
   	 * Add a new error in error.log array, after execute AMError::commit() to save in log file.
  	 *
  	 * @param String $class - CSS styled message to user
  	 * @param String $e - Exception message throwed to CMDevel or AMAPI
	 * @param String $module - Medule what execute wrong action
  	 */
  	public function __construct($class, $e, $module=self::LOG_CORE) {
    
		self::$errors[] = array("thrower"=>$class,
				    			"exception"=>$e,
    							"module"=>$module,
			    				);
	}

 	/**
  	 * Register errors messages in modules log files
  	 *
  	 * @access public 
  	 * @static
  	 * @param void
  	 * @return void
  	 */
	public static function commit() 
	{
		global $_conf;
    	$path = (string) $_conf->app[0]->paths[0]->log;
		
		if(isset($_SESSION['user'])) {
    		$actor_id = $_SESSION['user']->codeUser;
			$actor_name = $_SESSION['user']->username;
		}
    	$errors = self::getErrors();
		$HASH_ERRORS = array();
		
    	if(!empty($errors)) {
    		foreach($errors as $er) {
    			if(!key_exists($er['module'], $HASH_ERRORS)) {
					$HASH_ERRORS[$er['module']] = array();    				
    			}

    			$h = "3";// Hour for time zone goes here e.g. +7 or -4, just remove the + or -
				$hm = $h * 60;
				$ms = $hm * 60;
				$gmdate = gmdate("M d Y H:i:s ", time()-($ms)); // the "-" can be switched to a plus if that's what your time zone is.
				$HASH_ERRORS[$er['module']][] = "$gmdate - $actor_name($actor_id)<br />CLASS:$er[thrower]:Exception:$er[exception]\n";
    			
    		}

    		foreach($HASH_ERRORS as $k=>$er) {
    			$flPath = $path.'/'.$k;
	    		@$flog = fopen($flPath, "a");
	    		@fwrite($flog, implode("\n", $er));
      			@fclose($flog);
    		}
    	}
  	}

  	public static function getErrors() {
    	return self::$errors;
  	}
}