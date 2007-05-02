<?php

class AMException extends CMException {
  public function __construct($message) {
    parent::__construct("AMADIS",$message);
  }
}


class AMEImage extends AMException {

  public function __construct() {
    parent::__construct("Image type not supported.");
  }

}

class AMExceptionFile extends AMException {

  public function __construct($file, $errorMsg) {
    parent::__construct("Could not load the file $file. Error: " . $errorMsg);
  }
}

class AMUECannotCreateDir extends AMException {
  public function __construct($dirName) {
    parent::__construct("Cannot create dir: ".$dirName);
    
    return $dirName;
  }
}

class AMUEFileExists extends AMException {
  public function __construct($fileName) {
    parent::__construct("This file exists: ".$fileName);
  }
}

class AMUEFileNotExists extends AMException {
  public function __construct($fileName) {
    parent::__construct("This file not exists: ".$fileName);
  }
}

class AMUECannotDeleteDiretory extends AMException {
  
  public function __construct($dirName) {
    parent::__construct("This diretory cannot be deleted: ".$dirName);
  }
}

class AMUECannotRemoveFile extends AMException {
  
  public function __construct($dirName) {
    parent::__construct("The file cannot be deleted");
  }
}

class AMUEUploadDirNotSpecified extends AMException {
  public function __construct() {
    parent::__construct("Upload diretory was not specified");
  }
}

class AMUEFileUploadedError extends AMException {
  
  public function __construct($error) {
    parent::__construct("The following error occurred: ".$error);
  }
  
}

class AMUEFileDontUploaded extends AMException {
  public function __construct() {
    parent::__construct("The file was not uploaded");
  }
}

class AMUEDontFoundFilesToUpload extends AMException {
  public function __construct($prefix) {
    parent::__construct("The following prefix: <u>$prefix</u> was not found in \$_FILES array.");
  }
}

class AMWEFirstLogin extends AMException {
  public function __construct() {
    parent::__construct("This is a user first login.");
  }
}