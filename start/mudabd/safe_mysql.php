<?

//define('SQL_DEBUG', 1); // uncomment to always dump sql statement in Exception message (for debugging purposes only)
require_once('exception.php');
install_exception_errorhandler(); // Translates PHP Warnings and User-Errors into corresponding Exceptions

class DBException extends Exception {}; // Base class for Database Exceptions

class DBConnectException extends DBException {} // Thrown when a Database connection fails

// Thrown when problems with the execution or preparation of an SQL Query occurs.
class SQLException extends DBException {
  private $sql;
  private $sqlerrmsg;
    
  function __construct($msg, $code, $sql) {
    /* Pass all arguments passed to the constructor on to the parent's constructor */
    $this->sqlerrmsg = $msg;
    if (defined('SQL_DEBUG')) {
      $msg .= " in '$sql'";
    }
    parent::__construct($msg, $code);
    $this->sql = $sql;
  }
    
  function getSQL() {
    return $this->sql;
  }

  function getSQLError() {
    return $this->sqlerrmsg;
  }
    
}

// Helper function for constructor
function add_single_quotes($arg)
{
  return "'" . addcslashes($arg, "'\\") . "'";
}

// Written by Kai Londenberg K.Londenberg (at) librics (dot) de
// http://www.librics.de/ based on an example at zend.com

// mysqli wrapper class with exception support
class safe_mysqli extends mysqli {

  function __construct() {
    /* Pass all arguments passed to the constructor on to the parent's constructor */
    $args = func_get_args();
    try {
      eval("parent::__construct(" . join(',', array_map('add_single_quotes', $args)) . ");");
    } catch (WarningException $we) {
      if(mysqli_connect_error()){
	throw new DBConnectException(mysqli_connect_error(), mysqli_connect_errno());
	/* Throw an error if the connection fails */
      }
    }

  }
      
  function query($query) {
    $result = parent::query($query);
    if(mysqli_error($this)){
      throw new SQLException(mysqli_error($this), mysqli_errno($this), $query);
    }
    return $result;
  }

  function prepare($query) {
    $result = parent::prepare($query);
    if(mysqli_error($this)){
      throw new SQLException(mysqli_error($this), mysqli_errno($this), $query);
    }
    return $result;
  }

}

?>