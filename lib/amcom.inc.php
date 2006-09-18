<?
include("jpspan.inc.php");

class AMComunicator { 
  
  protected $classes = array();
  protected $server;

  public function addClassHandler($classHandler) {
    $this->classes[] = $classHandler;
  }

  public function __initServer() {
    $this->server = new JPSpan_Server_PostOffice();
    
    // Register your class with it...
    if(!empty($this->classes)) {
      foreach($this->classes as $item) {
	$this->server->addHandler(new $item);
      }
    }

    // This allows the JavaScript to be seen by
    // just adding ?client to the end of the
    // server's URL

    if (isset($_SERVER['QUERY_STRING']) &&
	strcasecmp($_SERVER['QUERY_STRING'], 'client')==0) {

      // Compress the output Javascript (e.g. strip whitespace)
      // turn this off it has performance problems
      define('JPSPAN_INCLUDE_COMPRESS',false);

      // Display the Javascript client
      $this->server->displayClient();

    } else {

      // This is where the real serving happens...
      // Include error handler
      // PHP errors, warnings and notices serialized to JS
      require_once JPSPAN . 'ErrorHandler.php';
      
      // Start serving requests...
      $this->server->serve();
    }
    
  }

}
?>