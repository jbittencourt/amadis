<?

$inc_path = get_include_path();
$new = "$_CMAPP[path]/templates:$_CMAPP[path]/templates/boxes:$_CMAPP[path]/lib";
set_include_path("$inc_path:$new");

function __autoload($class_name) {
  $filename = strtolower($class_name).'.inc.php';
  try {
    __cmautoload($class_name);
  } catch(CMException $e) {
    @include($filename);
  }
}


?>